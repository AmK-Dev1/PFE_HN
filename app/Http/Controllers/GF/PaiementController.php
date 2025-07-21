<?php

namespace App\Http\Controllers\GF;

use App\Http\Controllers\Controller;
use App\Models\GF\Paiement;
use Illuminate\Http\Request;

class PaiementController extends Controller
{

    public function index()
    {
        return [
            'data' => 'get paiements',
        ];
    }


    public function store(Request $request)
    {
        return [
            'data' => 'create paiement',
        ];
    }


    public function show(Paiement $Paiement)
    {
        return [
            'data' => 'get paiement',
        ];
    }


    public function update(Request $request, Paiement $Paiement)
    {
        return [
            'data' => 'update paiement',
        ];
    }


    public function destroy(Paiement $gfPaiement)
    {
        return [
            'data' => 'delete paiement',
        ];
    }
}
