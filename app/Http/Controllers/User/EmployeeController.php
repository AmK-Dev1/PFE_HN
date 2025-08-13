<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\RecapitulatifActiviteController;
use App\Models\Contribution;
use App\Models\Employees;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\EnteteActivite;
use App\Models\OperationType;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $operationTypeId = $request->query('type');

        if (!$operationTypeId) {
            return redirect()->back()->with('error', 'Paramètre "type" manquant dans l’URL.');
        }

        if ($request->ajax()) {
            $employees = Employees::with('operationType')
                ->where('operation_type_id', $operationTypeId)
                ->get();

            return DataTables::of($employees)
                ->addColumn('action', function ($employee) {
                    // ✅ Seulement supprimer (plus d’édition)
                    return '
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-delete" data-id="' . $employee->id . '">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $constants = Contribution::whereNull('company_id')->orderByDesc('year')->first();
        if (!$constants) {
            abort(500, 'Les contributions globales ne sont pas définies.');
        }

        $company = auth()->user()->companies()->first();
        $csstContribution = null;

        if ($company) {
            $csstContribution = Contribution::where('company_id', $company->id)
                                            ->whereNotNull('csst_rate')
                                            ->orderByDesc('year')
                                            ->first();
        }

        return view('user.fardeauMO.employees.index', [
            'employees'        => Employees::where('operation_type_id', $operationTypeId)->get(),
            'entetes'          => EnteteActivite::where('operation_type_id', $operationTypeId)->get(),
            'operationTypes'   => OperationType::all(),
            'constants'        => $constants,
            'csstContribution' => $csstContribution,
            'operationTypeId'  => $operationTypeId,
            'users'            => User::select('id','name','email')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->validateEmployeeData($request);

            // (facultatif) liaison logique à un user, mais ta table n’a pas user_id ⇒ ne PAS persister
            if (!empty($validated['employee_name'])) {
                $text = trim($validated['employee_name']);
                $user = User::where('name', $text)->orWhere('email', $text)->first();
                // $validated['user_id'] = $user?->id; // ❌ ta table ne possède pas cette colonne
            }

            $validated += [
                'non_taxable_dividends' => $validated['non_taxable_dividends'] ?? 0,
                'breaks_percent'        => $validated['breaks_percent'] ?? null,
                'idle_percent'          => $validated['idle_percent'] ?? null,
            ];

            $this->calculateEmployeeCosts($validated);

            if (!$request->filled('operation_type_id')) {
                $validated['operation_type_id'] = (int) $request->query('type');
            }

            // Nettoyage des champs non présents en DB
            unset($validated['user_id'], $validated['breaks_percent'], $validated['idle_percent'], $validated['id'], $validated['annual_salary']);

            $employee = Employees::updateOrCreate(
                ['id' => $request->id ?? null],
                $validated
            );

            $recapResponse = app(RecapitulatifActiviteController::class)
                ->recompute(new Request(['type' => $validated['operation_type_id']]));
            $recap = json_decode($recapResponse->getContent(), true);

            return response()->json([
                'success' => true,
                'message' => 'Employé enregistré avec succès',
                'id'      => $employee->id,
                'recap'   => $recap['recap'] ?? ($recap['data'] ?? null),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false,'message' => 'Erreur de validation','errors'  => $e->errors()], 422);
        } catch (\Throwable $e) {
            return response()->json(['success' => false,'message' => 'Erreur serveur : ' . $e->getMessage()], 500);
        }
    }


    /**
     * ✅ Enregistrement en masse de toutes les lignes du tableau
     * Payload attendu:
     * {
     *   operation_type_id: number,
     *   rows: [
     *     { id?, employee_name, position?, hours_worked_annual, ... }
     *   ]
     * }
     */
    public function bulkSave(Request $request)
    {
        $data = $request->validate([
            'operation_type_id' => 'required|exists:operation_types,id',
            'rows' => 'required|array',
            'rows.*.id' => 'nullable|integer',
            'rows.*.employee_name' => 'required|string|max:255',
            'rows.*.position' => 'nullable|string|max:255',
            'rows.*.hours_worked_annual' => 'required|numeric|min:0',
            'rows.*.weeks_worked' => 'nullable|numeric|min:0',
            'rows.*.vacation_rate' => 'nullable|numeric|min:0',
            'rows.*.hourly_rate' => 'required|numeric|min:0',
            'rows.*.retirement_fund' => 'nullable|numeric|min:0',
            'rows.*.bonus' => 'nullable|numeric|min:0',
            'rows.*.group_insurance' => 'nullable|numeric|min:0',
            'rows.*.other_benefits' => 'nullable|numeric|min:0',
            'rows.*.paid_vacation' => 'nullable|numeric|min:0',
            'rows.*.paid_leave' => 'nullable|numeric|min:0',
            'rows.*.non_taxable_dividends' => 'nullable|numeric|min:0',
            'rows.*.breaks_percent' => 'nullable|numeric|min:0',
            'rows.*.idle_percent' => 'nullable|numeric|min:0',
            'rows.*.hire_date' => 'nullable|date',
        ]);

        $operationTypeId = (int) $data['operation_type_id'];
        $rows = $data['rows'];

        DB::transaction(function () use ($rows, $operationTypeId) {
            foreach ($rows as $row) {
                $row['operation_type_id'] = $operationTypeId;

                // (facultatif) lookup User, mais NE PAS persister user_id (colonne absente)
                // if (!empty($row['employee_name'])) {
                //     $text = trim($row['employee_name']);
                //     $user = User::where('name', $text)->orWhere('email', $text)->first();
                //     $row['user_id'] = $user?->id; // ❌ colonne absente
                // }

                $row += [
                    'non_taxable_dividends' => $row['non_taxable_dividends'] ?? 0,
                    'breaks_percent'        => $row['breaks_percent'] ?? null,
                    'idle_percent'          => $row['idle_percent'] ?? null,
                ];

                $this->calculateEmployeeCosts($row);

                // Nettoyage avant persistance
                unset($row['user_id'], $row['breaks_percent'], $row['idle_percent'], $row['annual_salary']);

                if (!empty($row['id'])) {
                    $id = (int) $row['id'];
                    unset($row['id']); // ⚠️ ne pas SET id=...
                    Employees::where('id', $id)->update($row);
                } else {
                    unset($row['id']);
                    Employees::create($row);
                }
            }
        });

        $recapResponse = app(RecapitulatifActiviteController::class)
            ->recompute(new Request(['type' => $operationTypeId]));
        $recap = json_decode($recapResponse->getContent(), true);

        return response()->json([
            'success' => true,
            'message' => 'Toutes les lignes ont été enregistrées',
            'recap'   => $recap['recap'] ?? ($recap['data'] ?? null),
        ]);
    }


    public function destroy($id)
    {
        Employees::findOrFail($id)->delete();
        return response()->json(['success' => 'Employé supprimé avec succès']);
    }

    protected function validateEmployeeData(Request $request)
    {
        return $request->validate([
            'employee_name'         => 'required|string|max:255',
            'position'              => 'nullable|string|max:255',
            'operation_type_id'     => 'required|exists:operation_types,id',
            'hours_worked_annual'   => 'required|numeric|min:0',
            'weeks_worked'          => 'nullable|numeric|min:0',
            'vacation_rate'         => 'nullable|numeric|min:0',
            'hourly_rate'           => 'required|numeric|min:0',
            'retirement_fund'       => 'nullable|numeric|min:0',
            'bonus'                 => 'nullable|numeric|min:0',
            'group_insurance'       => 'nullable|numeric|min:0',
            'other_benefits'        => 'nullable|numeric|min:0',
            'paid_vacation'         => 'nullable|numeric|min:0',
            'paid_leave'            => 'nullable|numeric|min:0',
            'non_taxable_dividends' => 'nullable|numeric|min:0',
            'breaks_percent'        => 'nullable|numeric|min:0',
            'idle_percent'          => 'nullable|numeric|min:0',
            'hire_date'             => 'nullable|date',
        ]);
    }

    protected function calculateEmployeeCosts(&$validated)
    {
        $entete = EnteteActivite::latest()->first();
        $validated['breaks_percent'] = $validated['breaks_percent'] ?? ($entete->pourcentage_pause ?? 0);
        $validated['idle_percent']   = $validated['idle_percent']   ?? ($entete->pourcentage_temps_mort ?? 0);

        $contrib = Contribution::first();
        $rates   = $this->initializeContributionRates($contrib);

        $hours = (float) ($validated['hours_worked_annual'] ?? 0);
        $rate  = (float) ($validated['hourly_rate'] ?? 0);

        // ---- salaire annuel base (dans ta DB: annual_salary_base)
        $annualSalaryBase = $hours * $rate;
        $validated['annual_salary_base'] = $annualSalaryBase;
        unset($validated['annual_salary']); // par sécurité

        // ---- autres avantages ($/h)
        $otherPerHour = ($hours > 0)
            ? (($validated['retirement_fund'] ?? 0)
            + ($validated['bonus'] ?? 0)
            + ($validated['group_insurance'] ?? 0)
            + ($validated['other_benefits'] ?? 0)) / $hours
            : 0;

        // ---- vacances ($/h) et congés payés ($/h)
        $paidVacation = $rate * (($validated['vacation_rate'] ?? 0) / 100);
        // le front t’envoie déjà paid_leave en $/h ⇒ ne pas le re-multiplier
        $paidLeave    = (float) ($validated['paid_leave'] ?? 0);

        $adjustedRate = $rate + $otherPerHour + $paidVacation + $paidLeave;

        $contributions = $this->calculateContributions($rates, $adjustedRate, $hours);
        $productivity  = $this->calculateProductiveTime(($validated['breaks_percent'] ?? 0), ($validated['idle_percent'] ?? 0));
        $burden        = $this->calculateBurden(
            $adjustedRate,
            $contributions,
            $productivity['productive_time'],
            ($validated['non_taxable_dividends'] ?? 0),
            $hours
        );

        $seniority = !empty($validated['hire_date'])
            ? Carbon::parse($validated['hire_date'])->floatDiffInYears(Carbon::now())
            : 0;

        $validated = array_merge($validated, [
            'other_benefits_hourly'      => $otherPerHour,
            'paid_vacation'              => $paidVacation,
            'paid_leave'                 => $paidLeave,
            'adjusted_hourly_rate'       => $adjustedRate,
            'seniority'                  => $seniority,
        ], $contributions, $productivity, $burden);

        // ⚠️ ces 3 champs ne sont PAS dans la DB — on les garde seulement pour le calcul,
        // mais on ne doit PAS les persister
        // (on les enlèvera juste avant create/update)
    }


    protected function initializeContributionRates($contrib)
    {
        return [
            'rrq' => [
                'employee_rate' => (($contrib->rrq_rate_employee ?? 0) / 100),
                'max_salary'    => ($contrib->rrq_max_salary ?? 0),
                'exemption'     => ($contrib->rrq_exemption ?? 0),
            ],
            'ae' => [
                'employer_rate' => (($contrib->ae_rate_employer ?? 0) / 100),
                'max_salary'    => ($contrib->ae_max_salary ?? 0),
            ],
            'rqap' => [
                'employer_rate' => (($contrib->rqap_rate_employer ?? 0) / 100),
                'max_salary'    => ($contrib->rqap_max_salary ?? 0),
            ],
            'cnt' => [
                'rate'       => (($contrib->cnt_rate ?? 0) / 100),
                'max_salary' => ($contrib->cnt_max_salary ?? 0),
            ],
            'fssq' => [
                'rate' => (($contrib->fss_rate ?? 0) / 100),
            ],
        ];
    }

    /**
     * @param array $rates           // barèmes Contribution
     * @param float $adjustedRate    // $/h (taux horaire ajusté)
     * @param float $hours           // heures annuelles
     * @return array
     */
    protected function calculateContributions($rates, $adjustedRate, $hours)
    {
        $baseSalary = $adjustedRate * $hours; // $ total

        // RRQ (ramené en $/h)
        $rrqValue = 0;
        if ($baseSalary > $rates['rrq']['exemption']) {
            $rrqMaxGains = $rates['rrq']['max_salary'] - $rates['rrq']['exemption'];
            $rrqValue = ($baseSalary >= $rates['rrq']['max_salary'])
                ? ($rrqMaxGains * $rates['rrq']['employee_rate']) / $hours
                : (($baseSalary - $rates['rrq']['exemption']) / $hours) * $rates['rrq']['employee_rate'];
        }

        // AE ($/h)
        $aeValue = ($baseSalary >= $rates['ae']['max_salary'])
            ? ($rates['ae']['max_salary'] * $rates['ae']['employer_rate']) / $hours
            : $adjustedRate * $rates['ae']['employer_rate'];

        // RQAP ($/h)
        $rqapValue = ($baseSalary >= $rates['rqap']['max_salary'])
            ? ($rates['rqap']['max_salary'] * $rates['rqap']['employer_rate']) / $hours
            : $adjustedRate * $rates['rqap']['employer_rate'];

        // CNT ($/h)
        $cntValue = ($baseSalary >= $rates['cnt']['max_salary'])
            ? ($rates['cnt']['max_salary'] * $rates['cnt']['rate']) / $hours
            : $adjustedRate * $rates['cnt']['rate'];

        // FSSQ ($/h)
        $fssqValue = $adjustedRate * $rates['fssq']['rate'];

        // Taux avant temps morts ( $/h )
        $rateBeforeDowntime = $adjustedRate + $rrqValue + $aeValue + $rqapValue + $cntValue + $fssqValue;

        return [
            'rrq'                  => $rrqValue,
            'ae'                   => $aeValue,
            'rqap'                 => $rqapValue,
            'cnt'                  => $cntValue,
            'fssq'                 => $fssqValue,
            'rate_before_downtime' => $rateBeforeDowntime,
            'total_annual_cost'    => $rateBeforeDowntime * $hours, // $ total annuel
        ];
    }

    /**
     * Calcule les minutes productives et le pourcentage productif
     */
    protected function calculateProductiveTime($breaksPercent, $idlePercent)
    {
        $pause      = ((float)$breaksPercent / 100) * 60; // min/h
        $tempsMort  = ((float)$idlePercent / 100) * 60;   // min/h
        $nonProd    = $pause + $tempsMort;                // min/h
        $productive = 60 - $nonProd;                      // min/h
        $productivePct = ($productive > 0) ? ($productive / 60) * 100 : 0;

        return [
            'breaks_per_hour'            => $pause,
            'idle_time_per_hour'         => $tempsMort,
            'total_non_productive_time'  => $nonProd,
            'productive_time'            => $productive,
            'productive_time_percentage' => $productivePct, // ✅ cohérent avec ton modèle
        ];
    }

    /**
     * Calcule le taux avec fardeau et le pourcentage de fardeau
     */
    protected function calculateBurden($adjustedRate, $contributions, $productiveTime, $dividends, $hours)
    {
        $dividendHourly = $hours > 0 ? ((float)$dividends / $hours) : 0;

        // $/h avec fardeau (ramené sur 60 min productives)
        $burdenedRate = ($productiveTime > 0)
            ? ($contributions['rate_before_downtime'] / $productiveTime) * 60 + $dividendHourly
            : 0;

        $basePlusDividends = $adjustedRate + $dividendHourly;

        $burdenPercent = ($basePlusDividends > 0)
            ? (($burdenedRate / $basePlusDividends) - 1) * 100
            : 0;

        return [
            'rate_with_burden'  => $burdenedRate,
            'burden_percentage' => $burdenPercent, // ✅ cohérent avec ton modèle
        ];
    }
}
