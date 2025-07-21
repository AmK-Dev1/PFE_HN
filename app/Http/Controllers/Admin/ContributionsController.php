<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContributionsController extends Controller
{
    // ✅ Affiche la liste des contributions
    public function index()
    {
        $contributions = Contribution::with('company')->orderBy('year', 'desc')->get();
        return view('admin.contributions.index', compact('contributions'));
    }

    // ✅ Affiche le formulaire de création
    public function create()
    {
        return view('admin.contributions.create');
    }

    // ✅ Enregistre une nouvelle contribution en écrasant complètement l'ancienne du même type
    public function store(Request $request)
    {
        // 🔹 Validation des champs requis
        $validatedData = $request->validate([
            'year' => 'required|integer',
            'rrq_max_salary' => 'nullable|numeric',
            'rrq_exemption' => 'nullable|numeric',
            'taux_de_cotisation_rrq' => 'nullable|numeric',
          'rrq_max_contribution' => 'nullable|numeric',
            'ae_max_salary' => 'nullable|numeric',
            'ae_rate_employee' => 'nullable|numeric',
            'ae_rate_employer' => 'nullable|numeric',
            'rqap_max_salary' => 'nullable|numeric',
            'rqap_rate_employee' => 'nullable|numeric',
           
            'cnt_max_salary' => 'nullable|numeric',
            'cnt_rate' => 'nullable|numeric',
            'fss_rate' => 'nullable|numeric',
        ]);

        // 🔹 Détecter le type de contribution ajouté
        $type = null;
        if ($request->filled('rrq_max_salary')) $type = 'RRQ';
        elseif ($request->filled('ae_max_salary')) $type = 'AE';
        elseif ($request->filled('rqap_max_salary')) $type = 'RQAP';
        elseif ($request->filled('cnt_max_salary')) $type = 'CNT';
        elseif ($request->filled('fss_rate')) $type = 'FSS';

        if (!$type) {
            return redirect()->route('admin.contributions.index')->with('error', 'Aucune contribution valide détectée.');
        }

        // 🔹 Supprimer **TOUTES** les anciennes contributions du même type (quel que soit l'année)
        Contribution::whereNotNull(strtolower($type) . '_max_salary')->delete();

        // 🔹 Calculs automatiques avant enregistrement
        $validatedData['rrq_max_gains'] = ($request->rrq_max_salary ?? 0) - ($request->rrq_exemption ?? 0);
        $validatedData['rrq_hourly_exemption'] = ($request->rrq_exemption ?? 0) / 2080;
        $validatedData['rrq_hourly_contribution'] = ($validatedData['rrq_max_contribution'] ?? 0) / 2080;

        $validatedData['ae_max_employee'] = ($request->ae_max_salary ?? 0) * (($request->ae_rate_employee ?? 0) / 100);
        $validatedData['ae_max_employer'] = ($validatedData['ae_max_employee'] ?? 0) * (($request->ae_rate_employer ?? 0) / 100);
        $validatedData['ae_hourly_contribution'] = ($validatedData['ae_max_employer'] ?? 0) / 2080;

        $validatedData['rqap_max_contribution'] = ($request->rqap_max_salary ?? 0) * (($request->rqap_rate_employee ?? 0) / 100);
        $validatedData['rqap_hourly_contribution'] = ($validatedData['rqap_max_contribution'] ?? 0) / 2080;

        $validatedData['cnt_max_contribution'] = ($request->cnt_max_salary ?? 0) * (($request->cnt_rate ?? 0) / 100);
        $validatedData['cnt_hourly_contribution'] = ($validatedData['cnt_max_contribution'] ?? 0) / 2080;

        // 🔹 Création de la nouvelle contribution
        Contribution::create(array_merge($validatedData, ['company_id' => null]));

        return redirect()->route('admin.contributions.index')->with('success', 'Contribution mise à jour avec succès.');
    }
// 🔹 Modifier une contribution (EDIT)
public function edit(Contribution $contribution)
{
    return view('admin.contributions.edit', compact('contribution'));
}

// 🔹 Mettre à jour une contribution (UPDATE)
public function update(Request $request, Contribution $contribution)
{
    // ✅ Même validation que `store`
    $validatedData = $request->validate([
        'year' => 'required|integer',

        'rrq_max_salary' => 'nullable|numeric',
            'rrq_exemption' => 'nullable|numeric',
            'taux_de_cotisation_rrq' => 'nullable|numeric',
          'rrq_max_contribution' => 'nullable|numeric',
      
        'ae_max_salary' => 'nullable|numeric',
        'ae_rate_employee' => 'nullable|numeric',
        'ae_rate_employer' => 'nullable|numeric',

        'rqap_max_salary' => 'nullable|numeric',
        'rqap_rate_employee' => 'nullable|numeric',
        

        'cnt_max_salary' => 'nullable|numeric',
        'cnt_rate' => 'nullable|numeric',

        'fss_rate' => 'nullable|numeric',
    ]);

    // ✅ Mise à jour uniquement des champs envoyés
    foreach ($validatedData as $key => $value) {
        if (!is_null($value) && $value !== '') {
            $contribution->$key = $value;
        }
    }
    $contribution->save();

    return redirect()->route('admin.contributions.index')->with('success', 'Contribution mise à jour avec succès');
}
    // ✅ Supprime uniquement un type de contribution sans supprimer toute la ligne
    public function destroy(Request $request, $id)
    {
        // 🔹 Vérifier le type de contribution à supprimer
        $type = null;
        if ($request->input('delete_rrq')) $type = 'RRQ';
        elseif ($request->input('delete_ae')) $type = 'AE';
        elseif ($request->input('delete_rqap')) $type = 'RQAP';
        elseif ($request->input('delete_cnt')) $type = 'CNT';
        elseif ($request->input('delete_fss')) $type = 'FSS';

        if (!$type) {
            return redirect()->route('admin.contributions.index')->with('error', 'Aucune contribution sélectionnée.');
        }

        // 🔹 Récupérer la contribution existante
        $contribution = Contribution::where('id', $id)->first();

        if (!$contribution) {
            return redirect()->route('admin.contributions.index')->with('error', 'La contribution n\'existe pas.');
        }

        // 🔹 Liste des champs à mettre à NULL
        $fieldsToReset = [
            'RRQ' => ['rrq_max_salary', 'rrq_exemption', 'taux_de_cotisation_rrq', 'rrq_max_contribution', 'rrq_max_gains', 'rrq_hourly_exemption', 'rrq_hourly_contribution'],
            'AE' => ['ae_max_salary', 'ae_rate_employee', 'ae_rate_employer', 'ae_max_employee', 'ae_max_employer', 'ae_hourly_contribution'],
            'RQAP' => ['rqap_max_salary', 'rqap_rate_employee', 'rqap_max_contribution', 'rqap_hourly_contribution'],
            'CNT' => ['cnt_max_salary', 'cnt_rate', 'cnt_max_contribution', 'cnt_hourly_contribution'],
            'FSS' => ['fss_rate']
        ];

        if (isset($fieldsToReset[$type])) {
            foreach ($fieldsToReset[$type] as $field) {
                $contribution->$field = null; // 🔹 Met à NULL uniquement les champs du type sélectionné
            }
            $contribution->save(); // 🔹 Mise à jour dans la base de données
        }

        return redirect()->route('admin.contributions.index')->with('success', ucfirst($type) . ' supprimé avec succès.');
    }
}
