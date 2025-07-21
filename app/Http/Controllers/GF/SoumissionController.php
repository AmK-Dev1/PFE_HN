<?php

namespace App\Http\Controllers\GF;

use App\Http\Controllers\Controller;
use App\Models\GF\Soumission;
use Illuminate\Http\Request;

class SoumissionController extends Controller
{

    public function index()
    {
        return [
            'data' => 'get All Soumission',
        ];
    }


    public function store(Request $request)
    {
        return [
            'data' => 'Create Soumission',
        ];
    }


    public function show(Soumission $Soumission)
    {
        return [
            'data' => 'get Soumission',
        ];
    }


    public function update(Request $request, Soumission $Soumission)
    {
        return [
            'data' => 'update Soumission',
        ];
    }


    public function destroy(Soumission $Soumission)
    {
        return [
            'data' => 'delete Soumission',
        ];

    }
}
