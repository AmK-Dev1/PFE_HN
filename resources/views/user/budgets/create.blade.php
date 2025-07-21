@extends('user.layouts.app')

@section('title',trans_choice('labels.models.budget',2))

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
                            <h2 class="content-header-title float-left mb-0">{{__('messages.static.create')}}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{route('user.dashboard')}}">{{__('labels.fields.dashboard')}}</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{route('user.budgets.index')}}"> {{__('messages.static.list',['name'=> trans_choice('labels.models.budget',2)])}}</a>
                                    </li>

                                    <li class="breadcrumb-item active">
                                        {{__('messages.static.create')}}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <ul class="nav nav-tabs" id="yearTabs" role="tablist">
                    @for ($i = $start; $i <= $end; $i++)
                        <li class="nav-item">
                            <a class="nav-link @if($i === $start) active @endif" id="year-{{ $i }}-tab" data-toggle="tab" href="#year-{{ $i }}" role="tab">
                                Year: {{ $i }}
                            </a>
                        </li>
                    @endfor
                </ul>
                <div class="tab-content mt-2" id="yearTabsContent">
                    @for ($i = $start; $i <= $end; $i++)
                        <div class="tab-pane fade @if($i === $start) show active @endif" id="year-{{ $i }}" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <form class="form form-horizontal" method="post" action="{{ route('user.budgets.store') }}">
                                        @csrf
                                        <h3 class="mt-3">Year: {{ $i }}</h3>
                                        <!-- Import from Excel Button pushed to the extreme right -->
                                        <div class="ml-auto">
                                            <a href="javascript:void(0)" class="btn btn-outline-success d-flex align-items-center mt-3"
                                               data-toggle="modal" data-target="#importModal-{{ $i }}">
                                                Import From Excel File
                                                <i class="fas fa-file-excel ml-2"></i>
                                            </a>
                                        </div>
                                        <div id="bv-section-{{ $i }}">
                                            <div class="row mb-3 bv-row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="bvs[{{ $i }}][0][code]">Code</label>
                                                        <input type="text" class="form-control" name="bvs[{{ $i }}][0][code]" placeholder="Enter Code" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="bvs[{{ $i }}][0][type_id]">Type</label>
                                                        <select name="bvs[{{ $i }}][0][type_id]" id="bvs[{{ $i }}][0][type_id]" class="form-control" onchange="loadSubtypes(this, {{ $i }}, 0)" required>
                                                            <option value="">Select Type</option>
                                                            @foreach($types as $type)
                                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group" id="subtype-wrapper-{{ $i }}-0">
                                                        <label for="bvs[{{ $i }}][0][subtype_id]">Subtype</label>
                                                        <select name="bvs[{{ $i }}][0][subtype_id]" id="bvs[{{ $i }}][0][subtype_id]" class="form-control">
                                                            <option value="">Select Subtype</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="bvs[{{ $i }}][0][credit]">Credit</label>
                                                        <input type="number" class="form-control" name="bvs[{{ $i }}][0][credit]" placeholder="Enter Credit" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="bvs[{{ $i }}][0][debit]">Debit</label>
                                                        <input type="number" class="form-control" name="bvs[{{ $i }}][0][debit]" placeholder="Enter Debit" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="bvs[{{ $i }}][0][description]">Description</label>
                                                        <input type="text" class="form-control" name="bvs[{{ $i }}][0][description]" placeholder="Enter Description" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-9 offset-sm-3 mt-2">
                                            <button type="submit" class="btn btn-primary mr-1">{{ __('messages.static.save') }}</button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="addBVRow({{ $i }})">{{ __('messages.static.addLine') }}</button>
                                        </div>
                                    </form>
                                    <div class="modal fade" id="importModal-{{ $i }}" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="importModalLabel">Import Budgets for Year {{ $i }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('user.budgets.import', $i) }}" method="POST" enctype="multipart/form-data">
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
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to dynamically add BV rows -->
    <script>
        function loadSubtypes(selectElement, year, rowIndex) {
            const typeId = selectElement.value;
            const subtypeWrapper = document.getElementById(`subtype-wrapper-${year}-${rowIndex}`);
            const subtypeSelect = document.getElementById(`bvs[${year}][${rowIndex}][subtype_id]`);

            if (typeId) {
                // Show the subtype wrapper and make AJAX request to load subtypes for the selected type
                subtypeWrapper.style.display = 'block';

                fetch(`/get-subtypes/${typeId}`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">Select Subtype</option>';
                        data.forEach(subtype => {
                            options += `<option value="${subtype.id}">${subtype.name}</option>`;
                        });
                        subtypeSelect.innerHTML = options;
                    })
                    .catch(error => {
                        console.error('Error fetching subtypes:', error);
                        subtypeWrapper.style.display = 'none'; // Hide in case of error
                        subtypeSelect.innerHTML = ''; // Clear previous options
                    });
            } else {
                // Hide the subtype wrapper if no type is selected
                subtypeWrapper.style.display = 'none';
                subtypeSelect.innerHTML = '<option value="">Select Subtype</option>';
            }
        }
        const types = @json($types);
        function addBVRow(year) {
            const bvSection = document.getElementById(`bv-section-${year}`);
            const rows = bvSection.getElementsByClassName('bv-row');

            // Create options for the type select input
            let typeOptions = '<option value="">Select Type</option>';
            types.forEach(type => {
                typeOptions += `<option value="${type.id}">${type.name}</option>`;
            });

            const rowIndex = rows.length; // Use the existing row count as the index for the new row

            // Create a new row with subtype selection
            const newRow = document.createElement('div');
            newRow.className = 'row mb-3 bv-row';
            newRow.innerHTML = `
        <div class="col-md-2">
            <div class="form-group">
                <label for="bvs[${year}][${rowIndex}][code]">Code</label>
                <input type="text" class="form-control" name="bvs[${year}][${rowIndex}][code]" placeholder="Enter Code" required>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="bvs[${year}][${rowIndex}][type_id]">Type</label>
                <select name="bvs[${year}][${rowIndex}][type_id]" id="bvs[${year}][${rowIndex}][type_id]" class="form-control type-select" onchange="loadSubtypes(this, ${year}, ${rowIndex})" required>
                    ${typeOptions}
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group" id="subtype-wrapper-${year}-${rowIndex}">
                <label for="bvs[${year}][${rowIndex}][subtype_id]">Subtype</label>
                <select name="bvs[${year}][${rowIndex}][subtype_id]" id="bvs[${year}][${rowIndex}][subtype_id]" class="form-control subtype-select">
                    <option value="">Select Subtype</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="bvs[${year}][${rowIndex}][credit]">Credit</label>
                <input type="number" class="form-control" name="bvs[${year}][${rowIndex}][credit]" placeholder="Enter Credit" required>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="bvs[${year}][${rowIndex}][debit]">Debit</label>
                <input type="number" class="form-control" name="bvs[${year}][${rowIndex}][debit]" placeholder="Enter Debit" required>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="bvs[${year}][${rowIndex}][description]">Description</label>
                <input type="text" class="form-control" name="bvs[${year}][${rowIndex}][description]" placeholder="Enter Description" required>
            </div>
        </div>
    `;

            // Append the new row to the section
            bvSection.appendChild(newRow);
        }

    </script>

@endsection

