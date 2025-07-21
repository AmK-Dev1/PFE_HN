<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\EnteteActivite;
use Illuminate\Http\Request;

class EnteteActiviteController extends Controller
{
    public function index()
    {
        $entetes = EnteteActivite::all();
        return view('user.entetes.index', compact('entetes'));
    }

    public function create()
    {
        return view('user.entetes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'nullable|string|max:255',
            'annee' => 'nullable|integer',
            'nb_semaines' => 'required|integer|min:1|max:53',
            'nb_jours_feries' => 'required|integer|min:0|max:15',
            'pourcentage_pause' => 'required|numeric|min:0|max:1',
            'pourcentage_temps_mort' => 'required|numeric|min:0|max:1',
        ]);

        EnteteActivite::create($validated);

        return redirect()->route('user.entetes.index')->with('success', 'Entête créée avec succès.');
    }

    public function edit($id)
    {
        $entete = EnteteActivite::findOrFail($id);
        return view('user.entetes.edit', compact('entete'));
    }

    public function update(Request $request, $id)
    {
        $entete = EnteteActivite::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'nullable|string|max:255',
            'annee' => 'nullable|integer',
            'nb_semaines' => 'required|integer|min:1|max:53',
            'nb_jours_feries' => 'required|integer|min:0|max:15',
            'pourcentage_pause' => 'required|numeric|min:0|max:1',
            'pourcentage_temps_mort' => 'required|numeric|min:0|max:1',
        ]);

        $entete->update($validated);

        return redirect()->route('user.entetes.index')->with('success', 'Entête mise à jour.');
    }

    public function destroy($id)
    {
        $entete = EnteteActivite::findOrFail($id);
        $entete->delete();

        return redirect()->route('user.entetes.index')->with('success', 'Entête supprimée.');
    }
}