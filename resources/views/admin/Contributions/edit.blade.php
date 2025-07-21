@extends('admin.layouts.app')

@section('title', __('messages.static.edit'))

@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="col-12">
                <h2 class="content-header-title">{{ __('messages.static.edit') }}</h2>
            </div>
        </div>

        <div class="content-body">
            <form method="post" action="{{ route('admin.contributions.update', $contribution->id) }}">
                @csrf 
                @method('PUT')

                <!-- 🔹 Année et Valeur -->
                <div class="form-group">
                    <label>Année</label>
                    <input type="number" class="form-control" name="year" value="{{ $contribution->year }}" required>
                </div>

                

                <!-- 🔹 Onglets Bootstrap -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#rrq">RRQ</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#ae">AE</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#rqap">RQAP</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cnt">CNT</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#fss">FSS</a></li>
                </ul>

                <!-- 🔹 Contenu des onglets -->
                <div class="tab-content mt-3">
                    <!-- 🟢 RRQ -->
                    <div class="tab-pane fade show active" id="rrq">
                        <div class="form-group">
                            <label>Max Salaire RRQ</label>
                            <input type="number"  class="form-control" name="rrq_max_salary" value="{{ $contribution->rrq_max_salary }}">
                        </div>
                        <div class="form-group">
                            <label>Exemption de base RRQ</label>
                            <input type="number" class="form-control" name="rrq_exemption" value="{{ $contribution->rrq_exemption }}">
                        </div>
                        <div class="form-group">
                                            <label>Cotisation maximale RRQ</label>
                                            <input type="number" step="0.001"  class="form-control" name="rrq_max_contribution">
                                        </div>
                                        <div class="form-group">
                                            <label>Taux de cotisation RRQ (%)</label>
                                            <input type="number" step="0.001"  class="form-control" name="taux_de_cotisation_rrq">
                    </div>

                    <!-- 🟢 AE -->
                    <div class="tab-pane fade" id="ae">
                        <div class="form-group">
                            <label>Max Salaire AE</label>
                            <input type="number" class="form-control" name="ae_max_salary" value="{{ $contribution->ae_max_salary }}">
                        </div>
                        <div class="form-group">
                            <label>Taux Employé (%)</label>
                            <input type="number"  step="0.001"  class="form-control" name="ae_rate_employee" value="{{ $contribution->ae_rate_employee }}">
                        </div>
                        <div class="form-group">
                            <label>Part Employeur (x employé)</label>
                            <input type="number"  step="0.001"  class="form-control" name="ae_rate_employer" value="{{ $contribution->ae_rate_employer }}">
                        </div>
                    </div>

                    <!-- 🟢 RQAP -->
                    <div class="tab-pane fade" id="rqap">
                        <div class="form-group">
                            <label>Salaire Max RQAP</label>
                            <input type="number" class="form-control" name="rqap_max_salary" value="{{ $contribution->rqap_max_salary }}">
                        </div>
                        <div class="form-group">
                            <label>Taux Employeur RQAP (%)</label>
                            <input type="number"  step="0.001"  class="form-control" name="rqap_rate_employer" value="{{ $contribution->rqap_rate_employer }}">
                        </div>
                    </div>

                    <!-- 🟢 CNT -->
                    <div class="tab-pane fade" id="cnt">
                        <div class="form-group">
                            <label>Salaire Max CNT</label>
                            <input type="number" class="form-control" name="cnt_max_salary" value="{{ $contribution->cnt_max_salary }}">
                        </div>
                        <div class="form-group">
                            <label>Taux CNT (%)</label>
                            <input type="number"  step="0.001"  class="form-control" name="cnt_rate" value="{{ $contribution->cnt_rate }}">
                        </div>
                    </div>

                    <!-- 🟢 FSS -->
                    <div class="tab-pane fade" id="fss">
                        <div class="form-group">
                            <label>Taux FSS (%)</label>
                            <input type="number" step="0.001"  class="form-control" name="fss_rate" value="{{ $contribution->fss_rate }}">
                        </div>
                    </div>
                </div>

                <!-- 🔹 Boutons -->
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ route('admin.contributions.index') }}" class="btn btn-secondary">Annuler</a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
