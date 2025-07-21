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
                                    <a href="javascript:void(0);" class="brand-logo">
                                        <h1 class="brand-text text-primary ml-1">{{config('app.name')}}</h1>
                                    </a>
                                </a>

                                <h4 class="card-title mb-1">{{__('labels.login.reset-message')}}</h4>
{{--                                <p class="card-text mb-2">{{__('labels.login.reset-text')}}</p>--}}

                                <form class="auth-reset-password-form mt-2" action="{{route('office.passwords.setup')}}" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <label for="reset-password-new">{{__('labels.fields.password')}}</label>
                                        </div>
                                        <div class="input-group input-group-merge form-password-toggle @error('password') is-invalid @enderror">
                                            <input type="password" class="form-control form-control-merge @error('password') error @enderror" id="reset-password-new" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="reset-password-new" tabindex="1" autofocus />
                                            <div class="input-group-append">
                                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                            </div>
                                        </div>
                                        @error('password')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <label for="reset-password-confirm">{{__('labels.fields.password-confirmation')}}</label>
                                        </div>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input type="password" class="form-control form-control-merge" id="reset-password-confirm" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="reset-password-confirm" tabindex="2" />
                                            <div class="input-group-append">
                                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-block" tabindex="3">{{__('messages.static.save')}}</button>
                                </form>

                                <p class="text-center mt-2">
                                    <a href="{{route('office.offices.choose.index')}}"> <i data-feather="chevron-left"></i> {{__('labels.fields.skip')}} </a>
                                </p>
                            </div>
                        </div>
                        <!-- /Login v1 -->
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection


