@extends('user.layouts.app')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/app-assets/css-rtl/pages/app-user.css')}}">
@endpush

@section('title', trans_choice('messages.static.details', 2))

@section('content')
<div class="app-content content">
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
                                    <a href="{{ route('user.budgets.index') }}">{{ __('messages.static.list', ['name'=> trans_choice('labels.models.budget',2)]) }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('messages.static.view') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="app-user-view">
                <div class="col-md-12">
                    <div class="card user-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <h5>Budget de l'année :</h5>
                                </div>
                                <div class="col-3">Période du : {{ $year }}</div>
                                <div class="col-12 mt-3">
                                    <div class="table-responsive">
                                        <table class="table" id="table">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>Type</th>
                                                <th>Code</th>
                                                <th>Description</th>
                                                <th>Débit</th>
                                                <th>Crédit</th>
                                                <th>Montant</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($groupedBudgets as $typeId => $budgetsGroup)
                                                @php
                                                    $type = $budgetsGroup->first()->type;
                                                    $typeTotal = $budgetsGroup->sum('amount');
                                                @endphp

                                                    <!-- Row for Type Name -->
                                                <tr class="table-secondary">
                                                    <td colspan="6" class="font-weight-bold">{{ $type ? $type->name : 'No Type' }}</td>
                                                </tr>

                                                <!-- Rows for Each Budget Line -->
                                                @foreach($budgetsGroup as $budget)
                                                    <tr>
                                                        <td></td> <!-- Empty cell to align with Type name row -->
                                                        <td>{{ $budget->code }}</td>
                                                        <td>{{ $budget->description }}</td>
                                                        <td>{{ $budget->debit }}</td>
                                                        <td>{{ $budget->credit }}</td>
                                                        <td>{{ $budget->amount }}</td>
                                                    </tr>
                                                @endforeach

                                                <!-- Row for Type Total -->
                                                <tr>
                                                    <td colspan="5" class="text-right font-weight-bold">Total for {{ $type ? $type->name : 'No Type' }}:</td>
                                                    <td class="font-weight-bold">{{ $typeTotal }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Modal Structure -->
                                    <div class="modal fade" id="importModal-{{ $year }}" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="importModalLabel">Import Budgets for Year {{ $year }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('user.budgets.import', $year) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="file">Choose Excel File</label>
                                                            <input type="file" name="file" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Import</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
