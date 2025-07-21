@extends('user.auth.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-v1 px-2">
                    <div class="auth-inner py-2">
                        <!-- Login v1 -->
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="javascript:void(0);" class="brand-logo">
                                    <h1 class="brand-text text-primary ml-1">{{config('app.name')}}</h1>
                                </a>

                                <div class="auth-login-form mt-2">
                                    @csrf
                                    <ul class="list-group">
                                        @foreach($companies as $company)
                                            <li class="list-group-item">
                                                <form action="{{ route('user.company.set', $company) }}" method="POST"
                                                      class="d-flex justify-content-between align-items-center">
                                                    @csrf
                                                    <h5>{{ $company->name }}</h5>
                                                    <button type="submit" class="btn btn-sm btn-info">{{ __('messages.static.manage') }}</button>
                                                </form>

                                            </li>
                                        @endforeach

                                    </ul>
                                    <a class="dropdown-item mt-25" href="javascript:void(0)"
                                       onclick="document.getElementById('logout-form').submit()">
                                        <i class="mr-50 " data-feather="power"></i>
                                        {{__('labels.login.logout')}}
                                    </a>
                                    <form action="{{route('user.logout')}}" id="logout-form" method="post">@csrf</form>

                                </div>
                            </div>
                        </div>
                        <!-- /Login v1 -->
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
