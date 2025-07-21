@extends('user.layouts.app')

@section('content')
<div class="container">
    <h2>Modifier le type d'opération</h2>

    <form action="{{ route('user.fardeauMO.operation_types.update', $operationType->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label for="name">Nom du type d'opération</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $operationType->name }}" required>
        </div>
        <button type="submit" class="btn btn-success">Modifier</button>
    </form>
</div>
@endsection
