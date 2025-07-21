<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();
        return view('admin.companies.index', compact('companies'));
    }

    public function searchUsers(Request $request)
    {
        $query = $request->get('query');

        if ($query) {
            // Search for users by name or other criteria
            $users = User::where('name', 'LIKE', '%' . $query . '%')
                ->orWhere('email', 'LIKE', '%' . $query . '%')
                ->take(10)
                ->get();
        } else {
            // Fetch the first 10 users by default
            $users = User::take(10)->get();
        }

        return view('admin.companies.partials.user_list', compact('users'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all(); // Fetch all users from the database
        return view('admin.companies.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'users'  => 'nullable|array',
        ]);


        $company = Company::create($validated);

        if (!empty($validated['users'])) {
            $company->users()->attach($validated['users']);
        }

        session()->flash('success',__('messages.flash.create'));
        return redirect()->route('admin.companies.index')->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Load company with its users through the many-to-many relationship
        $company = Company::with('users')->findOrFail($id);
        return view('admin.companies.show', compact('company', ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255' . $company->id,// Password is optional; only update if provided
        ]);


        $company->update($validated);

        session()->flash('success',__('messages.flash.create'));
        return redirect()->route('admin.companies.index')->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        session()->flash('success', __('messages.flash.delete'));
        return redirect()->route('admin.companies.index');
    }
}
