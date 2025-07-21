<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function select(Request $request)
    {

        // Get the companies that the manager is associated with
        $companies = Auth::user()->companies;

        return view('user.company.select', compact('companies'));
    }

    public function setCompany(Company $company)
    {
        // Store the selected company in the session
        session(['company_id' => $company->id]);

        // Redirect to the manager dashboard or another page as needed
        return redirect()->route('user.dashboard');
    }

    public function changeCompany(Request $request)
    {
        // Validate the company ID
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        // Store the selected company in the session
        session(['company_id' => $request->company_id]);

        // Redirect to the manager dashboard or another page as needed
        return redirect()->route('user.dashboard');
    }
}
