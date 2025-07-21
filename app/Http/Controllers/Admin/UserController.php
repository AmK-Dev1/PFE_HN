<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        {
            $users = User::all();
            if ($request->wantsJson()) {
                return response()->json(compact('users'));
            }
            return view('admin.users.index', compact('users'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'image'       => 'sometimes|nullable|file|image|max:3000', // Optional company description
            'roles'  => 'required|array',
            'roles.*'  => 'required|integer',
        ]);

       /* // Create the company
        $company = Company::create([
            'name' => $validated['company_name'],
            'description' => $validated['company_description'] ?? null,
        ]);*/

        // Create the user (manager)
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Check if the role 'Company Manager' exists, create it if not
        //$role = Role::firstOrCreate(['name' => 'Company Manager']);

        // Assign the 'Company Manager' role to the user
        foreach ($validated['roles'] as $role_id) {
            $role = Role::findById($role_id,'web');
            $user->assignRole($role);
        }

        // Attach the user to the newly created company
       // $user->companies()->attach($company->id);

        session()->flash('success',__('messages.flash.create'));
        return redirect()->route('admin.users.index')->with('success', 'User and company created successfully, and the user is assigned as a Company Manager.');

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $user->id,// Password is optional; only update if provided
        ]);


        $user->update($validated);

        session()->flash('success',__('messages.flash.create'));
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        session()->flash('success',__('messages.flash.delete'));
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
