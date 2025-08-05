@if(isset($employee))
<tr data-id="{{ $employee->id }}">
<td><input type="text" class="form-control form-control-sm" name="employee_name"></td>
<td><input type="text" class="form-control form-control-sm" name="position"></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="hours_worked_annual"></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="weeks_worked"></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="vacation_rate"></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="hourly_rate"></td>

<!-- 🔹 Calculé automatiquement -->
<td><input type="number" step="0.01" class="form-control form-control-sm" name="annual_salary" readonly></td>

<td><input type="number" step="0.01" class="form-control form-control-sm" name="retirement_fund"></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="bonus"></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="group_insurance"></td>

<!-- 🔹 Calculé automatiquement -->
<td><input type="number" step="0.01" class="form-control form-control-sm" name="other_benefits_hourly" readonly></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="paid_vacation" readonly></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="paid_leave" readonly></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="adjusted_hourly_rate" readonly></td>

<!-- 🔹 Cotisations calculées -->
<td><input type="number" step="0.01" class="form-control form-control-sm" name="rrq" readonly></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="ae" readonly></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="rqap" readonly></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="csst" readonly></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="fssq" readonly></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="cnt" readonly></td>

<td><input type="number" step="0.01" class="form-control form-control-sm" name="other_benefits"></td>

<!-- 🔹 Calculs automatiques -->
<td><input type="number" step="0.01" class="form-control form-control-sm" name="rate_before_downtime" readonly></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="total_annual_cost" readonly></td>

<td><input type="number" step="0.01" class="form-control form-control-sm" name="non_taxable_dividends"></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="breaks_per_hour"readonly ></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="idle_time_per_hour" readonly></td>

<!-- 🔹 Calculs automatiques -->
<td><input type="number" step="0.01" class="form-control form-control-sm" name="total_non_productive_time" readonly></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="productive_time" readonly></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="productive_time_percentage" readonly></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="rate_with_burden" readonly></td>
<td><input type="number" step="0.01" class="form-control form-control-sm" name="burden_percentage" readonly></td>

<td><input type="date" class="form-control form-control-sm" name="hire_date"></td>

<!-- 🔹 Calculé -->
<td><input type="number" step="0.01" class="form-control form-control-sm" name="seniority" readonly></td>

<td>
    <div class="d-flex justify-content-center gap-1">
        <button class="btn btn-success btn-sm btn-save-employee" title="Enregistrer">
            <i class="fas fa-save"></i>
        </button>
        <button class="btn btn-danger btn-sm btn-delete-employee" title="Supprimer">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</td>

@endif 