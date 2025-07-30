<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OperationType;



class DashboardController extends Controller
{
    public function index()
    {

        $company = Auth::user()->companies()->first();
        $operationTypes = OperationType::where('company_id', $company->id)->get();

        return view('user.dashboard', compact('operationTypes'));

    }
}
