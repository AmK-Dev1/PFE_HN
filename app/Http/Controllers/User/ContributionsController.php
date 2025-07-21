<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contribution;
use Illuminate\Support\Facades\Auth;

class ContributionsController extends Controller
{
    /**
     * ✅ Affiche la liste des contributions CSST de l'entreprise de l'utilisateur
     */
    public function index()
{
    // 🔹 Récupérer l'entreprise associée à l'utilisateur
    $company = Auth::user()->companies()->first();

    if (!$company) {
        return redirect()->route('user.dashboard')->with('error', 'Aucune entreprise associée à cet utilisateur.');
    }

    // 🔹 Récupérer la contribution CSST spécifique à l'entreprise de l'utilisateur
    $csstContribution = Contribution::where('company_id', $company->id)
                                    ->whereNotNull('csst_rate')
                                    ->orderBy('year', 'desc')
                                    ->first();

    // 🔹 Récupérer les contributions générales ajoutées par l’admin SaaS
    $generalContributions = Contribution::whereNull('company_id')
                                        ->orderBy('year', 'desc')
                                        ->get();

    // 🔹 Debug : Afficher dans le terminal ou dans le log Laravel
    \Log::info('General Contributions:', $generalContributions->toArray());

    return view('user.fardeauMO.contributions.index', compact('csstContribution', 'generalContributions'));
}


    /**
     * ✅ Affiche les détails d'une contribution (seulement si elle appartient à la même entreprise)
     */
    public function show(Contribution $contribution)
    {
        // 🔹 Vérifier que l'utilisateur appartient à la même entreprise
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
    // ✅ Vérification des champs obligatoires
    $validatedData = $request->validate([
        'year' => 'required|integer',
        'csst_rate' => 'required|numeric|min:0|max:100'
    ]);

    // ✅ Récupérer l'entreprise associée à l'utilisateur
    $company = Auth::user()->companies()->first();

    if (!$company) {
        return redirect()->route('user.fardeauMO.contributions.index')->with('error', 'Aucune entreprise associée à cet utilisateur.');
    }

    // ✅ Vérifier s'il existe déjà une ligne pour cette entreprise et cette année
    $contribution = Contribution::where('company_id', $company->id)
                                ->where('year', $request->year)
                                ->first();

    if ($contribution) {
        // ✅ Effacer uniquement l'ancienne CSST et mettre à jour la ligne
        $contribution->update([
            'csst_rate' => $request->csst_rate
        ]);
    } else {
        // ✅ Si aucune contribution n'existe encore pour cette année et entreprise, en créer une
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
        // 🔹 Vérifier que l'utilisateur appartient à la même entreprise
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
        // 🔹 Vérifier que l'utilisateur appartient à la même entreprise
        $company = Auth::user()->companies()->first();

        if (!$company || $contribution->company_id !== $company->id) {
            return redirect()->route('user.fardeauMO.contributions.index')->with('error', 'Vous ne pouvez pas modifier cette contribution.');
        }

        // 🔹 Validation des champs obligatoires
        $validatedData = $request->validate([
            'csst_rate' => 'required|numeric|min:0|max:100',
        ]);

        // 🔹 Mise à jour de la contribution
        $contribution->update($validatedData);

        return redirect()->route('user.fardeauMO.contributions.index')
                         ->with('success', 'Contribution CSST mise à jour avec succès.');
    }
}
