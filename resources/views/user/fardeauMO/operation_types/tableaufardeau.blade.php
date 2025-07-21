<div class="container mt-4">
    <!-- Phase 1: En-tête et Paramètres -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" height="50">
        <div>
            <label for="year">Année :</label>
            <input type="number" id="year" value="{{ date('Y') }}" class="form-control d-inline w-auto">
            <label for="update">Mise à jour :</label>
            <input type="text" id="update" value="{{ now()->format('Y-m-d') }}" class="form-control d-inline w-auto" readonly>
        </div>
        <div>
            <label for="holidays">Jours fériés :</label>
            <input type="number" id="holidays" value="8" class="form-control d-inline w-auto">
        </div>
    </div>

    <!-- Phase 2: Tableau Principal -->
    <table class="table table-bordered text-center" id="mainTable">
        <thead>
            <tr>
                <th>Nom Employé</th>
                <th>Poste</th>
                <th>Taux Horaire ($)</th>
                <th>Heures Travaillées</th>
                <th>Sous-total ($)</th>
                <th>Fardeau (%)</th>
                <th>Total Fardeau ($)</th>
                <th>Cotisation (%)</th>
                <th>Montant Cotisation ($)</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->nom }}</td>
                    <td>{{ $employee->poste }}</td>
                    <td>{{ $employee->taux_horaire }}</td>
                    <td>{{ $employee->heures_travail }}</td>
                    <td class="subtotal">{{ $employee->sous_total }}</td>
                    <td>{{ $employee->fardeau }}</td>
                    <td class="totalBurden">{{ $employee->total_fardeau }}</td>
                    <td>{{ $employee->cotisation }}</td>
                    <td class="totalContribution">{{ $employee->montant_cotisation }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Phase 3: Totaux -->
    <div class="mt-4">
        <h4>Résumé</h4>
        <table class="table table-bordered text-center">
            <tr class="total-row">
                <td>Total Heures Travaillées:</td>
                <td>{{ $total_heures }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Sous-total ($):</td>
                <td>{{ $total_sous_total }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Fardeau ($):</td>
                <td>{{ $total_fardeau }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Cotisation ($):</td>
                <td>{{ $total_cotisation }}</td>
            </tr>
        </table>
    </div>
</div>
