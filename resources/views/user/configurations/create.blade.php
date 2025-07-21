@extends('user.layouts.app')

@section('title',trans_choice('labels.models.configuration',2))

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
                                        <a href="{{route('user.configurations.index')}}"> {{__('messages.static.list',['name'=> trans_choice('labels.models.configuration',2)])}}</a>
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
                <div class="card">
                    <div class="card-body">
                        <form class="form" action="{{ route('user.configurations.store') }}" method="POST">
                            @csrf
                            @foreach($types as $type)
                                <div class="row mb-3 bv-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label style="font-weight:bold">{{ $type->name }}</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="types[{{ $type->id }}][start]">Code Range Start:</label>
                                                    <input type="number" name="types[{{ $type->id }}][start]" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="types[{{ $type->id }}][end]">Code Range End:</label>
                                                    <input type="number" name="types[{{ $type->id }}][end]" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <button type="submit" class="btn btn-primary">Create Configurations</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

