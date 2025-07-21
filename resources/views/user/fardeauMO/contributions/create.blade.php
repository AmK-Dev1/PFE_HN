@extends('user.layouts.app')

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
                            <a href="{{ route('user.dashboard') }}">{{ __('labels.fields.dashboard') }}</a>
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
                            <button title="{{ __('messages.static.back') }}" onclick="window.location.href='{{ route('user.fardeauMO.contributions.index') }}'" class="btn btn-outline-info">
                                <i data-feather="arrow-left"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('user.fardeauMO.contributions.store') }}">
                                @csrf

                                <!-- 🔹 Sélection de l’année -->
                                <div class="form-group">
                                    <label>Année</label>
                                    <input type="number" class="form-control" name="year" required>
                                </div>

                                <!-- 🔹 Onglet CSST (Seule contribution disponible) -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#csst">CSST</a>
                                    </li>
                                </ul>

                                <!-- 🔹 Contenu de l'onglet -->
                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="csst">
                                        <div class="form-group">
                                            <label>Taux CSST (%)</label>
                                            <input type="number" step="0.001" class="form-control" name="csst_rate" required>
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
