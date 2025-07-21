@extends('user.layouts.app')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/app-assets/css-rtl/pages/app-user.css') }}">
@endpush

@section('title', trans_choice('messages.static.details', 2))

@section('content')

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">{{ __('messages.static.view') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('user.dashboard') }}">{{ __('labels.fields.dashboard') }}</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('user.fardeauMO.operation_types.index') }}">Types d'opérations</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        {{ __('messages.static.view') }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <section class="app-user-view">
                    <!-- Operation Type Card starts -->
                    <div class="col-md-12">
                        <div class="card user-card">
                            <div class="card-header">
                                <button title="{{ __('messages.static.back') }}"
                                        onclick="document.location = '{{ url()->previous() }}'" type="button"
                                        class="btn btn-icon btn-outline-info">
                                    <i data-feather='arrow-right'></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div
                                        class="col-xl-6 col-lg-12 d-flex flex-column justify-content-between border-container-lg">
                                        <div class="user-avatar-section">
                                            <div class="d-flex justify-content-start">
                                                <div class="d-flex flex-column ml-1">
                                                    <div class="user-info mb-1">
                                                        <h4 class="mb-0">{{ $operationType->name }}</h4>
                                                        <span class="card-email">ID: {{ $operationType->id }}</span>
                                                    </div>
                                                    <div class="d-flex flex-wrap">
                                                        <a href="{{ route('user.fardeauMO.operation_types.edit', $operationType->id) }}"
                                                           class="btn btn-outline-primary">
                                                            <i data-feather='edit-2'></i>
                                                        </a>

                                                        <button class="btn btn-outline-danger ml-1"
                                                                onclick="deleteItem({{ $operationType->id }})">
                                                            <i data-feather='trash-2'></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 mt-3 mt-xl-0">
                                        <div class="user-info-wrapper">
                                            <div class="d-flex flex-wrap my-50">
                                                <div class="user-info-title">
                                                    <i data-feather="calendar" class="mr-1"></i>
                                                    <span class="card-text user-info-title font-weight-bold mb-0">
                                                        {{ __('labels.fields.created_at') }}
                                                    </span>
                                                </div>
                                                <p class="card-text mb-0">{{ $operationType->created_at }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($operationType->company)
                                    <h4 class="mt-4">Entreprise associée</h4>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nom</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{ $operationType->company->id }}</td>
                                                <td>{{ $operationType->company->name }}</td>
                                                <td>
                                                    <a title="{{ __('messages.static.details') }}"
                                                       href="{{ route('user.companies.show', $operationType->company->id) }}">
                                                        <i class="mr-50 fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p>Aucune entreprise associée.</p>
                                @endif

                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="{{ asset('assets/admin/app-assets/js/scripts/pages/app-user-view.js') }}"></script>
    @include('user.layouts.partials.delete', ['route' => '/user/fardeauMO/operation_types/'])
@endpush
