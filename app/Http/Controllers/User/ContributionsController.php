<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contribution;
use Illuminate\Support\Facades\Auth;

class ContributionsController extends Controller
{
    /**
     * ✅ Affiche la liste des contributions (CSST entreprise + constantes globales)
     */
    public function index()
    {
        // 🔹 Récupérer l'entreprise associée à l'utilisateur
        $company = Auth::user()->companies()->first();

        if (!$company) {
            return redirect()->route('user.dashboard')->with('error', 'Aucune entreprise associée à cet utilisateur.');
        }

        // 🔹 Contribution CSST spécifique à l'entreprise
        $csstContribution = Contribution::where('company_id', $company->id)
                                        ->whereNotNull('csst_rate')
                                        ->orderByDesc('year')
                                        ->first();

        // 🔹 Contribution générale (constantes) sans company_id
        $generalContributions = Contribution::whereNull('company_id')
                                    ->orderByDesc('year')
                                    ->get();

        if (!$generalContributions) {
            return redirect()->route('user.dashboard')->with('error', 'Les constantes de contribution ne sont pas définies.');
        }

    return view('user.fardeauMO.contributions.index', compact('csstContribution', 'generalContributions'));    
    }

    /**
     * ✅ Affiche les détails d'une contribution (seulement si elle appartient à la même entreprise)
     */
    public function show(Contribution $contribution)
    {
        $company = Auth::user()->companies()->first();

        if (!$company || $contribution->company_id !== $company->id) {
            return redirect()->route('user.fardeauMO.contributions.index')->with('error', 'Accès interdit.');
        }

        return view('user.fardeauMO.contributions.show', compact('contribution'));
    }

    /**
     * ✅ Affiche le formulaire de création d'une nouvelle contribution
     */
    public function create()
    {
        return view('user.fardeauMO.contributions.create');
    }

    /**
     * ✅ Enregistre une contribution CSST en remplaçant l'ancienne pour la même entreprise et la même année
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'year' => 'required|integer',
            'csst_rate' => 'required|numeric|min:0|max:100'
        ]);

        $company = Auth::user()->companies()->first();

        if (!$company) {
            return redirect()->route('user.fardeauMO.contributions.index')->with('error', 'Aucune entreprise associée à cet utilisateur.');
        }

        $contribution = Contribution::where('company_id', $company->id)
                                    ->where('year', $request->year)
                                    ->first();

        if ($contribution) {
            $contribution->update(['csst_rate' => $request->csst_rate]);
        } else {
            Contribution::create([
                'year' => $request->year,
                'company_id' => $company->id,
                'csst_rate' => $request->csst_rate
            ]);
        }

        return redirect()->route('user.fardeauMO.contributions.index')->with('success', 'Contribution CSST mise à jour avec succès.');
    }

    /**
     * ✅ Affiche le formulaire de modification d'une contribution existante
     */
    public function edit(Contribution $contribution)
    {
        $company = Auth::user()->companies()->first();

        if (!$company || $contribution->company_id !== $company->id) {
            return redirect()->route('user.fardeauMO.contributions.index')->with('error', 'Vous ne pouvez pas modifier cette contribution.');
        }

        return view('user.fardeauMO.contributions.edit', compact('contribution'));
    }

    /**
     * ✅ Met à jour une contribution existante
     */
    public function update(Request $request, Contribution $contribution)
    {
        $company = Auth::user()->companies()->first();

        if (!$company || $contribution->company_id !== $company->id) {
            return redirect()->route('user.fardeauMO.contributions.index')->with('error', 'Vous ne pouvez pas modifier cette contribution.');
        }

        $validatedData = $request->validate([
            'csst_rate' => 'required|numeric|min:0|max:100',
        ]);

        $contribution->update($validatedData);

        return redirect()->route('user.fardeauMO.contributions.index')->with('success', 'Contribution CSST mise à jour avec succès.');
    }
}
