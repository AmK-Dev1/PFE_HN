@extends('admin.layouts.app')

@push('css')

    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/app-assets/css-rtl/pages/app-user.css')}}">

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
                                        <a href="{{route('admin.dashboard')}}">{{__('labels.fields.dashboard')}}</a>
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
                            <div class="card-header">
                                <button title="{{__('messages.static.back')}}"
                                        onclick="document.location = '{{url()->previous()}}'" type="button"
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
                                                        <h4 class="mb-0">{{$company->name}}</h4>
                                                        <span class="card-email">{{$company->description}}</span>
                                                    </div>
                                                    <div class="d-flex flex-wrap">

                                                            <a href="{{route('admin.companies.edit', $company->id)}}"
                                                               class="btn btn-outline-primary">
                                                                <i data-feather='edit-2'></i>
                                                            </a>


                                                            <button class="btn btn-outline-danger ml-1"
                                                                    onclick="deleteItem({{$company->id}})">
                                                                <i data-feather='trash-2'></i>
                                                            </button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6  col-lg-6 mt-3 mt-xl-0">
                                        <div class="user-info-wrapper">





                                            <div class="d-flex flex-wrap my-50">
                                                <div class="user-info-title">
                                                    <i data-feather="calendar" class="mr-1"></i>
                                                    <span
                                                        class="card-text user-info-title font-weight-bold mb-0">{{__('labels.fields.created_at')}}</span>
                                                </div>
                                                <p class="card-text mb-0">{{$company->created_at}}</p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @if($company->users->isEmpty())
                                    <p>No users found for this company.</p>
                                @else
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
                                            {{--<th>{{ __('labels.fields.image') }}</th>--}}
                                            <th>{{ __('labels.fields.name') }}</th>
                                            <th>{{ __('labels.fields.email') }}</th>
                                            <th>{{ trans_choice('labels.models.role', 1) }}</th>
                                            <th>{{ __('messages.static.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($company->users as $user)
                                            <tr>
                                                <td>
                                                    {{ $user->id }}
                                                </td>
                                                {{--<td>
                                                    <span class="avatar">
                                                        <img src="{{ $user->image_url }}" class="round"
                                                            height="50" width="50" alt="{{ $user->name }} logo" />
                                                    </span>
                                                </td>--}}
                                                <td>
                                                    {{ $user->name }}
                                                </td>
                                                <td>
                                                    <a href="mailto:{{ $user->email }}"
                                                       class="text-decoration-none">{{ $user->email }}</a>
                                                </td>
                                                <td>
                                                    @foreach ($user->roles as $role)
                                                        <span class="badge badge-success">
                                                                {{ $role->name }}
                                                            </span>
                                                    @endforeach
                                                </td>
                                                <td>


                                                    <a title="{{ __('messages.static.details') }}"
                                                       href="{{ route('admin.users.show', $user) }}">
                                                        <i class="mr-50 fas fa-eye"></i>
                                                    </a>




                                                    <a title="{{ __('messages.static.edit') }}"
                                                       href="{{ route('admin.users.edit', $user->id) }}">
                                                        <i class="mr-50 fas fa-edit"></i>
                                                    </a>

                                                    <a title="{{__('messages.static.delete')}}"
                                                       onclick="deleteItem({{$user->id}})" href="javascript:void(0);">
                                                        <i class="mr-50 fas fa-trash"></i>
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
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

    <script src="{{asset('assets/admin/app-assets/js/scripts/pages/app-user-view.js')}}"></script>

    @can('delete-tag')
        @include('admin.layouts.partials.delete', ['route' => '/admin/companies/'])
    @endcan
@endpush

