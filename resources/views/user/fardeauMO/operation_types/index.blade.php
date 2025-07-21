@extends('user.layouts.app')

@section('title', trans_choice('labels.models.operation_type', 2))

@section('content')

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">
                                {{ __('messages.static.list', ['name' => trans_choice('labels.models.operation_type', 2)]) }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('user.dashboard') }}">{{ __('labels.fields.dashboard') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        {{ __('messages.static.list', ['name' => trans_choice('labels.models.operation_type', 2)]) }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="row" id="table-head">
                    <div class="col-12">

                        <div class="card">

                            <!-- 🔹 Bouton "Ajouter" un type d'opération -->
                            <div class="card-header">
                                <div>
                                    <a title="{{ __('messages.static.create') }}" 
                                       href="{{ route('user.fardeauMO.operation_types.create') }}"
                                       class="btn btn-icon btn-outline-primary">
                                        <i data-feather="plus"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="card-tools">
                                    <form id="filter-form">
                                        <div class="row justify-content-end">
                                            @include('user.layouts.partials.search')

                                            <div class="form-group mr-1 mt-2">
                                                <button type="submit"
                                                        class="btn btn-success">{{ __('messages.static.search') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- 🔹 Tableau des types d'opérations -->
                                <div class="table-responsive">
                                    <table class="table" id="table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('labels.fields.name') }}</th>
                                                <th>{{ __('messages.static.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($operationTypes as $operationType)
                                                <tr>
                                                    <td>{{ $operationType->id }}</td>
                                                    <td>{{ $operationType->name }}</td>
                                                    <td>

                                                        <!-- 🔹 Bouton "Voir" -->
                                                        <a title="{{ __('messages.static.details') }}"
                                                           href="{{ route('user.fardeauMO.operation_types.show', $operationType->id) }}">
                                                            <i class="mr-50 fas fa-eye"></i>
                                                        </a>
                                                        

                                                        <!-- 🔹 Bouton "Modifier" -->
                                                        <a title="{{ __('messages.static.edit') }}"
                                                           href="{{ route('user.fardeauMO.operation_types.edit', $operationType->id) }}">
                                                            <i class="mr-50 fas fa-edit"></i>
                                                        </a>

                                                        <!-- 🔹 Bouton "Supprimer" avec confirmation -->
                                                        <a title="{{ __('messages.static.delete') }}"
                                                           onclick="deleteItem({{ $operationType->id }})" href="javascript:void(0);">
                                                            <i class="mr-50 fas fa-trash"></i>
                                                        </a>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

@endsection

<!-- 🔹 Inclusion du script de suppression -->
@include('user.layouts.partials.delete', ['route' => '/user/fardeauMO/operation_types/'])
