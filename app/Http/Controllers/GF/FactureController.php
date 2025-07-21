<?php

namespace App\Http\Controllers\GF;

use App\Http\Controllers\Controller;
use App\Models\GF\Facture;
use Illuminate\Http\Request;

class FactureController extends Controller
{

    public function index()
    {
        return [
            'data' => 'get all factures',
        ];

    }


    public function store(Request $request)
    {
        return [
            'data' => 'create facture',
        ];

    }


    public function show(Facture $Facture)
    {
        return [
            'data' => 'get facture',
        ];

    }


    public function update(Request $request, Facture $Facture)
    {
        return [
            'data' => 'update facture',
        ];

    }


    public function destroy(Facture $Facture)
    {
        return [
            'data' => 'delete facture',
        ];

    }
}
