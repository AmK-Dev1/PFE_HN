<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Imports\AmortissementsImport;
use App\Models\Amortissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AmortissementController extends Controller
{
    public function index()
    {
        $companyId = session('company_id') ?? Auth::user()->company_id;
        session(['company_id' => $companyId]);

        $years = Amortissement::where('company_id', $companyId)
            ->select('year')
            ->distinct()
            ->orderBy('year')
            ->get();

        return view('user.amortissements.index', compact('years'));
    }

   public function create(Request $request)
{
    $start = $request->query('start');
    $end = $request->query('end');

    $companyId = session('company_id') ?? (Auth::check() ? Auth::user()->company_id : null);
    if (!$companyId) {
        return redirect()->route('login')->with('error', 'Session expirée. Veuillez vous reconnecter.');
    }
    session(['company_id' => $companyId]);

    $dataByYear = [];

    for ($i = $start; $i <= $end; $i++) {
        $currentData = Amortissement::where('company_id', $companyId)
            ->where('year', $i)
            ->get();

        // ✅ Si l’année courante est vide mais que l’année précédente a des données
        if ($currentData->isEmpty() && $i > $start) {
            $previousYear = $i - 1;
            $previousData = Amortissement::where('company_id', $companyId)
                ->where('year', $previousYear)
                ->get();

            // On duplique les lignes pour préremplir
            $copied = $previousData->map(function ($item) use ($i) {
                return [
                    'poste' => $item->poste,
                    'cout' => $item->cout,
                    'amort_cumule_anterieur' => $item->amort_cumule_anterieur + $item->amortissement_annee,
                    'valeur_nette_anterieure' => ($item->cout - ($item->amort_cumule_anterieur + $item->amortissement_annee)),
                    'acquisition_annee' => 0,
                    'amortissement_annee' => 0,
                    'amortissement_mensuel' => 0,
                    'taux' => $item->taux,
                    'type_amortissement' => $item->type_amortissement,
                    'year' => $i,
                ];
            });

            $dataByYear[$i] = $copied->toArray();
        } else {
            $dataByYear[$i] = $currentData->toArray();
        }
    }

    return view('user.amortissements.create', compact('start', 'end', 'dataByYear'));
}


    public function store(Request $request)
    {
        $companyId = session('company_id') ?? Auth::user()->company_id;
        session(['company_id' => $companyId]);

        $data = $request->input('amortissements');
        $activeYear = $request->input('active_year');

        foreach ($data as $year => $lignes) {
            foreach ($lignes as $ligne) {
                if (empty(trim($ligne['poste'] ?? '')) || empty($ligne['taux'])) {
                    continue;
                }

                $poste = trim($ligne['poste']);
                $taux = (float) $ligne['taux'];
                $type_amortissement = $ligne['type_amortissement'];
                $acquisition_annee = isset($ligne['acquisition_annee']) ? (float) $ligne['acquisition_annee'] : 0;

                if ($year == min(array_keys($data))) {
                    $cout = (float) ($ligne['cout'] ?? 0);
                    $amort_cumule_anterieur = (float) ($ligne['amort_cumule_anterieur'] ?? 0);
                } else {
                    $prev = Amortissement::where('poste', $poste)
                        ->where('year', $year - 1)
                        ->where('company_id', $companyId)
                        ->first();

                    if (!$prev) continue;

                    $cout = $prev->cout;
                    $amort_cumule_anterieur = $prev->amort_cumule_anterieur + $prev->amortissement_annee;
                    $type_amortissement = $prev->type_amortissement;
                    $taux = $prev->taux;
                }

                $valeur_nette_anterieure = $cout - $amort_cumule_anterieur;

                if ($type_amortissement === 'L') {
                    $amortissement_annee = $cout * ($taux / 100) * 0.5;
                } elseif ($type_amortissement === 'D') {
                    $amortissement_annee = ($valeur_nette_anterieure + ($acquisition_annee / 2)) * ($taux / 100);
                } else {
                    $amortissement_annee = 0;
                }

                $amortissement_mensuel = $amortissement_annee / 12;

                Amortissement::updateOrCreate([
                    'company_id' => $companyId,
                    'poste' => $poste,
                    'year' => $year
                ], [
                    'cout' => $cout,
                    'amort_cumule_anterieur' => $amort_cumule_anterieur,
                    'valeur_nette_anterieure' => $valeur_nette_anterieure,
                    'acquisition_annee' => $acquisition_annee,
                    'amortissement_annee' => $amortissement_annee,
                    'amortissement_mensuel' => $amortissement_mensuel,
                    'taux' => $taux,
                    'type_amortissement' => $type_amortissement,
                ]);
            }
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('success', 'Amortissements enregistrés avec succès.')
            ->with('active_year', $activeYear);
    }

    public function import(Request $request, $year)
    {
        $import = new AmortissementsImport($year);
        Excel::import($import, $request->file('file'));
        $amortissements = $import->getImported();

        return view('user.amortissements.preview', compact('amortissements', 'year'));
    }

    public function storeImported(Request $request, $year)
    {
        $companyId = session('company_id') ?? Auth::user()->company_id;
        session(['company_id' => $companyId]);

        $amortissements = $request->input('amortissements');

        foreach ($amortissements as $ligne) {
            Amortissement::create(array_merge($ligne, [
                'company_id' => $companyId,
                'year' => $year,
            ]));
        }

        return redirect()->route('user.amortissements.index')->with('success', 'Importation enregistrée avec succès.');
    }

    public function showByYear($year)
    {
        $companyId = session('company_id') ?? Auth::user()->company_id;
        session(['company_id' => $companyId]);

        $amortissements = Amortissement::where('company_id', $companyId)
            ->where('year', $year)
            ->get();

        return view('user.amortissements.show', compact('amortissements', 'year'));
    }

    public function destroy($year)
    {
        $companyId = session('company_id') ?? Auth::user()->company_id;
        session(['company_id' => $companyId]);

        Amortissement::where('company_id', $companyId)
            ->where('year', $year)
            ->delete();

        return redirect()->route('user.amortissements.index')->with('success', 'Amortissements de l\'année supprimés.');
    }
}
