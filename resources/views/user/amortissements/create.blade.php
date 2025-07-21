@extends('user.layouts.app')

@section('title', 'Créer des Amortissements')

@push('css')
<style>
    .tab-content {
        min-height: 500px;
    }
    .tab-pane {
        padding: 15px;
    }
    .fixed-action-bar {
        position: sticky;
        top: 0;
        z-index: 1050;
        background: white;
        padding: 10px 0;
        border-bottom: 1px solid #ccc;
    }
</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12">
                <div class="fixed-action-bar d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary mr-2">← </a>
                        <h4 class="mb-0 d-inline">Amortissements</h4>
                    </div>
                    <button type="submit" form="amortissementsForm" class="btn btn-primary">
                        Enregistrer 
                    </button>
                </div>
            </div>
        </div>
        <div class="content-body">
            <ul class="nav nav-tabs" id="yearTabs" role="tablist">
                @for ($i = $start; $i <= $end; $i++)
                    <li class="nav-item">
                        <a class="nav-link @if($i === $start) active @endif" id="year-{{ $i }}-tab" data-toggle="tab" href="#year-{{ $i }}" role="tab">
                            {{ $i }}
                        </a>
                    </li>
                @endfor
            </ul>
            <form method="POST" action="{{ route('user.amortissements.store') }}" id="amortissementsForm">
                @csrf
                <input type="hidden" name="active_year" id="active_year" value="{{ old('active_year', $start) }}">
                <div class="tab-content mt-2" id="yearTabsContent">
                    @for ($i = $start; $i <= $end; $i++)
                        <div class="tab-pane fade @if($i === $start) show active @endif" id="year-{{ $i }}" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Année : {{ $i }}</h5>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Poste</th>
                                                    <th>Coût</th>
                                                    <th>Amort. Cumulé Préc.</th>
                                                    <th>Valeur Nette Préc.</th>
                                                    <th>Acquisition</th>
                                                    <th>Amort. Année</th>
                                                    <th>Amort. Mensuel</th>
                                                    <th>Taux %</th>
                                                    <th>Méthode</th>
                                                </tr>
                                            </thead>
                                            <tbody id="rows-{{ $i }}">
                                                @if(old('amortissements.' . $i))
                                                    @foreach(old('amortissements.' . $i) as $key => $line)
                                                    <tr>
                                                        <td><input type="text" name="amortissements[{{ $i }}][{{ $key }}][poste]" class="form-control input-calc" value="{{ $line['poste'] }}"></td>
                                                        <td><input type="number" step="0.01" name="amortissements[{{ $i }}][{{ $key }}][cout]" class="form-control input-calc" value="{{ $line['cout'] }}" @if($i > $start) readonly @endif></td>
                                                        <td><input type="number" step="0.01" name="amortissements[{{ $i }}][{{ $key }}][amort_cumule_anterieur]" class="form-control input-calc" value="{{ $line['amort_cumule_anterieur'] }}" @if($i > $start) readonly @endif></td>
                                                        <td><input type="text" class="form-control valeur-nette" name="amortissements[{{ $i }}][{{ $key }}][valeur_nette_anterieure]" readonly value="{{ $line['valeur_nette_anterieure'] }}"></td>
                                                        <td><input type="number" step="0.01" name="amortissements[{{ $i }}][{{ $key }}][acquisition_annee]" class="form-control input-calc" value="{{ $line['acquisition_annee'] }}"></td>
                                                        <td><input type="text" class="form-control amort-annee" name="amortissements[{{ $i }}][{{ $key }}][amortissement_annee]" readonly value="{{ $line['amortissement_annee'] }}"></td>
                                                        <td><input type="text" class="form-control amort-mois" name="amortissements[{{ $i }}][{{ $key }}][amortissement_mensuel]" readonly value="{{ $line['amortissement_mensuel'] }}"></td>
                                                        <td><input type="number" step="0.01" name="amortissements[{{ $i }}][{{ $key }}][taux]" class="form-control input-calc" value="{{ $line['taux'] }}"></td>
                                                        <td>
                                                            <select name="amortissements[{{ $i }}][{{ $key }}][type_amortissement]" class="form-control input-calc">
                                                                <option value="L" @if($line['type_amortissement'] === 'L') selected @endif>L (Linéaire)</option>
                                                                <option value="D" @if($line['type_amortissement'] === 'D') selected @endif>D (Dégressif)</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td><input type="text" name="amortissements[{{ $i }}][0][poste]" class="form-control input-calc"></td>
                                                        <td><input type="number" step="0.01" name="amortissements[{{ $i }}][0][cout]" class="form-control input-calc" @if($i > $start) readonly @endif></td>
                                                        <td><input type="number" step="0.01" name="amortissements[{{ $i }}][0][amort_cumule_anterieur]" class="form-control input-calc" @if($i > $start) readonly @endif></td>
                                                        <td><input type="text" class="form-control valeur-nette" name="amortissements[{{ $i }}][0][valeur_nette_anterieure]" readonly></td>
                                                        <td><input type="number" step="0.01" name="amortissements[{{ $i }}][0][acquisition_annee]" class="form-control input-calc"></td>
                                                        <td><input type="text" class="form-control amort-annee" name="amortissements[{{ $i }}][0][amortissement_annee]" readonly></td>
                                                        <td><input type="text" class="form-control amort-mois" name="amortissements[{{ $i }}][0][amortissement_mensuel]" readonly></td>
                                                        <td><input type="number" step="0.01" name="amortissements[{{ $i }}][0][taux]" class="form-control input-calc"></td>
                                                        <td>
                                                            <select name="amortissements[{{ $i }}][0][type_amortissement]" class="form-control input-calc">
                                                                <option value="L">L (Linéaire)</option>
                                                                <option value="D">D (Dégressif)</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-secondary mb-3" onclick="addRow({{ $i }})">Ajouter une ligne</button>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
    document.getElementById('amortissementsForm').addEventListener('submit', function(e) {
        const activeTab = document.querySelector('.nav-link.active').getAttribute('href');
        localStorage.setItem('activeTab', activeTab);
        document.getElementById('active_year').value = activeTab.replace('#year-', '');
    });

    function addRow(year) {
        const tbody = document.getElementById('rows-' + year);
        const index = tbody.rows.length;
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" name="amortissements[${year}][${index}][poste]" class="form-control input-calc"></td>
            <td><input type="number" step="0.01" name="amortissements[${year}][${index}][cout]" class="form-control input-calc" ${(year > {{ $start }}) ? 'readonly' : ''}></td>
            <td><input type="number" step="0.01" name="amortissements[${year}][${index}][amort_cumule_anterieur]" class="form-control input-calc" ${(year > {{ $start }}) ? 'readonly' : ''}></td>
            <td><input type="text" class="form-control valeur-nette" name="amortissements[${year}][${index}][valeur_nette_anterieure]" readonly></td>
            <td><input type="number" step="0.01" name="amortissements[${year}][${index}][acquisition_annee]" class="form-control input-calc"></td>
            <td><input type="text" class="form-control amort-annee" name="amortissements[${year}][${index}][amortissement_annee]" readonly></td>
            <td><input type="text" class="form-control amort-mois" name="amortissements[${year}][${index}][amortissement_mensuel]" readonly></td>
            <td><input type="number" step="0.01" name="amortissements[${year}][${index}][taux]" class="form-control input-calc"></td>
            <td>
                <select name="amortissements[${year}][${index}][type_amortissement]" class="form-control input-calc">
                    <option value="L">L (Linéaire)</option>
                    <option value="D">D (Dégressif)</option>
                </select>
            </td>
        `;
        tbody.appendChild(row);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            const tab = new bootstrap.Tab(document.querySelector(`a[href="${activeTab}"]`));
            tab.show();
        }
        document.querySelectorAll('a[data-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (e) {
                localStorage.setItem('activeTab', e.target.getAttribute('href'));
            });
        });

        document.addEventListener('input', function (event) {
            if (event.target.classList.contains('input-calc')) {
                const row = event.target.closest('tr');
                const cout = parseFloat(row.querySelector('[name*="[cout]"]').value) || 0;
                const cumule = parseFloat(row.querySelector('[name*="[amort_cumule_anterieur]"]').value) || 0;
                const acquisition = parseFloat(row.querySelector('[name*="[acquisition_annee]"]').value) || 0;
                const taux = parseFloat(row.querySelector('[name*="[taux]"]').value) || 0;
                const methode = row.querySelector('[name*="[type_amortissement]"]').value;

                let valeur_nette = cout - cumule;
                let amort_annee = 0;

                if (methode === 'L') {
                    amort_annee = cout * (taux / 100) * 0.5;
                } else if (methode === 'D') {
                    amort_annee = (valeur_nette + (acquisition / 2)) * (taux / 100);
                }
                const amort_mois = amort_annee / 12;

                row.querySelector('.valeur-nette').value = valeur_nette.toFixed(2);
                row.querySelector('.amort-annee').value = amort_annee.toFixed(2);
                row.querySelector('.amort-mois').value = amort_mois.toFixed(2);
            }
        });
    });
</script>
@endpush
@endsection
