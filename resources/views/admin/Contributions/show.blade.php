@extends('admin.layouts.app')

@section('title', trans_choice('labels.models.contribution', 1))

@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="col-12">
                <h2 class="content-header-title">{{ __('messages.static.details') }}</h2>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('admin.contributions.index') }}" class="btn btn-outline-secondary">
                                <i data-feather="arrow-left"></i> Retour
                            </a>
                            <a href="{{ route('admin.contributions.edit', $contribution->id) }}" class="btn btn-outline-primary">
                                <i data-feather="edit"></i> Modifier
                            </a>
                        </div>
                        <div class="card-body">
                            <h3>Contribution pour l'année {{ $contribution->year }}</h3>

                            <div class="accordion" id="contributionsAccordion">

                                <!-- 🔹 RRQ -->
                                <div class="card">
                                    <div class="card-header">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#rrqCollapse">
                                            🔹 RRQ (Régime de rentes du Québec)
                                        </button>
                                    </div>
                                    <div id="rrqCollapse" class="collapse show">
                                        <div class="card-body">
                                            <table class="table">
                                                <tr><th>Max Salaire</th><td>{{ $contribution->rrq_max_salary }} $</td></tr>
                                                <tr><th>Exemption Base</th><td>{{ $contribution->rrq_exemption }} $</td></tr>
                                                <tr><th>Max Gains Cotisables</th><td>{{ $contribution->rrq_max_gains }} $</td></tr>
                                                <tr><th>Exemption à l'heure</th><td>{{ $contribution->rrq_hourly_exemption }} $</td></tr>
                                                <tr><th>Cotisation RRQ à l'heure</th><td>{{ $contribution->rrq_hourly_contribution }} $</td></tr>
                                                <tr><th>Taux Employé (%)</th><td>{{ $contribution->rrq_rate_employee }}%</td></tr>
                                                <tr><th>Taux Employeur (%)</th><td>{{ $contribution->rrq_rate_employer }}%</td></tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- 🔹 AE -->
                                <div class="card">
                                    <div class="card-header">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#aeCollapse">
                                            🔹 AE (Assurance Emploi)
                                        </button>
                                    </div>
                                    <div id="aeCollapse" class="collapse">
                                        <div class="card-body">
                                            <table class="table">
                                                <tr><th>Max Salaire</th><td>{{ $contribution->ae_max_salary }} $</td></tr>
                                                <tr><th>Taux Employé (%)</th><td>{{ $contribution->ae_rate_employee }}%</td></tr>
                                                <tr><th>Part Employeur</th><td>{{ $contribution->ae_rate_employer }}</td></tr>
                                                <tr><th>Cotisation max Employé</th><td>{{ $contribution->ae_max_employee }} $</td></tr>
                                                <tr><th>Cotisation max Employeur</th><td>{{ $contribution->ae_max_employer }} $</td></tr>
                                                <tr><th>Cotisation à l'heure</th><td>{{ $contribution->ae_hourly_contribution }} $</td></tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- 🔹 RQAP -->
                                <div class="card">
                                    <div class="card-header">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#rqapCollapse">
                                            🔹 RQAP (Assurance Parentale)
                                        </button>
                                    </div>
                                    <div id="rqapCollapse" class="collapse">
                                        <div class="card-body">
                                            <table class="table">
                                                <tr><th>Salaire Max RQAP</th><td>{{ $contribution->rqap_max_salary }} $</td></tr>
                                                <tr><th>Taux Employeur RQAP (%)</th><td>{{ $contribution->rqap_rate_employer }}%</td></tr>
                                                <tr><th>Cotisation max</th><td>{{ $contribution->rqap_max_contribution }} $</td></tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- 🔹 CNT -->
                                <div class="card">
                                    <div class="card-header">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#cntCollapse">
                                            🔹 CNT (Cotisation aux Normes du Travail)
                                        </button>
                                    </div>
                                    <div id="cntCollapse" class="collapse">
                                        <div class="card-body">
                                            <table class="table">
                                                <tr><th>Salaire Max CNT</th><td>{{ $contribution->cnt_max_salary }} $</td></tr>
                                                <tr><th>Taux CNT (%)</th><td>{{ $contribution->cnt_rate }}%</td></tr>
                                                <tr><th>Cotisation max</th><td>{{ $contribution->cnt_max_contribution }} $</td></tr>
                                                <tr><th>Cotisation CNT à l'heure</th><td>{{ $contribution->cnt_hourly_contribution }} $</td></tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- 🔹 FSSQ -->
                                <div class="card">
                                    <div class="card-header">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#fssqCollapse">
                                            🔹 FSSQ (Fonds des Services de Santé du Québec)
                                        </button>
                                    </div>
                                    <div id="fssqCollapse" class="collapse">
                                        <div class="card-body">
                                            <table class="table">
                                                <tr><th>Taux FSSQ (%)</th><td>{{ $contribution->fss_rate }}%</td></tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- FIN Accordéon -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
