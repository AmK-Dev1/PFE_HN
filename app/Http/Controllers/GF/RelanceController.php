<?php

namespace App\Http\Controllers\GF;

use App\Http\Controllers\Controller;
use App\Models\GF\Relance;
use Illuminate\Http\Request;

class RelanceController extends Controller
{

    public function index()
    {
        return [
            'data' => 'get relances',
        ];
    }


    public function store(Request $request)
    {
        return [
            'data' => 'create relance',
        ];
    }


    public function show(Relance $Relance)
    {
        return [
            'data' => 'get relance',
        ];
    }


    public function update(Request $request, Relance $Relance)
    {
        return [
            'data' => 'update relance',
        ];
    }


    public function destroy(Relance $Relance)
    {
        return [
            'data' => 'delete relance',
        ];
    }
}
