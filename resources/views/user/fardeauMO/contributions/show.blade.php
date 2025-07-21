@extends('user.layouts.app')

@section('title', 'Détails de la Contribution')

@section('content')

<div class="container">
    <h2>📊 Détails de la Contribution pour l'année {{ $contribution->year }}</h2>

    <div class="card">
        <div class="card-header">
            <a href="{{ route('user.fardeauMO.contributions.index') }}" class="btn btn-outline-secondary">
                <i data-feather="arrow-left"></i> Retour
            </a>

            <!-- 🔹 Bouton Modifier CSST uniquement -->
            @if(Auth::user()->company_id === $contribution->company_id)
                <a href="{{ route('user.fardeauMO.contributions.edit', $contribution->id) }}" class="btn btn-outline-primary">
                    <i data-feather="edit"></i> Modifier CSST
                </a>
            @endif
        </div>

        <div class="card-body">

            <!-- 🔹 RRQ -->
            <h4>🔹 RRQ (Régime de rentes du Québec)</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Max Salaire</th>
                        <th>Exemption Base</th>
                        <th>Max Gains Cotisables</th>
                        <th>Exemption à l'Heure</th>
                        <th>Cotisation RRQ à l'Heure</th>
                        <th>Taux Employé (%)</th>
                        <th>Taux Employeur (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $contribution->rrq_max_salary }} $</td>
                        <td>{{ $contribution->rrq_exemption }} $</td>
                        <td>{{ $contribution->rrq_max_gains }} $</td>
                        <td>{{ $contribution->rrq_hourly_exemption }} $</td>
                        <td>{{ $contribution->rrq_hourly_contribution }} $</td>
                        <td>{{ $contribution->rrq_rate_employee }}%</td>
                        <td>{{ $contribution->rrq_rate_employer }}%</td>
                    </tr>
                </tbody>
            </table>

            <!-- 🔹 AE -->
            <h4>🔹 AE (Assurance Emploi)</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Max Salaire</th>
                        <th>Taux Employé (%)</th>
                        <th>Part Employeur</th>
                        <th>Cotisation max Employé</th>
                        <th>Cotisation max Employeur</th>
                        <th>Cotisation à l'Heure</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $contribution->ae_max_salary }} $</td>
                        <td>{{ $contribution->ae_rate_employee }}%</td>
                        <td>{{ $contribution->ae_rate_employer }}</td>
                        <td>{{ $contribution->ae_max_employee }} $</td>
                        <td>{{ $contribution->ae_max_employer }} $</td>
                        <td>{{ $contribution->ae_hourly_contribution }} $</td>
                    </tr>
                </tbody>
            </table>

            <!-- 🔹 RQAP -->
            <h4>🔹 RQAP (Assurance Parentale)</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Max Salaire</th>
                        <th>Taux Employeur (%)</th>
                        <th>Cotisation max</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $contribution->rqap_max_salary }} $</td>
                        <td>{{ $contribution->rqap_rate_employer }}%</td>
                        <td>{{ $contribution->rqap_max_contribution }} $</td>
                    </tr>
                </tbody>
            </table>

            <!-- 🔹 CNT -->
            <h4>🔹 CNT (Cotisation aux Normes du Travail)</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Max Salaire</th>
                        <th>Taux CNT (%)</th>
                        <th>Cotisation max</th>
                        <th>Cotisation CNT à l'Heure</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $contribution->cnt_max_salary }} $</td>
                        <td>{{ $contribution->cnt_rate }}%</td>
                        <td>{{ $contribution->cnt_max_contribution }} $</td>
                        <td>{{ $contribution->cnt_hourly_contribution }} $</td>
                    </tr>
                </tbody>
            </table>

            <!-- 🔹 FSSQ -->
            <h4>🔹 FSSQ (Fonds des Services de Santé du Québec)</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Taux FSSQ (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $contribution->fss_rate }}%</td>
                    </tr>
                </tbody>
            </table>

            <!-- 🔹 CSST (Seule modifiable) -->
            <h4>🔹 CSST (Commission des normes, de l'équité, de la santé et de la sécurité du travail)</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Taux CSST (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $contribution->csst_rate }}%</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>

@endsection
