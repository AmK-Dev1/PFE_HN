@extends('user.layouts.app')

@section('title', 'Coût par Camion')

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="col-12">
                <h2 class="content-header-title">Coût par Camion</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('user.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Coût par Camion</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Tableau de gestion des coûts</h4>
                            <div>
                                <button id="addRow" type="button" class="btn btn-primary btn-sm">+ Ajouter une ligne</button>
                                <button id="addColumn" type="button" class="btn btn-outline-secondary btn-sm">+ Ajouter une colonne personnalisée</button>
                            </div>
                        </div>
                        <form action="{{ route('user.coutscamion.store') }}" method="POST" id="camionForm">
                            @csrf
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="camionTable" class="table table-borderless text-center align-middle">
                                        <thead class="thead-light">
                                            <tr id="tableHead">
                                                <th style="min-width: 200px">Unité</th>
                                                <th>Année de construction</th>
                                                <th>No plaque</th>
                                                <th>Responsable</th>
                                                <th>Marque</th>
                                                <th>Coût/km</th>
                                                <th>KM parcourus</th>
                                                <th>Coût/hr</th>
                                                <th>Heures</th>
                                                <th>Carburant</th>
                                                <th>Entretien</th>
                                                <th>Immat.</th>
                                                <th>Assurance</th>
                                                <th>Intérêt prêt</th>
                                                <th>Location</th>
                                                <th>Amortissement</th>
                                                @foreach($colonnesPersonnalisees as $colonne)
                                                    <th>{{ $colonne['title'] ?? $colonne }}</th>
                                                @endforeach
                                                <th style="min-width: 200px">Total Dépenses Directes</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            @foreach($camions as $index => $camion)
                                                <tr>
                                                    <td><input type="text" name="unite[]" class="form-control form-control-sm" value="{{ $camion->unite }}"></td>
                                                    <td><input type="number" name="annee_de_construction[]" class="form-control form-control-sm" value="{{ $camion->annee_de_construction }}"></td>
                                                    <td><input type="text" name="no_plaque[]" class="form-control form-control-sm" value="{{ $camion->no_plaque }}"></td>
                                                    <td><input type="text" name="responsable[]" class="form-control form-control-sm" value="{{ $camion->responsable }}"></td>
                                                    <td><input type="text" name="marque[]" class="form-control form-control-sm" value="{{ $camion->marque }}"></td>
                                                    <td><input type="number" step="0.01" name="cout_km[]" class="form-control form-control-sm" value="{{ $camion->cout_km }}"></td>
                                                    <td><input type="number" step="0.01" name="km_parcourus[]" class="form-control form-control-sm" value="{{ $camion->km_parcourus }}"></td>
                                                    <td><input type="number" step="0.01" name="cout_hr[]" class="form-control form-control-sm" value="{{ $camion->cout_hr }}"></td>
                                                    <td><input type="number" step="0.01" name="heures[]" class="form-control form-control-sm" value="{{ $camion->heures }}"></td>
                                                    <td><input type="number" step="0.01" name="carburant[]" class="form-control form-control-sm" value="{{ $camion->carburant }}"></td>
                                                    <td><input type="number" step="0.01" name="entretien[]" class="form-control form-control-sm" value="{{ $camion->entretien }}"></td>
                                                    <td><input type="number" step="0.01" name="immatriculation[]" class="form-control form-control-sm" value="{{ $camion->immatriculation }}"></td>
                                                    <td><input type="number" step="0.01" name="assurance[]" class="form-control form-control-sm" value="{{ $camion->assurance }}"></td>
                                                    <td><input type="number" step="0.01" name="interet[]" class="form-control form-control-sm" value="{{ $camion->interet }}"></td>
                                                    <td><input type="number" step="0.01" name="location[]" class="form-control form-control-sm" value="{{ $camion->location }}"></td>
                                                    <td><input type="number" step="0.01" name="amortissement[]" class="form-control form-control-sm" value="{{ $camion->amortissement }}"></td>
                                                    @foreach($colonnesPersonnalisees as $colonne)
                                                        @php
                                                            $slug = is_array($colonne) ? $colonne['slug'] : $colonne;
                                                            $value = $camion->colonnes_personnalisees[$slug] ?? '';
                                                        @endphp
                                                        <td><input type="number" step="0.01" name="personnalise[{{ $slug }}][]" class="form-control form-control-sm" value="{{ $value }}"></td>
                                                    @endforeach
                                                    <td><input type="number" step="0.01" name="total_depenses[]" class="form-control form-control-sm" value="{{ $camion->total_depenses }}" readonly></td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <button type="submit" class="btn btn-sm rounded-circle bg-white d-flex align-items-center justify-content-center border-0" title="Sauvegarder">
                                                                <i class="fas fa-check" style="font-size: 14px; color:#5A55FF;"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm rounded-circle bg-white d-flex align-items-center justify-content-center border-0" title="Modifier">
                                                                <i class="fas fa-edit" style="font-size: 14px; color:#5A55FF;"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm rounded-circle bg-white d-flex align-items-center justify-content-center border-0" title="Supprimer" onclick="deleteRow(this)">
                                                                <i class="fas fa-trash" style="font-size: 14px; color:#5A55FF;"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr id="summaryRow" class="bg-primary text-white font-weight-bold">
                                                <!-- Les totaux seront calculés par JavaScript -->
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialisation des variables
const existingCamions = @json($camions ?? []);
const existingPersonnalisees = @json($colonnesPersonnalisees ?? []);
let customColumns = existingPersonnalisees.map(c => typeof c === 'object' ? c.slug : c);
const columnTitles = Object.fromEntries((existingPersonnalisees || []).map(c => [c.slug || c, c.title || c]));

// Fonction pour créer une nouvelle ligne
function createRow(data = {}) {
    const tbody = document.getElementById("tableBody");
    const tr = document.createElement("tr");
    
    // Colonnes fixes
    const fixedColumns = [
        {name: "unite", type: "text"},
        {name: "annee_de_construction", type: "number"},
        {name: "no_plaque", type: "text"},
        {name: "responsable", type: "text"},
        {name: "marque", type: "text"},
        {name: "cout_km", type: "number"},
        {name: "km_parcourus", type: "number"},
        {name: "cout_hr", type: "number"},
        {name: "heures", type: "number"},
        {name: "carburant", type: "number"},
        {name: "entretien", type: "number"},
        {name: "immatriculation", type: "number"},
        {name: "assurance", type: "number"},
        {name: "interet", type: "number"},
        {name: "location", type: "number"},
        {name: "amortissement", type: "number"}
    ];

    fixedColumns.forEach(col => {
        const td = document.createElement("td");
        const input = document.createElement("input");
        input.type = col.type;
        input.className = "form-control form-control-sm";
        input.name = `${col.name}[]`;
        input.value = data[col.name] || "";
        if (col.type === "number") input.step = "0.01";
        input.addEventListener("input", updateRowTotals);
        td.appendChild(input);
        tr.appendChild(td);
    });

    // Colonnes personnalisées
    customColumns.forEach(col => {
        const td = document.createElement("td");
        const input = document.createElement("input");
        input.type = "number";
        input.className = "form-control form-control-sm";
        input.name = `personnalise[${col}][]`;
        input.value = data.colonnes_personnalisees?.[col] || "";
        input.step = "0.01";
        input.addEventListener("input", updateRowTotals);
        td.appendChild(input);
        tr.appendChild(td);
    });

    // Total dépenses
    const totalTd = document.createElement("td");
    const totalInput = document.createElement("input");
    totalInput.type = "number";
    totalInput.className = "form-control form-control-sm";
    totalInput.name = "total_depenses[]";
    totalInput.value = data.total_depenses || "0.00";
    totalInput.readOnly = true;
    totalTd.appendChild(totalInput);
    tr.appendChild(totalTd);

    // Actions
    const actionsTd = document.createElement("td");
    actionsTd.className = "text-center";
    actionsTd.innerHTML = `
        <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-sm rounded-circle bg-white d-flex align-items-center justify-content-center border-0" title="Sauvegarder">
                <i class="fas fa-check" style="font-size: 14px; color:#5A55FF;"></i>
            </button>
            <button type="button" class="btn btn-sm rounded-circle bg-white d-flex align-items-center justify-content-center border-0" title="Modifier">
                <i class="fas fa-edit" style="font-size: 14px; color:#5A55FF;"></i>
            </button>
            <button type="button" class="btn btn-sm rounded-circle bg-white d-flex align-items-center justify-content-center border-0" title="Supprimer" onclick="deleteRow(this)">
                <i class="fas fa-trash" style="font-size: 14px; color:#5A55FF;"></i>
            </button>
        </div>
    `;
    tr.appendChild(actionsTd);

    tbody.appendChild(tr);
    updateRowTotals();
}

// Fonction pour supprimer une ligne
function deleteRow(button) {
    if (confirm("Voulez-vous vraiment supprimer cette ligne ?")) {
        const tr = button.closest("tr");
        tr.remove();
        updateSummary();
    }
}

// Fonction pour mettre à jour les totaux de chaque ligne
function updateRowTotals() {
    document.querySelectorAll("#tableBody tr").forEach(row => {
        let total = 0;

        // Colonnes de coût fixes
        const costColumns = ['carburant', 'entretien', 'immatriculation', 'assurance', 'interet', 'location', 'amortissement'];
        costColumns.forEach(col => {
            const input = row.querySelector(`input[name="${col}[]"]`);
            if (input) {
                total += parseFloat(input.value) || 0;
            }
        });

        // Colonnes personnalisées
        row.querySelectorAll("input[name^='personnalise[']").forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        // Mettre à jour le total
        const totalInput = row.querySelector("input[name='total_depenses[]']");
        if (totalInput) {
            totalInput.value = total.toFixed(2);
        }

        // Calculer coût/km et coût/heure
        const kmInput = row.querySelector("input[name='km_parcourus[]']");
        const heuresInput = row.querySelector("input[name='heures[]']");
        const coutKmInput = row.querySelector("input[name='cout_km[]']");
        const coutHrInput = row.querySelector("input[name='cout_hr[]']");

        const km = parseFloat(kmInput?.value) || 0;
        const heures = parseFloat(heuresInput?.value) || 0;

        if (coutKmInput) coutKmInput.value = km > 0 ? (total / km).toFixed(2) : "0.00";
        if (coutHrInput) coutHrInput.value = heures > 0 ? (total / heures).toFixed(2) : "0.00";
    });
}

// Fonction pour mettre à jour le résumé
function updateSummary() {
    updateRowTotals();
    
    const summary = {};
    const tfoot = document.getElementById("summaryRow");
    tfoot.innerHTML = "";

    // Ajouter les colonnes fixes
    const fixedColumns = [
        {name: "unite", isTotal: true},
        {name: "annee_de_construction"},
        {name: "no_plaque"},
        {name: "responsable"},
        {name: "marque"},
        {name: "cout_km", isCalc: true},
        {name: "km_parcourus", isSum: true},
        {name: "cout_hr", isCalc: true},
        {name: "heures", isSum: true},
        {name: "carburant", isSum: true},
        {name: "entretien", isSum: true},
        {name: "immatriculation", isSum: true},
        {name: "assurance", isSum: true},
        {name: "interet", isSum: true},
        {name: "location", isSum: true},
        {name: "amortissement", isSum: true}
    ];

    fixedColumns.forEach(col => {
        const td = document.createElement("td");
        
        if (col.isTotal) {
            td.textContent = "Total";
        } else if (col.isCalc) {
            // Calcul spécial pour cout_km et cout_hr
            const kmTotal = sumColumn("km_parcourus[]");
            const heuresTotal = sumColumn("heures[]");
            const totalDepenses = sumColumn("total_depenses[]");
            
            if (col.name === "cout_km") {
                td.textContent = kmTotal > 0 ? (totalDepenses / kmTotal).toFixed(2) : "0.00";
            } else {
                td.textContent = heuresTotal > 0 ? (totalDepenses / heuresTotal).toFixed(2) : "0.00";
            }
            td.classList.add("bg-info", "text-white");
        } else if (col.isSum) {
            const sum = sumColumn(`${col.name}[]`);
            td.textContent = sum.toFixed(2);
        } else {
            td.textContent = "";
        }
        
        tfoot.appendChild(td);
    });

    // Colonnes personnalisées
    customColumns.forEach(col => {
        const sum = sumColumn(`personnalise[${col}][]`);
        const td = document.createElement("td");
        td.textContent = sum.toFixed(2);
        tfoot.appendChild(td);
    });

    // Total dépenses
    const totalDepenses = sumColumn("total_depenses[]");
    const totalTd = document.createElement("td");
    totalTd.textContent = totalDepenses.toFixed(2);
    totalTd.classList.add("bg-info", "text-white");
    tfoot.appendChild(totalTd);

    // Actions (vide)
    tfoot.appendChild(document.createElement("td"));
}

// Fonction utilitaire pour faire la somme d'une colonne
function sumColumn(name) {
    let sum = 0;
    document.querySelectorAll(`input[name="${name}"]`).forEach(input => {
        sum += parseFloat(input.value) || 0;
    });
    return sum;
}

// Ajouter une colonne personnalisée
document.getElementById("addColumn").addEventListener("click", () => {
    const title = prompt("Entrez le titre de la nouvelle colonne:");
    if (!title) return;

    const slug = `col_${Date.now()}`;
    const columnData = { slug, title };
    customColumns.push(columnData);

    // Ajouter l'en-tête
    const th = document.createElement("th");
    th.textContent = title;
    th.dataset.slug = slug;
    
    const header = document.getElementById("tableHead");
    const totalTh = header.querySelector("th:nth-last-child(2)");
    header.insertBefore(th, totalTh);

    // Ajouter aux lignes existantes
    document.querySelectorAll("#tableBody tr").forEach(tr => {
        const td = document.createElement("td");
        const input = document.createElement("input");
        input.type = "number";
        input.className = "form-control form-control-sm";
        input.name = `personnalise[${slug}][]`;
        input.step = "0.01";
        input.addEventListener("input", updateRowTotals);
        td.appendChild(input);
        tr.insertBefore(td, tr.querySelector("td:nth-last-child(2)"));
    });

    // Ajouter au pied de tableau
    const tfoot = document.getElementById("summaryRow");
    const td = document.createElement("td");
    td.textContent = "0.00";
    tfoot.insertBefore(td, tfoot.querySelector("td:nth-last-child(2)"));

    updateSummary();
});

// Ajouter une nouvelle ligne
document.getElementById("addRow").addEventListener("click", () => createRow());

// Initialisation au chargement
document.addEventListener("DOMContentLoaded", () => {
    if (existingCamions.length === 0) {
        createRow();
    }
    updateSummary();
});

// Gestion de la soumission du formulaire
$('#camionForm').on('submit', function(e) {
    e.preventDefault();
    
    let form = $(this);
    let submitBtn = form.find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');

    $.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: form.serialize(),
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                setTimeout(() => window.location.reload(), 1500);
            } else {
                toastr.error(response.message || "Erreur lors de l'enregistrement");
            }
        },
        error: function(xhr) {
            let errorMsg = xhr.responseJSON?.message || "Erreur serveur";
            toastr.error(errorMsg);
        },
        complete: function() {
            submitBtn.prop('disabled', false).html('<i class="fas fa-check"></i> Sauvegarder');
        }
    });
});
</script>
@endsection