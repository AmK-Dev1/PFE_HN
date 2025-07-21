<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Imports\BudgetsImport;
use App\Models\Budget;
use App\Models\Configuration;
use App\Models\Subtype;
use App\Models\Type;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BudgetController extends Controller
{

    public function create(Request $request)
    {
        // Get the start and end years from the request query parameters
        $start = $request->query('start');
        $end = $request->query('end');
        $years = $request->query('years');
        $types = Type::with('subtypes')->get();
        // Pass them to the view
        return view('user.budgets.create', compact('start', 'end','years','types'));
    }

    public function getSubtypes($typeId)
    {
        $subtypes = Subtype::where('type_id', $typeId)->get();
        return response()->json($subtypes);
    }

    public function import(Request $request, $year)
    {
        // Handle the uploaded file
        $file = $request->file('file');

        // Initialize and use the importer to parse the file
        $import = new BudgetsImport();
        Excel::import($import, $file);

        // Get the parsed budget data from the importer
        $budgets = $import->getBudgets();

        // Retrieve all types with their associated subtypes for dropdowns in the preview view
        $types = Type::with('subtypes')->get();

        // Format the types with their subtypes for JavaScript usage in the frontend
        $typesWithSubtypes = $types->mapWithKeys(function ($type) {
            return [
                $type->id => $type->subtypes->map(function ($subtype) {
                    return [
                        'id' => $subtype->id,
                        'name' => $subtype->name
                    ];
                })->toArray()
            ];
        });

        // Retrieve configurations specific to the current company
        $companyId = session('company_id');
        $configurations = Configuration::where('company_id', $companyId)->get();

        // Apply configurations to automatically assign the `type_id` based on code range
        foreach ($budgets as &$budget) {
            $budget['type_id'] = null; // Default to null in case no configuration matches
            $budget['subtype_id'] = null;

            foreach ($configurations as $config) {
                if ($budget['code'] >= $config->code_start && $budget['code'] <= $config->code_end) {
                    $budget['type_id'] = $config->type_id;
                    break; // Stop searching once a matching configuration is found
                }
            }
        }

        // Redirect to the preview view with all necessary data
        return view('user.budgets.preview', compact('budgets', 'year', 'types', 'typesWithSubtypes'));
    }

    public function storeImported(Request $request, $year)
    {
        // Delete all budget lines for the given year
        Budget::where('year', $year)->delete();
        $budgets = $request->input('budgets');// Retrieved from the form submission
        foreach ($budgets as $budget) {
            // Save each budget to the database (adjust this to match your database structure)
            Budget::create([
                'code' => $budget["code"],
                'description' => $budget["description"],
                'debit' => $budget["debit"],
                'credit' => $budget["credit"],
                'type_id' => $budget["type_id"],
                'subtype_id' => $budget["subtype_id"],
                'amount' => $budget["amount"],
                'year' => $year,
                'company_id' => session("company_id"),
            ]);
        }

        return redirect()->back();
    }

    public function budgetAnalysis(Request $request)
    {
        $startYear = $request->query('start_year');
        $endYear = $request->query('end_year');
        // Retrieve the company ID from the session
        $companyId = session('company_id');

        // Initialize data
        $revenusData = [];
        $coutsDirectsData = [
            'subtypes' => [],
            'totals' => [],
        ];

        // Fetch total REVENUS for each year
        $revenusTotals = Budget::where('type_id', Type::where('name', 'REVENUS')->value('id'))
            ->where('company_id', $companyId)
            ->whereBetween('year', [$startYear, $endYear])
            ->groupBy('year')
            ->selectRaw('year, SUM(amount) as total')
            ->pluck('total', 'year')
            ->toArray();

        // Prepare REVENUS data
        $revenusLines = Budget::where('type_id', Type::where('name', 'REVENUS')->value('id'))
            ->where('company_id', $companyId)
            ->whereBetween('year', [$startYear, $endYear])
            ->get()
            ->groupBy('code');

        foreach ($revenusLines as $code => $lines) {
            $latestLine = $lines->sortByDesc('year')->first();
            $revenusData[] = [
                'code' => $code,
                'description' => $latestLine->description,
                'amounts' => $lines->pluck('amount', 'year')->toArray(),
            ];
        }

        // Prepare COÛTS DIRECTS data
        $directCostType = Type::where('name', 'COÛTS DIRECTS')->first();

        if ($directCostType) {
            $subtypes = Subtype::where('type_id', $directCostType->id)->get();

            foreach ($subtypes as $subtype) {
                $lines = Budget::where('subtype_id', $subtype->id)
                    ->where('company_id', $companyId)
                    ->whereBetween('year', [$startYear, $endYear])
                    ->get()
                    ->groupBy('code');

                $subtypeLines = [];
                $subtypeTotals = array_fill_keys(range($startYear, $endYear), 0);

                foreach ($lines as $code => $lineGroup) {
                    $latestLine = $lineGroup->sortByDesc('year')->first();
                    $amounts = $lineGroup->pluck('amount', 'year')->toArray();
                    $subtypeLines[] = [
                        'code' => $code,
                        'description' => $latestLine->description,
                        'amounts' => $amounts,
                    ];

                    // Update subtype totals
                    foreach ($amounts as $year => $amount) {
                        $subtypeTotals[$year] += $amount;
                    }
                }

                $coutsDirectsData['subtypes'][$subtype->name] = [
                    'lines' => $subtypeLines,
                    'totals' => $subtypeTotals,
                ];
            }

            // Calculate total COÛTS DIRECTS
            $coutsDirectsTotals = array_fill_keys(range($startYear, $endYear), 0);

            foreach ($coutsDirectsData['subtypes'] as $subtypeData) {
                foreach ($subtypeData['totals'] as $year => $amount) {
                    $coutsDirectsTotals[$year] += $amount;
                }
            }

            $coutsDirectsData['totals'] = $coutsDirectsTotals;
        }

        // Calculate Profit Brut
        $profitBrut = array_fill_keys(range($startYear, $endYear), 0);

        foreach ($profitBrut as $year => $value) {
            $revenus = $revenusTotals[$year] ?? 0;
            $coutsDirects = $coutsDirectsData['totals'][$year] ?? 0;

            $profitBrut[$year] = $revenus - $coutsDirects;
        }

        // Prepare FRAIS D'EXPLOITATION data
        $fraisExploitationType = Type::where('name', 'FRAIS D\'EXPLOITATION')->first();

        $fraisExploitationData = [
            'subtypes' => [],
            'totals' => array_fill_keys(range($startYear, $endYear), 0),
        ];

        if ($fraisExploitationType) {
            $subtypes = Subtype::where('type_id', $fraisExploitationType->id)->get();

            foreach ($subtypes as $subtype) {
                $lines = Budget::where('subtype_id', $subtype->id)
                    ->where('company_id', $companyId)
                    ->whereBetween('year', [$startYear, $endYear])
                    ->get()
                    ->groupBy('code');

                $subtypeLines = [];
                $subtypeTotals = array_fill_keys(range($startYear, $endYear), 0);

                foreach ($lines as $code => $lineGroup) {
                    $latestLine = $lineGroup->sortByDesc('year')->first();
                    $amounts = $lineGroup->pluck('amount', 'year')->toArray();

                    $subtypeLines[] = [
                        'code' => $code,
                        'description' => $latestLine->description,
                        'amounts' => $amounts,
                    ];

                    // Update subtype totals
                    foreach ($amounts as $year => $amount) {
                        $subtypeTotals[$year] += $amount;
                    }
                }

                $fraisExploitationData['subtypes'][$subtype->name] = [
                    'lines' => $subtypeLines,
                    'totals' => $subtypeTotals,
                ];
            }

            // Calculate total FRAIS D'EXPLOITATION
            $fraisExploitationTotals = array_fill_keys(range($startYear, $endYear), 0);

            foreach ($fraisExploitationData['subtypes'] as $subtypeData) {
                foreach ($subtypeData['totals'] as $year => $amount) {
                    $fraisExploitationTotals[$year] += $amount;
                }
            }
            $fraisExploitationData['totals'] = $fraisExploitationTotals;
        }

        // Prepare AUTRES REVENUS ET CHARGES data
        $autresRevenusChargesType = Type::where('name', 'Autres revenus et charges')->first();

        $autresRevenusChargesData = [
            'lines' => [],
            'totals' => array_fill_keys(range($startYear, $endYear), 0),
        ];

        if ($autresRevenusChargesType) {
            $lines = Budget::where('type_id', $autresRevenusChargesType->id)
                ->where('company_id', $companyId)
                ->whereBetween('year', [$startYear, $endYear])
                ->get()
                ->groupBy('code');

            foreach ($lines as $code => $lineGroup) {
                $latestLine = $lineGroup->sortByDesc('year')->first();
                $amounts = $lineGroup->pluck('amount', 'year')->toArray();

                $autresRevenusChargesData['lines'][] = [
                    'code' => $code,
                    'description' => $latestLine->description,
                    'amounts' => $amounts,
                ];

                // Update totals
                foreach ($amounts as $year => $amount) {
                    $autresRevenusChargesData['totals'][$year] += $amount;
                }
            }
        }

        // Calculate Total Profit Net for each year
        $totalProfitNet = [];
        foreach (range($startYear, $endYear) as $year) {
            $totalBeneficeAvantImpot = ($profitBrut[$year] ?? 0) - ($fraisExploitationData['totals'][$year] ?? 0);
            $totalAutresRevenusCharges = $autresRevenusChargesData['totals'][$year] ?? 0;

            $totalProfitNet[$year] = $totalBeneficeAvantImpot + $totalAutresRevenusCharges;
        }

        // Pass data to the view
        return view('user.budgets.analysis', [
            'startYear' => $startYear,
            'endYear' => $endYear,
            'revenusData' => $revenusData,
            'revenusTotals' => $revenusTotals,
            'coutsDirectsData' => $coutsDirectsData,
            'fraisExploitationData' => $fraisExploitationData,
            'profitBrut' => $profitBrut,
            'autresRevenusChargesData' => $autresRevenusChargesData,
            'totalProfitNet' => $totalProfitNet,
        ]);
    }

    public function showByYear($year)
    {
        // Retrieve the company ID from the session
        $companyId = session('company_id');

        // Get all budgets for the selected year
        $budgets = Budget::where('company_id', $companyId)->with('type')->where('year', $year)->get();


        // Group budgets by type, including items without a type
        $groupedBudgets = $budgets->groupBy('type_id');

        return view('user.budgets.show', compact('groupedBudgets', 'year'));
    }

    public function index()
    {
        // Retrieve the company ID from the session
        $companyId = session('company_id');

        // Get distinct years for which budgets exist
        $years = Budget::where('company_id', $companyId)->select('year')->distinct()->get();

        return view('user.budgets.index', compact('years'));
    }

    public function store(Request $request)
    {
        // Retrieve the company ID from the session
        $companyId = session('company_id');

        if ($request->has('bvs')) {
            foreach ($request->bvs as $year => $entries) {
                foreach ($entries as $bv) {
                    Budget::create([
                        'company_id' => $companyId,
                        'year' => $year,
                        'code' => $bv['code'],
                        'type_id' => $bv['type_id'],
                        'subtype_id' => $bv['subtype_id'],
                        'credit' => $bv['credit'],
                        'debit' => $bv['debit'],
                        'amount' => $bv['debit']-$bv['credit'],
                        'description' => $bv['description'],
                    ]);
                }
            }
        }


        return redirect()->route('user.budgets.index', $companyId)->with('success', 'BVs created successfully');
    }

    public function destroy($year)
    {
        // Retrieve the company ID from the session
        $companyId = session('company_id');

        // Delete all budget lines for the given year
        Budget::where('company_id',$companyId)->where('year', $year)->delete();

        // Optionally, you can also include a flash message for user feedback
        return redirect()->route('user.budgets.index')->with('success', 'Budget lines for the year ' . $year . ' were successfully deleted.');
    }

}
