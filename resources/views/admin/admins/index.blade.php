@extends('admin.layouts.app')

@section('title', trans_choice('labels.models.admin', 2))

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
                                {{ __('messages.static.list', ['name' => trans_choice('labels.models.admin', 2)]) }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">{{ __('labels.fields.dashboard') }}</a>
                                    </li>

                                    <li class="breadcrumb-item active">
                                        {{ __('messages.static.list', ['name' => trans_choice('labels.models.admin', 2)]) }}
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
                                        <a title="{{ __('messages.static.create') }}" href="{{route('admin.admins.create')}}"
                                            class="btn btn-icon btn-outline-primary">
                                            <i data-feather="plus"></i>
                                        </a>

                                </div>
                            </div>

                            <div class="card-body">
                                <div class="card-tools">
                                    <form id="filter-form">
                                        <div class="row justify-content-end">


                                            @include('admin.layouts.partials.search')

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
                                                <th>#</th>
                                                <th>{{ __('labels.fields.image') }}</th>
                                                <th>{{ __('labels.fields.name') }}</th>
                                                <th>{{ __('labels.fields.email') }}</th>
                                                <th>{{ __('messages.static.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($admins as $admin)
                                                <tr>
                                                    <td>
                                                        {{ $admin->id }}
                                                    </td>
                                                    <td>
                                                        <span class="avatar">
                                                            <img src="{{ $admin->image_url }}" class="round"
                                                                height="50" width="50" alt="{{ $admin->name }} logo" />
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ $admin->name }}
                                                    </td>
                                                    <td>
                                                        <a href="mailto:{{ $admin->email }}"
                                                            class="text-decoration-none">{{ $admin->email }}</a>
                                                    </td>
                                                    <td>


                                                            <a title="{{ __('messages.static.details') }}"
                                                                href="{{ route('admin.admins.show', $admin) }}">
                                                                <i class="mr-50 fas fa-eye"></i>
                                                            </a>




                                                            <a title="{{ __('messages.static.edit') }}"
                                                                href="{{ route('admin.admins.edit', $admin->id) }}">
                                                                <i class="mr-50 fas fa-edit"></i>
                                                            </a>

                                                            <a title="{{ __('messages.static.edit') }}"
                                                                href="{{ route('admin.admins.edit-password', $admin->id) }}">
                                                                <i class="mr-50 fas fa-lock"></i>
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

    @include('admin.layouts.partials.delete', ['route' => '/admin/admins/'])


