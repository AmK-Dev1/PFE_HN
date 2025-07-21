@extends('user.layouts.app')

@section('title', trans_choice('labels.models.configuration', 2))

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
                                {{ __('messages.static.list', ['name' => trans_choice('labels.models.configuration', 2)]) }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('user.dashboard') }}">{{ __('labels.fields.dashboard') }}</a>
                                    </li>

                                    <li class="breadcrumb-item active">
                                        {{ __('messages.static.list', ['name' => trans_choice('labels.models.configuration', 2)]) }}
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
                                           href="{{ route('user.configurations.create') }}"
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
                                <div class="table-responsive">
                                    <table class="table" id="table">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Company</th>
                                            <th>Type</th>
                                            <th>Code Start</th>
                                            <th>Code End</th>
                                            <th>{{ __('messages.static.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($configurations as $config)
                                            <tr >
                                                <td>{{ $config->company->name }}</td>
                                                <td>{{ $config->type->name }}</td>
                                                <td>{{ $config->code_start }}</td>
                                                <td>{{ $config->code_end }}</td>
                                                <td>
                                                    <a href="{{ route('user.configurations.edit', $config->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                                    <form action="{{ route('user.configurations.destroy', $config->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-icon btn-outline-danger" title="{{ __('messages.static.delete') }}"
                                                                onclick="return confirm('{{ __('messages.static.confirm_delete') }}');">
                                                            <i class="mr-50 fas fa-trash"></i>
                                                        </button>
                                                    </form>

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

@include('user.layouts.partials.delete', ['route' => '/user/configurations/'])


@push('js')

@endpush
