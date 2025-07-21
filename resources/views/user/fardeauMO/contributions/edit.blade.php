@extends('user.layouts.app')

@section('title', 'Modifier une Contribution (CSST)')

@section('content')

<div class="container">
    <h2>✏️ Modifier la Contribution - CSST</h2>

    <div class="card">
        <div class="card-header">
            <button title="Retour" onclick="window.location.href='{{ route('user.fardeauMO.contributions.index') }}'" class="btn btn-outline-info">
                <i data-feather="arrow-left"></i> Retour
            </button>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('user.fardeauMO.contributions.update', $contribution->id) }}">
                @csrf
                @method('PUT')

                <!-- 🔹 Année (Lecture seule) -->
                <div class="form-group">
                    <label>Année</label>
                    <input type="number" class="form-control" name="year" value="{{ $contribution->year }}" readonly>
                </div>

                <!-- 🔹 Modification du Taux CSST -->
                <div class="form-group">
                    <label>Taux CSST (%)</label>
                    <input type="number" step="0.001" class="form-control" name="csst_rate" value="{{ $contribution->csst_rate }}" required>
                </div>

                <!-- 🔹 Boutons -->
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Mettre à Jour</button>
                    <a href="{{ route('user.fardeauMO.contributions.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
