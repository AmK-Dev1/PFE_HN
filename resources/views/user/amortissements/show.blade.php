@extends('user.layouts.app')

@section('title', "Amortissements - Année $year")

@section('content')
<div class="app-content content ">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12">
                <h2>Amortissements pour l'année {{ $year }}</h2>
                <a href="{{ route('user.amortissements.create', ['start' => $year, 'end' => $year]) }}#year-{{ $year }}" class="btn btn-outline-primary mb-2">
                    Modifier cette année
                </a>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Poste</th>
                                    <th>Coût</th>
                                    <th>Amort. cumulé préc.</th>
                                    <th>Valeur nette préc.</th>
                                    <th>Acquisition</th>
                                    <th>Amort. année</th>
                                    <th>Amort. mensuel</th>
                                    <th>Taux %</th>
                                    <th>Méthode</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($amortissements as $a)
                                    <tr>
                                        <td>{{ $a->poste }}</td>
                                        <td>{{ number_format($a->cout, 2, ',', ' ') }}</td>
                                        <td>{{ number_format($a->amort_cumule_anterieur, 2, ',', ' ') }}</td>
                                        <td>{{ number_format($a->valeur_nette_anterieure, 2, ',', ' ') }}</td>
                                        <td>{{ number_format($a->acquisition_annee, 2, ',', ' ') }}</td>
                                        <td>{{ number_format($a->amortissement_annee, 2, ',', ' ') }}</td>
                                        <td>{{ number_format($a->amortissement_mensuel, 2, ',', ' ') }}</td>
                                        <td>{{ $a->taux }}%</td>
                                        <td>{{ $a->type_amortissement }}</td>
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
@endsection
