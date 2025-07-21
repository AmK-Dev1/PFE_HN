<div class="table-responsive">
    <table id="employeeTable" class="table table-borderless text-center align-middle">
        <thead class="thead-light">
            <tr>
                <th>Nom employé</th>
                <th>Poste</th>
                <th>Heures travaillées/an</th>
                <th>Taux horaire de base ($/h)</th>
                <th>Salaire annuel de base ($)</th>
                <th>Fonds retraite ($/an)</th>
                <th>Boni ($/an)</th>
                <th>Assurance groupe ($/an)</th>
                <th>Taux de vacance (%)</th>
                <th>Autres avantages ($/h)</th>
                <th>Vacances payées ($/h)</th>
                <th>Congé payé ($/h)</th>
                <th>Taux horaire corrigé ($/h)</th>
                <th>RRQ ($/h)</th>
                <th>AE ($/h)</th>
                <th>RQAP ($/h)</th>
                <th>CSST ($/h)</th>
                <th>FSSQ ($/h)</th>
                <th>CNT ($/h)</th>
                <th>Taux avant pauses ($/h)</th>
                <th>Coût annuel total ($)</th>
                <th>Pause (min/h)</th>
                <th>Temps mort (min/h)</th>
                <th>Total non productif (min/h)</th>
                <th>Temps productif (min/h)</th>
                <th>% temps productif</th>
                <th>Taux avec fardeau ($/h)</th>
                <th>Fardeau (%)</th>
                <th>Date d'embauche</th>
                <th>Ancienneté (années)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @foreach($employees as $employees)
                @include('user.fardeauMO.partials.employee-row', ['employees' => $employees])
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-light">
                <td colspan="2"><strong>Total</strong></td>
                <td><span id="totalHeuresTravaillees">0</span></td>
                <td><span id="totalTauxHoraireBase">0</span></td>
                <td><span id="totalSalaireAnnuelBase">0</span></td>
                <td><span id="totalFondsRetraite">0</span></td>
                <td><span id="totalBoni">0</span></td>
                <td><span id="totalAssuranceGroupe">0</span></td>
                <td><span id="totalTauxVacance">0</span></td>
                <td><span id="totalAutresAvantages">0</span></td>
                <td><span id="totalVacancesPayees">0</span></td>
                <td><span id="totalCongePaye">0</span></td>
                <td><span id="totalTauxHoraireCorrige">0</span></td>
                <td><span id="totalRRQ">0</span></td>
                <td><span id="totalAE">0</span></td>
                <td><span id="totalRQAP">0</span></td>
                <td><span id="totalCSST">0</span></td>
                <td><span id="totalFSSQ">0</span></td>
                <td><span id="totalCNT">0</span></td>
                <td><span id="totalTauxAvantPauses">0</span></td>
                <td><span id="totalCoutAnnuelTotal">0</span></td>
                <td><span id="totalPauseMinH">0</span></td>
                <td><span id="totalTempsMortMinH">0</span></td>
                <td><span id="totalNonProductif">0</span></td>
                <td><span id="totalTempsProductif">0</span></td>
                <td><span id="totalPourcentageProductif">0</span>%</td>
                <td><span id="totalTauxAvecFardeau">0</span></td>
                <td><span id="totalFardeau">0</span>%</td>
                <td></td>
                <td><span id="totalAnciennete">0</span></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
