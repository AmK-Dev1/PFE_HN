@extends('user.layouts.app')

@push('css')

    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/app-assets/css-rtl/pages/app-user.css')}}">

    <style>

        #map {
            height: 500px;
        }

        .svg {
            width: 16px;
            height: 16px;
        }
    </style>
@endpush

@section('title',trans_choice('messages.static.details',2))

@section('content')

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">{{__('messages.static.view')}}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{route('user.dashboard')}}">{{__('labels.fields.dashboard')}}</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{route('user.budgets.index')}}">{{__('messages.static.list',['name'=> trans_choice('labels.models.budget',2)])}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        {{__('messages.static.view')}}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section class="app-user-view">
                    <!-- User Card starts-->
                    <div class="col-md-12">
                        <div class="card user-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-9">
                                        <h5>Budget de l'année :</h5>
                                    </div>
                                    <div class="col-3">
                                        Période du : {{ $year }}
                                    </div>

                                    <div class="col-12 mt-3">
                                        <div class="table-responsive">
                                            <form method="POST" action="{{ route('user.budgets.storeImported',$year) }}">
                                                @csrf
                                                <input type="hidden" name="year" value="{{ $year }}">
                                                <table class="table" id="table">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>Actions</th>
                                                        <th>Code</th>
                                                        <th>Type</th>
                                                        <th>Subtype</th>
                                                        <th>Description</th>
                                                        <th>Débit</th>
                                                        <th>Crédit</th>
                                                        <th>Montant</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($budgets as $index => $budget)
                                                        <tr id="budget-row-{{ $index }}">
                                                            <td>
                                                                <!-- Delete button for each row -->
                                                                <button type="button" class="btn btn-danger btn-sm delete-row" onclick="deleteRow({{ $index }})">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <td><input type="text" name="budgets[{{ $index }}][code]" value="{{ $budget['code'] }}" class="form-control" required></td>
                                                            <td>
                                                                <select name="budgets[{{ $index }}][type_id]" id="budgets[{{ $index }}][type_id]" class="form-control type-select" data-index="{{ $index }}">
                                                                    <option value="">Select Type</option>
                                                                    @foreach($types as $type)
                                                                        <option value="{{ $type->id }}" @if(isset($budget['type_id']) && $budget['type_id'] == $type->id) selected @endif>
                                                                            {{ $type->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="budgets[{{ $index }}][subtype_id]" id="budgets[{{ $index }}][subtype_id]" class="form-control subtype-select" disabled>
                                                                    <option value="">Select Subtype</option>
                                                                    <!-- Options will be populated dynamically based on the selected type -->
                                                                </select>
                                                            </td>
                                                            <td><input type="text" name="budgets[{{ $index }}][description]" value="{{ $budget['description'] }}" class="form-control"></td>
                                                            <td><input type="number" name="budgets[{{ $index }}][debit]" value="{{ $budget['debit'] }}" class="form-control" required></td>
                                                            <td><input type="number" name="budgets[{{ $index }}][credit]" value="{{ $budget['credit'] }}" class="form-control" required></td>
                                                            <td><input type="number" name="budgets[{{ $index }}][amount]" value="{{ $budget['amount'] }}" class="form-control" required></td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                <button type="submit" class="btn btn-primary">Save Budgets</button>
                                            </form>
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

@push('js')
    <script src="{{asset('assets/admin/app-assets/js/scripts/pages/app-user-view.js')}}"></script>

    @include('user.layouts.partials.delete', ['route' => '/user/budgets/'])

    <script>

        function deleteRow(index) {
            // Find the row by its ID and remove it from the DOM
            const row = document.getElementById(`budget-row-${index}`);
            if (row) {
                row.remove();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const typesWithSubtypes = @json($typesWithSubtypes); // Array of types and their subtypes

            // Initialize subtype dropdowns on page load
            document.querySelectorAll('.type-select').forEach(select => {
                const index = select.getAttribute('data-index');
                const subtypeSelect = document.querySelector(`#budgets\\[${index}\\]\\[subtype_id\\]`);

                if (select.value) {
                    populateSubtypeDropdown(select.value, subtypeSelect, index);
                }

                // Event listener for type dropdown changes
                select.addEventListener('change', function() {
                    populateSubtypeDropdown(this.value, subtypeSelect, index);
                });
            });

            // Function to populate subtype dropdown based on selected type
            function populateSubtypeDropdown(selectedType, subtypeSelect, index) {
                // Clear the existing options
                subtypeSelect.innerHTML = '<option value="">Select Subtype</option>';

                if (typesWithSubtypes[selectedType]) {
                    // Enable subtype dropdown and populate it with options
                    subtypeSelect.disabled = false;
                    typesWithSubtypes[selectedType].forEach(subtype => {
                        const option = document.createElement('option');
                        option.value = subtype.id;
                        option.textContent = subtype.name;
                        // If a subtype is already selected, mark it as selected
                        if (parseInt(subtype.id) === parseInt({{ $budgets[$index]['subtype_id'] ?? 'null' }})) {
                            option.selected = true;
                        }
                        subtypeSelect.appendChild(option);
                    });
                } else {
                    // If no subtypes, disable the dropdown and add a placeholder
                    subtypeSelect.disabled = true;
                    subtypeSelect.innerHTML = '<option value="">No Subtypes Available</option>';
                }
            }
        });
    </script>

@endpush
