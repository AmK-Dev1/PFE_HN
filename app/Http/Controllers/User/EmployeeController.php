<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\Employees;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\EnteteActivite;
use App\Models\OperationType;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employees = Employees::with('operationType')->get();

            return DataTables::of($employees)
                ->addColumn('action', function ($employee) {
                    return '
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-edit" data-id="' . $employee->id . '"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-delete" data-id="' . $employee->id . '"><i class="fas fa-trash"></i></button>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $contribution = Contribution::first();
        if (!$contribution) {
            abort(500, 'La table "contributions" est vide.');
        }

        return view('user.fardeauMO.employees.index', [
            'employees' => Employees::all(),
            'entetes' => EnteteActivite::all(),
            'operationTypes' => OperationType::all(),
            'contribution' => $contribution
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->validateEmployeeData($request);

            $validated += [
                'non_taxable_dividends' => $validated['non_taxable_dividends'] ?? 0,
                'breaks_percent' => $validated['breaks_percent'] ?? null,
                'idle_percent' => $validated['idle_percent'] ?? null,
            ];

            $this->calculateEmployeeCosts($validated);

            $employee = Employees::updateOrCreate(
                ['id' => $request->id ?? null],
                $validated
            );

            return response()->json([
                'success' => true,
                'message' => 'Employé enregistré avec succès',
                'id' => $employee->id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ], 500);
        }
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
        $validated['idle_percent'] = $validated['idle_percent'] ?? ($entete->pourcentage_temps_mort ?? 0);

        $contrib = Contribution::first();
        $rates = $this->initializeContributionRates($contrib);

        $hours = $validated['hours_worked_annual'];
        $rate = $validated['hourly_rate'];

        $annualSalaryBase = $hours * $rate;

        $otherPerHour = ($hours > 0)
            ? ($validated['retirement_fund'] + $validated['bonus'] + $validated['group_insurance'] + $validated['other_benefits']) / $hours
            : 0;

        $paidVacation = $rate * ($validated['vacation_rate'] / 100);
        $paidLeave = $rate * ($validated['paid_leave'] / 100);

        $adjustedRate = $rate + $otherPerHour + $paidVacation + $paidLeave;

        $contributions = $this->calculateContributions($rates, $adjustedRate, $hours);
        $productivity = $this->calculateProductiveTime($validated['breaks_percent'], $validated['idle_percent']);
        $burden = $this->calculateBurden(
            $adjustedRate,
            $contributions,
            $productivity['productive_time'],
            $validated['non_taxable_dividends'],
            $hours
        );

        $seniority = $validated['hire_date']
            ? Carbon::parse($validated['hire_date'])->floatDiffInYears(Carbon::now())
            : 0;

        $validated = array_merge($validated, [
            'annual_salary_base' => $annualSalaryBase,
            'other_benefits_hourly' => $otherPerHour,
            'paid_vacation' => $paidVacation,
            'paid_leave' => $paidLeave,
            'adjusted_hourly_rate' => $adjustedRate,
            'seniority' => $seniority,
        ], $contributions, $productivity, $burden);
    }

    protected function initializeContributionRates($contrib)
    {
        return [
            'rrq' => [
                'employee_rate' => ($contrib->rrq_rate_employee ?? 0) / 100,
                'max_salary' => $contrib->rrq_max_salary ?? 0,
                'exemption' => $contrib->rrq_exemption ?? 0,
            ],
            'ae' => [
                'employer_rate' => ($contrib->ae_rate_employer ?? 0) / 100,
                'max_salary' => $contrib->ae_max_salary ?? 0,
            ],
            'rqap' => [
                'employer_rate' => ($contrib->rqap_rate_employer ?? 0) / 100,
                'max_salary' => $contrib->rqap_max_salary ?? 0,
            ],
            'cnt' => [
                'rate' => ($contrib->cnt_rate ?? 0) / 100,
                'max_salary' => $contrib->cnt_max_salary ?? 0,
            ],
            'fssq' => [
                'rate' => ($contrib->fss_rate ?? 0) / 100,
            ]
        ];
    }

    protected function calculateContributions($rates, $adjustedRate, $hours)
    {
        $baseSalary = $adjustedRate * $hours;

        $rrqValue = 0;
        if ($baseSalary > $rates['rrq']['exemption']) {
            $rrqMaxGains = $rates['rrq']['max_salary'] - $rates['rrq']['exemption'];
            $rrqValue = ($baseSalary >= $rates['rrq']['max_salary'])
                ? ($rrqMaxGains * $rates['rrq']['employee_rate']) / $hours
                : (($baseSalary - $rates['rrq']['exemption']) / $hours) * $rates['rrq']['employee_rate'];
        }

        $aeValue = ($baseSalary >= $rates['ae']['max_salary'])
            ? ($rates['ae']['max_salary'] * $rates['ae']['employer_rate']) / $hours
            : $adjustedRate * $rates['ae']['employer_rate'];

        $rqapValue = ($baseSalary >= $rates['rqap']['max_salary'])
            ? ($rates['rqap']['max_salary'] * $rates['rqap']['employer_rate']) / $hours
            : $adjustedRate * $rates['rqap']['employer_rate'];

        $cntValue = ($baseSalary >= $rates['cnt']['max_salary'])
            ? ($rates['cnt']['max_salary'] * $rates['cnt']['rate']) / $hours
            : $adjustedRate * $rates['cnt']['rate'];

        $fssqValue = $adjustedRate * $rates['fssq']['rate'];

        $totalRate = $adjustedRate + $rrqValue + $aeValue + $rqapValue + $cntValue + $fssqValue;

        return [
            'rrq' => $rrqValue,
            'ae' => $aeValue,
            'rqap' => $rqapValue,
            'cnt' => $cntValue,
            'fssq' => $fssqValue,
            'rate_before_downtime' => $totalRate,
            'total_annual_cost' => $totalRate * $hours
        ];
    }

    protected function calculateProductiveTime($breaksPercent, $idlePercent)
    {
        $pause = ($breaksPercent / 100) * 60;
        $tempsMort = ($idlePercent / 100) * 60;
        $nonProd = $pause + $tempsMort;
        $productive = 60 - $nonProd;

        return [
            'breaks_per_hour' => $pause,
            'idle_time_per_hour' => $tempsMort,
            'total_non_productive_time' => $nonProd,
            'productive_time' => $productive,
            'productive_percent' => ($productive / 60) * 100
        ];
    }

    protected function calculateBurden($adjustedRate, $contributions, $productiveTime, $dividends, $hours)
    {
        $dividendHourly = $hours > 0 ? $dividends / $hours : 0;
        $burdenedRate = ($productiveTime > 0)
            ? ($contributions['rate_before_downtime'] / $productiveTime) * 60 + $dividendHourly
            : 0;

        $basePlusDividends = $adjustedRate + $dividendHourly;

        $burdenPercent = ($basePlusDividends > 0)
            ? (($burdenedRate / $basePlusDividends) - 1) * 100
            : 0;

        return [
            'rate_with_burden' => $burdenedRate,
            'burden_percent' => $burdenPercent
        ];
    }
}
