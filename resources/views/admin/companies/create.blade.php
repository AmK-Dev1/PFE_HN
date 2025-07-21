@extends('admin.layouts.app')

@section('title',trans_choice('labels.models.company',2))

@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/admin/app-assets/vendors/css/forms/select/select2.min.css')}}">
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
                                        <a href="{{route('admin.dashboard')}}">{{__('labels.fields.dashboard')}}</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{route('admin.companies.index')}}">{{__('messages.static.list',['name'=> trans_choice('labels.models.company',2)])}}</a>
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
                <div class="row" id="table-head">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <button title="{{__('messages.static.back')}}" onclick="document.location = '{{url()->previous()}}'" type="button" class="btn btn-icon btn-outline-info">
                                    <i data-feather='arrow-right'></i>
                                </button>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">{{__('messages.static.create')}}</h4>
                                    </div>
                                    <div class="card-body">
                                        <form class="form form-horizontal" method="post" action="{{route('admin.companies.store')}}">
                                            @csrf
                                            <div class="row">
                                                <!-- Name Field -->
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="name">{{ __('labels.fields.name') }}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                               id="name" name="name" placeholder="{{ __('labels.fields.name') }}"
                                                               value="{{ old('name') }}" required style="max-width: 400px;" />
                                                        @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Description Field -->
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="description">{{ __('labels.fields.description') }}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control @error('description') is-invalid @enderror"
                                                               id="description" name="description"
                                                               placeholder="{{ __('labels.fields.description') }}"
                                                               value="{{ old('description') }}" required style="max-width: 400px;" />
                                                        @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Search field and table -->
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label for="user_search">{{trans_choice('labels.models.user',2)}}</label>
                                                <input type="text" id="user_search" class="form-control" placeholder="Search users..."
                                                       style="width: 300px;">
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover w-100" id="user_list">
                                                    <!-- User list populated by AJAX -->
                                                </table>
                                            </div>

                                            <div class="col-sm-9 offset-sm-3 mt-2">
                                                <button type="submit" class="btn btn-primary mr-1">{{__('messages.static.save')}}</button>
                                                <button type="reset" class="btn btn-outline-secondary">{{__('messages.static.reset')}}</button>
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
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Load the initial 10 users when the page loads
            fetchUsers('');

            // Fetch users when typing in the search input
            $('#user_search').on('keyup', function() {
                var query = $(this).val();
                fetchUsers(query);
            });

            function fetchUsers(query) {
                $.ajax({
                    url: "{{ route('admin.companies.searchUsers') }}",
                    type: 'GET',
                    data: { query: query },
                    success: function(data) {
                        $('#user_list').html(data);
                    }
                });
            }
        });
    </script>

@endsection
