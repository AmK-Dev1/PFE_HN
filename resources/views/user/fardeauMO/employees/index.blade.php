@extends('user.layouts.app')

@section('title', 'Fardeau de la Main-d’Œuvre')

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row mb-2">
            <div class="col-12">
                <h2 class="content-header-title">Fardeau de la Main-d’Œuvre</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Fardeau MO</li>
                    </ol>
                </div>
            </div>
        </div>

        {{-- 🟦 Entêtes d'Activité --}}
        @include('user.fardeauMO.partials.entete-table', ['entetes' => $entetes])
       <input type="hidden" name="jour_ferie" value="{{ $entetes->first()->jours_feries ?? 0 }}">
        {{-- 🟨 Employés --}}
        <div class="d-flex justify-content-end mb-2">
            <button id="addRowBtn" type="button" class="btn btn-primary btn-sm">
                + Ajouter une ligne
            </button>
        </div>

        <div class="table-responsive">
            <table id="employeeTable" class="table table-borderless text-center align-middle">
                <thead class="thead-light">
                    <tr>
                      <th>Type operations</th>
                        <th>Nom employé</th>
                        <th>Poste</th>
                        <th>Heures travaillées/an</th>
                        <th>Nombre de semaine travaillées</th>
                        <th>Taux de vacance (%)</th>
                        <th>Taux horaire de base ($/h)</th>
                        <th>Salaire annuel de base ($)</th>    <!-- heur travaillé*taux horaire de base  --> 
                        <th>Fonds retraite ($/an)</th>
                        <th>Boni ($/an)</th>
                        <th>Assurance groupe ($/an)</th>
                        <th>Autres avantages ($/h)</th>   <!-- (Fonds retraite+Boni+Assurance groupe )/Heures travaillées-->
                        <th>Vacances payées ($/h)</th>   <!-- Taux de vacance*Taux horaire de base -->
                        <th>Congé payé ($/h)</th>   <!-- (taux horaire base*jour ferié((heure travaillé*nb semaine/5)/heur travaillé)) -->
                        <th>Taux horaire corrigé ($/h)</th>   <!--Taux Horaire de base+Autres avantages +Vacances payées+Congé payé -->
                        <th>RRQ ($/h)</th>   <!-- RRQ=SI(Nom de l'employé="";"";SI(Taux horaire corrigé=0;"";SI((Taux horaire corrigé*heures travaillée anuelement)<Exemption de base de RRQ :;"";SI((Taux horaire corrigé*heures travaillée anuelement)>Maximum des gains cotisable ;Cotisation maximale RRQ /heures travaillée anuelement;Taux horaire corrigé*heures travaillée anuelement-Exemption de base de RRQ )/heures travaillée anuelement*Taux de cotisation RRQ ))))
 -->
                        <th>AE ($/h)</th>   <!-- AE=SI(Nom de l'employé="";"";SI(Taux horaire corrigé=0;"";SI(Taux horaire corrigé*Taux de l'employé*Part de l'employeur*heures travaillée anuelement>Cotisation maximale AE de l'employeur ;Cotisation maximale AE de l'employeur /heures travaillée anuelement;Taux horaire corrigé*Taux de l'employé*Part de l'employeur)) -->
                        <th>RQAP ($/h)</th><!--RQAP=SI(Nom de l'employé="";"";SI(Taux horaire corrigé=0;"";SI((Taux horaire corrigé*heures travaillée anuelement)>Salaire Max assurable RQAP;Cotisation maximale au RQAP/heures travaillée anuelement;(Taux horaire corrigé*heures travaillée anuelement)/heures travaillée anuelement*Taux Employeur RQAP(%))))-->
                        <th>CSST ($/h)</th><!--CSST=SI(Nom de l'employé="";"";SI(Taux horaire corrigé=0;"";Taux horaire corrigé*Taux CSST (%)))-->
                        <th>FSSQ ($/h)</th><!--FSSQ=SI(Nom de l'employé="";"";SI(Taux horaire corrigé=0;"";Taux horaire corrigé*Taux FSSQ (%)))-->
                        <th>CNT ($/h)</th><!--CNT=SI(Nom de l'employé="";"";SI(Taux horaire corrigé=0;"";SI((Taux horaire corrigé*heures travaillée anuelement)>Cotisation maximale au CNT ;Cotisation maximale au CNT /heures travaillée anuelement;(Taux horaire corrigé*Taux CNT (%)))))--> 
                        <th>Taux avant pauses ($/h)</th><!--Taux horaire corrigé+RRQ+AE+RQAP+CSST+FSSQ+CNT-->
                        <th>Coût annuel total ($)</th><!--Coût Annuel Total=SI(Nom de l'employé="";"";SI(Taux avant pauses, congés et temps mort="";"";(Taux avant pauses, congés et temps mort)*heures travaillée anuelement))-->
                        <th>Pause (min/h)</th><!--Pauses en minutes/heure=pourcentage_pause*60-->
                        <th>Temps mort (min/h)</th><!--Temps mort en minutes/heure=pourcentage_temps_mort*60-->
                        <th>Total non productif (min/h)</th><!--Total minutes non productives=Pauses en minutes+Temps mort en minutes-->
                        <th>Temps productif (min/h)</th><!--Temps productif par heure=60-Total minutes non productives-->
                        <th>% temps productif</th><!--% de temps productif=Temps productif par heure/60-->
                        <th>Taux avec fardeau ($/h)</th><!--Taux Avec Fardeau=SINom de l'employé ="";"";SI(Taux horaire corrigé="";"";(Taux avant pauses, congés et temps mort/Temps productif par heure*60))-->
                        <th>Fardeau (%)</th>
                        <th>Date d'embauche</th>
                        <th>Ancienneté (années)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @foreach($employees as $employee)
                        @include('user.fardeauMO.partials.employee-row', ['employee' => $employee])
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Template pour ajout dynamique --}}
        <template id="employeeRowTemplate">
    <tr>
      <td>
            <select name="operation_type_id" class="form-control form-control-sm">
                <option value="">-- Choisir --</option>
                @foreach($operationTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </td>

      
        <td><input type="text" class="form-control form-control-sm" name="employee_name"></td>
        <td><input type="text" class="form-control form-control-sm" name="position"></td>
        
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="hours_worked_annual" onchange="calculateRow(this)"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="hourly_rate" onchange="calculateRow(this)"></td>
        <td><input type="number" class="form-control form-control-sm" name="base_salary" readonly></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="retirement_fund" onchange="calculateRow(this)"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="bonus" onchange="calculateRow(this)"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="group_insurance" onchange="calculateRow(this)"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="vacation_rate" onchange="calculateRow(this)"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="other_benefits" onchange="calculateRow(this)"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="paid_vacation" onchange="calculateRow(this)"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="paid_leave" onchange="calculateRow(this)"></td>
        <td><input type="number" class="form-control form-control-sm" name="corrected_hourly_rate" readonly></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="rrq" onchange="calculateRow(this)"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="ae" onchange="calculateRow(this)"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="rqap" onchange="calculateRow(this)"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="csst" onchange="calculateRow(this)"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="fssq" onchange="calculateRow(this)"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="cnt" onchange="calculateRow(this)"></td>
        <td><input type="number" class="form-control form-control-sm" name="rate_before_breaks" readonly></td>
        <td><input type="number" class="form-control form-control-sm" name="total_annual_cost" readonly></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="break_time" onchange="calculateRow(this)"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="dead_time" onchange="calculateRow(this)"></td>
        <td><input type="number" class="form-control form-control-sm" name="total_non_productive" readonly></td>
        <td><input type="number" class="form-control form-control-sm" name="productive_time" readonly></td>
        <td><input type="number" class="form-control form-control-sm" name="productive_percentage" readonly></td>
        <td><input type="number" class="form-control form-control-sm" name="burdened_rate" readonly></td>
        <td><input type="number" class="form-control form-control-sm" name="burden_percentage" readonly></td>
        <td><input type="date" class="form-control form-control-sm" name="hire_date" onchange="calculateRow(this)"></td>
        <td><input type="number" class="form-control form-control-sm" name="seniority" readonly></td>
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
</template>
    </div>
</div>
@endsection




@push('scripts')
<script>
window.contributionRates = {
    rrq: {
        rate: {{ $contribution->rrq_rate_employee / 100 }},
        exemption: {{ $contribution->rrq_exemption }},
        max: {{ $contribution->rrq_max_salary }}
    },
    ae: {
        rate: {{ $contribution->ae_rate_employer / 100 }},
        max: {{ $contribution->ae_max_salary }}
    },
    rqap: {
        rate: {{ $contribution->rqap_rate_employer / 100 }},
        max: {{ $contribution->rqap_max_salary }}
    },
    csst: {
        rate: {{ $contribution->csst_rate / 100 }}
    },
    fssq: {
        rate: {{ $contribution->fss_rate / 100 }}
    },
    cnt: {
        rate: {{ $contribution->cnt_rate / 100 }},
        max: {{ $contribution->cnt_max_salary }}
    }
};
</script>

<script>
  document.getElementById('addRowBtn').addEventListener('click', () => {
  const tbody = document.querySelector('#employeeTable tbody');
  const template = document.getElementById('employeeRowTemplate').content;
  const clone = document.importNode(template, true);
  tbody.appendChild(clone);

  // Ajouter les listeners sur la nouvelle ligne
  const row = tbody.lastElementChild;
  row.querySelectorAll('input, select').forEach(input => {
    input.addEventListener('input', () => calculateRow(row));
  });
  // Et initialiser les calculs
  calculateRow(row);
});
window.addEventListener('DOMContentLoaded', () => {
    const entete = {
        jours_feries: parseFloat(document.querySelector('[name="jours_feries"]')?.value || 0),
        pourcentage_pause: parseFloat(document.querySelector('[name="pourcentage_pause"]')?.value || 0),
        pourcentage_temps_mort: parseFloat(document.querySelector('[name="pourcentage_temps_mort"]')?.value || 0),
    };
    const contributions = window.contributionRates;

    // Initialise calculs et écouteurs
    const rows = document.querySelectorAll('#employeeTable tbody tr');
    rows.forEach(row => {
        row.querySelectorAll('input, select').forEach(input =>
            input.addEventListener('input', () => calculateRow(row))
        );
        calculateRow(row);
    });

    function calculateRow(row) {
        const get = name => parseFloat(row.querySelector(`[name="${name}"]`)?.value || 0);
        const set = (name, val) => {
            const el = row.querySelector(`[name="${name}"]`);
            if (el) el.value = (+val).toFixed(2);
        };

        const hours = get('hours_worked_annual');
        const weeks = get('weeks_worked') || 52;
        const baseRate = get('hourly_rate');
        const retirement = get('retirement_fund');
        const bonus = get('bonus');
        const insurance = get('group_insurance');
        const vacationRate = get('vacation_rate');
        const dividends = get('non_taxable_dividends');

        const annualSalary = hours * baseRate;
        const otherHourly = hours ? (retirement + bonus + insurance) / hours : 0;
        const paidVacation = baseRate * (vacationRate / 100);
        const paidLeave = hours
            ? baseRate * entete.jours_feries * ((hours / weeks / 5) / hours)
            : 0;

        const adjustedRate = baseRate + otherHourly + paidVacation + paidLeave;
        const baseForCot = adjustedRate * hours;

        // Cotisations
        const rrq = baseForCot > contributions.rrq.exemption
            ? (Math.min(baseForCot, contributions.rrq.max) - contributions.rrq.exemption) * contributions.rrq.rate / hours
            : 0;
        const ae = baseForCot > contributions.ae.max
            ? contributions.ae.max * contributions.ae.rate / hours
            : adjustedRate * contributions.ae.rate;
        const rqap = baseForCot > contributions.rqap.max
            ? contributions.rqap.max * contributions.rqap.rate / hours
            : adjustedRate * contributions.rqap.rate;
        const csst = adjustedRate * contributions.csst.rate;
        const fssq = adjustedRate * contributions.fssq.rate;
        const cnt = baseForCot > contributions.cnt.max
            ? contributions.cnt.max * contributions.cnt.rate / hours
            : adjustedRate * contributions.cnt.rate;

        const totalContrib = rrq + ae + rqap + csst + fssq + cnt;
        const rateBefore = adjustedRate + totalContrib;
        const totalCost = rateBefore * hours;

        const pauseMin = entete.pourcentage_pause * 60;
        const deadMin = entete.pourcentage_temps_mort * 60;
        const totalNonProd = pauseMin + deadMin;
        const prodTime = 60 - totalNonProd;
        const prodPct = prodTime / 60 * 100;

        const dividendHourly = hours ? dividends / hours : 0;
        const rateWithBurden = prodTime > 0
            ? (rateBefore / prodTime) * 60 + dividendHourly
            : 0;
        const basePlusDiv = adjustedRate + dividendHourly;
        const burdenPct = basePlusDiv > 0
            ? (rateWithBurden / basePlusDiv - 1) * 100
            : 0;

        const hireDateStr = row.querySelector('[name="hire_date"]')?.value;
        const seniority = hireDateStr
            ? new Date().getFullYear() - new Date(hireDateStr).getFullYear() -
              (new Date() < new Date(new Date().getFullYear(), new Date(hireDateStr).getMonth(), new Date(hireDateStr).getDate()) ? 1 : 0)
            : 0;

        // Mise à jour
        set('annual_salary_base', annualSalary);
        set('other_benefits_hourly', otherHourly);
        set('paid_vacation', paidVacation);
        set('paid_leave', paidLeave);
        set('adjusted_hourly_rate', adjustedRate);
        set('rrq', rrq);
        set('ae', ae);
        set('rqap', rqap);
        set('csst', csst);
        set('fssq', fssq);
        set('cnt', cnt);
        set('rate_before_downtime', rateBefore);
        set('total_annual_cost', totalCost);
        set('breaks_per_hour', pauseMin);
        set('idle_time_per_hour', deadMin);
        set('total_non_productive_time', totalNonProd);
        set('productive_time', prodTime);
        set('productive_time_percentage', prodPct);
        set('rate_with_burden', rateWithBurden);
        set('burden_percentage', burdenPct);
        set('seniority', seniority);

       
    }

    async function saveRow(row) {
        const data = {};
        row.querySelectorAll('input, select').forEach(el => {
            if (el.name) data[el.name] = el.value;
        });
        data.id = row.dataset.id || null;

        try {
            const res = await fetch('{{ route("user.fardeauMO.employees.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });
            const json = await res.json();
            if (res.ok && json.id) {
                row.dataset.id = json.id;
                alert('Enregistré!');
            } else {
                console.warn('Erreur enregistrement', json.message);
            }
        } catch (e) {
            console.error('Erreur AJAX', e);
        }
    }

    document.addEventListener('click', async e => {
        if (e.target.closest('.btn-save-employee')) {
            await saveRow(e.target.closest('tr'));
        }

        if (e.target.closest('.btn-delete-employee')) {
            const row = e.target.closest('tr');
            const id = row.dataset.id;
            if (!id) {
                row.remove();
                return;
            }
            const res = await fetch(`/user/fardeauMO/employees/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            if (res.ok) {
                row.remove();
                alert('Supprimé');
            } else {
                alert('Erreur suppression');
            }
        }
    });
});
</script>
@endpush



