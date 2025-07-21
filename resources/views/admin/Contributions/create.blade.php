@extends('admin.layouts.app')

@section('title', __('messages.static.create'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/app-assets/vendors/css/forms/select/select2.min.css') }}">
@endpush

@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="col-12">
                <h2 class="content-header-title">{{ __('messages.static.create') }}</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">{{ __('labels.fields.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ __('messages.static.create') }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row" id="table-head">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button title="{{ __('messages.static.back') }}" onclick="window.location.href='{{ route('admin.contributions.index') }}'" class="btn btn-outline-info">
                                <i data-feather="arrow-left"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('admin.contributions.store') }}">
                                @csrf

                                <!-- 🔹 Sélection de l’année et valeur -->
                                <div class="form-group">
                                    <label>Année</label>
                                    <input type="number" class="form-control" name="year" required>
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
                                            <input type="number" class="form-control" name="rrq_max_salary">
                                        </div>
                                        <div class="form-group">
                                            <label>Exemption de base RRQ</label>
                                            <input type="number" class="form-control" name="rrq_exemption">
                                        </div>
                                        <div class="form-group">
                                            <label>Cotisation maximale RRQ</label>
                                            <input type="number" step="0.001"  class="form-control" name="rrq_max_contribution">
                                        </div>
                                        <div class="form-group">
                                            <label>Taux de cotisation RRQ (%)</label>
                                            <input type="number" step="0.001"  class="form-control" name="taux_de_cotisation_rrq">
                                        </div>
                                        <input type="hidden" name="rrq_max_gains" value="{{ old('rrq_max_gains', 0) }}">
                                        <input type="hidden" name="rrq_hourly_exemption" value="{{ old('rrq_hourly_exemption', 0) }}">
                                        <input type="hidden" name="rrq_hourly_contribution" value="{{ old('rrq_hourly_contribution', 0) }}">>
                                    </div>

                                    <!-- 🟢 AE -->
                                    <div class="tab-pane fade" id="ae">
                                        <div class="form-group">
                                            <label>Max Salaire AE</label>
                                            <input type="number" class="form-control" name="ae_max_salary">
                                        </div>
                                        <div class="form-group">
                                            <label>Taux Employé (%)</label>
                                            <input type="number" step="0.001" class="form-control" name="ae_rate_employee">
                                        </div>
                                        <div class="form-group">
                                            <label>Part Employeur (x employé)</label>
                                            <input type="number" step="0.001"  class="form-control" name="ae_rate_employer">
                                        </div>
                                        <input type="hidden" name="ae_max_employee" value="{{ old('ae_max_employee', 0) }}">
                                        <input type="hidden" name="ae_max_employer" value="{{ old('ae_max_employer', 0) }}">
                                        <input type="hidden" name="ae_hourly_contribution" value="{{ old('ae_hourly_contribution', 0) }}">

                                    </div>

                                    <!-- 🟢 RQAP -->
                                    <div class="tab-pane fade" id="rqap">
                                        <div class="form-group">
                                            <label>Salaire Max RQAP</label>
                                            <input type="number" class="form-control" name="rqap_max_salary">
                                        </div>
                                        <div class="form-group">
                                            <label>Taux Employé RQAP (%)</label>
                                            <input type="number" step="0.001"  class="form-control" name="rqap_rate_employee">
                                        </div>
                                        <div class="form-group">
                                            <label>Taux Employeur RQAP (%)</label>
                                            <input type="number" step="0.001"   class="form-control" name="rqap_rate_employer">
                                        </div>
                                         <!-- Champs cachés pour RQAP -->
                                         <input type="hidden" name="rqap_max_contribution" value="{{ old('rqap_max_contribution', 0) }}">
                                         <input type="hidden" name="rqap_hourly_contribution" value="{{ old('rqap_hourly_contribution', 0) }}">

                                    </div>

                                    <!-- 🟢 CNT -->
                                    <div class="tab-pane fade" id="cnt">
                                        <div class="form-group">
                                            <label>Salaire Max CNT</label>
                                            <input type="number" class="form-control" name="cnt_max_salary">
                                        </div>
                                        <div class="form-group">
                                            <label>Taux CNT (%)</label>
                                            <input type="number" step="0.001"  class="form-control" name="cnt_rate">
                                        </div>
                                    </div>

                                    <!-- 🟢 FSS -->
                                    <div class="tab-pane fade" id="fss">
                                        <div class="form-group">
                                            <label>Taux FSS (%)</label>
                                            <input type="number" step="0.001"  class="form-control" name="fss_rate">
                                        </div>
                                    </div>
                                </div>

                                <!-- 🔹 Boutons -->
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <button type="reset" class="btn btn-secondary">Réinitialiser</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
