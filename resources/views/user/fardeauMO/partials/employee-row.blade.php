@if(isset($employee))
<tr data-id="{{ $employee->id }}">
    <td>
            <select name="operation_type_id" class="form-control form-control-sm">
                <option value="">-- Choisir --</option>
                @foreach($operationTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </td>

    <td><input type="text" class="form-control form-control-sm" name="employee_name" value="{{ $employee->employee_name }}"></td>
    <td><input type="text" class="form-control form-control-sm" name="position" value="{{ $employee->position }}"></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="heures_travaillees" value="{{ $employee->heures_travaillees }}" onchange="calculateEmployeeRow(this)"></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="taux_horaire_base" value="{{ $employee->taux_horaire_base }}" onchange="calculateEmployeeRow(this)"></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="salaire_annuel_base" value="{{ $employee->salaire_annuel_base }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="fonds_retraite" value="{{ $employee->fonds_retraite }}" onchange="calculateEmployeeRow(this)"></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="boni" value="{{ $employee->boni }}" onchange="calculateEmployeeRow(this)"></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="assurance_groupe" value="{{ $employee->assurance_groupe }}" onchange="calculateEmployeeRow(this)"></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="taux_vacance" value="{{ $employee->taux_vacance }}" onchange="calculateEmployeeRow(this)"></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="autres_avantages" value="{{ $employee->autres_avantages }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="vacances_payees" value="{{ $employee->vacances_payees }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="conge_paye" value="{{ $employee->conge_paye }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="taux_horaire_corrige" value="{{ $employee->taux_horaire_corrige }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="rrq" value="{{ $employee->rrq }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="ae" value="{{ $employee->ae }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="rqap" value="{{ $employee->rqap }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="csst" value="{{ $employee->csst }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="fssq" value="{{ $employee->fssq }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="cnt" value="{{ $employee->cnt }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="taux_avant_pauses" value="{{ $employee->taux_avant_pauses }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="cout_annuel_total" value="{{ $employee->cout_annuel_total }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="pause_min_h" value="{{ $employee->pause_min_h }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="temps_mort_min_h" value="{{ $employee->temps_mort_min_h }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="total_non_productif" value="{{ $employee->total_non_productif }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="temps_productif" value="{{ $employee->temps_productif }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="pourcentage_temps_productif" value="{{ $employee->pourcentage_temps_productif }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="taux_avec_fardeau" value="{{ $employee->taux_avec_fardeau }}" readonly></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="fardeau" value="{{ $employee->fardeau }}" readonly></td>
    <td><input type="date" class="form-control form-control-sm" name="date_embauche" value="{{ $employee->date_embauche }}" onchange="calculateEmployeeRow(this)"></td>
    <td><input type="number" step="0.01" class="form-control form-control-sm" name="anciennete" value="{{ $employee->anciennete }}" readonly></td>
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
</tr>
@endif 