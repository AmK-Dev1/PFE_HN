@extends('user.layouts.app')

@section('title', trans_choice('labels.models.budget', 2))

@push('css')

@endpush


@section('content')

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">
                                {{ __('messages.static.list', ['name' => trans_choice('labels.models.budget', 2)]) }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('user.dashboard') }}">{{ __('labels.fields.dashboard') }}</a>
                                    </li>

                                    <li class="breadcrumb-item active">
                                        {{ __('messages.static.list', ['name' => trans_choice('labels.models.budget', 2)]) }}
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

                            <div class="card-header">
                                <div>
                                        <a title="{{ __('messages.static.create') }}"
                                           href="{{ route('user.budgets.create') }}"
                                           data-toggle="modal" data-target="#yearModal"
                                           class="btn btn-icon btn-outline-primary">
                                            <i data-feather="plus"></i>
                                        </a>
                                </div>
                                <div class="ml-auto">
                                    <a href="" class="btn btn-outline-primary"
                                       data-toggle="modal" data-target="#yearModal2">
                                        Analyse Budget
                                        <i class="fas fa-file-import ml-2"></i>
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
                                <div class="table-responsive">
                                    <table class="table" id="table">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Année</th>
                                            <th>{{ __('messages.static.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($years as $year)
                                            <tr >
                                                <td>
                                                    {{ $year->year }}
                                                </td>
                                                <td>
                                                    <a title="{{ __('messages.static.details') }}"
                                                       href="{{route('user.budgets.show',$year->year)}}">
                                                        <i class="mr-50 fas fa-eye"></i>
                                                    </a>

                                                    <a title="{{ __('messages.static.download') }}"
                                                       target="_blank"
                                                       href="{{--{{route('user.budget.pdf',$retrocession->id)}}--}}">
                                                        <i class="mr-50 fas fa-download"></i>
                                                    </a>

                                                    <form action="{{ route('user.budgets.destroy', $year->year) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-icon btn-outline-danger" title="{{ __('messages.static.delete') }}"
                                                                onclick="return confirm('{{ __('messages.static.confirm_delete') }}');">
                                                            <i class="mr-50 fas fa-trash"></i>
                                                        </button>
                                                    </form>

                                                    {{--<a title="{{ __('messages.static.delete') }}"
                                                       onclick="deleteItem({{ $year->year }})"
                                                       href="javascript:void(0);">
                                                        <i class="mr-50 fas fa-trash"></i>
                                                    </a>--}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {{--<div class="d-flex justify-content-center my-1">
                                        {{$retrocessions->links()}}
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="yearModal2" tabindex="-1" aria-labelledby="yearModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="yearModalLabel">Enter Start and End Year for analysis</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="startYear2">Start Year</label>
                        <input type="number" id="startYear2" class="form-control" placeholder="Enter Start Year" required>
                    </div>
                    <div class="form-group">
                        <label for="endYear2">End Year</label>
                        <input type="number" id="endYear2" class="form-control" placeholder="Enter End Year" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitYears2">Proceed</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Year selection modal -->
    <div class="modal fade" id="yearModal" tabindex="-1" aria-labelledby="yearModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="yearModalLabel">Enter Number of Years</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="startYear">Start year</label>
                        <input type="number" class="form-control" id="startYear" min="1" value="1" required>
                    </div>
                    <div class="form-group">
                        <label for="endYear">End year</label>
                        <input type="number" class="form-control" id="endYear" min="1" value="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitYears">Proceed</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        document.getElementById('submitYears').addEventListener('click', function () {
            // Get the number of years entered by the user
            const endYear = parseInt(document.getElementById('endYear').value);
            const startYear = parseInt(document.getElementById('startYear').value);

            var currentYear = new Date().getFullYear(); // Get the current year

            // Calculate the number of years
            var numOfYears = endYear - startYear;
            console.log('Start Year:', startYear, 'End Year:', endYear, 'Years:', numOfYears); // Debugging values

            // Ensure the start year is less than or equal to the end year and end year is not the current year
            if (numOfYears >= 0 && endYear !== currentYear) {
                // Redirect to the create route with the start, end, and numOfYears as query parameters
                window.location.href = "{{ route('user.budgets.create') }}" + "?start=" + startYear + "&end=" + endYear + "&years=" + numOfYears;
            } else {
                alert('Please enter a valid number of years.');
            }
        });

        document.getElementById('submitYears2').addEventListener('click', function () {
            // Get start and end year values from the input fields
            const startYear = document.getElementById('startYear2').value;
            const endYear = document.getElementById('endYear2').value;

            // Validate the years
            if (startYear && endYear && parseInt(startYear) <= parseInt(endYear)) {
                // Redirect to the budget analysis route with startYear and endYear as query parameters
                const url = "{{ route('user.budgets.analysis') }}" + "?start_year=" + startYear + "&end_year=" + endYear;
                window.location.href = url;
            } else {
                alert('Please enter a valid start and end year.');
            }
        });
    </script>

@endsection

@include('user.layouts.partials.delete', ['route' => '/user/budgets/'])

@push('js')

@endpush
