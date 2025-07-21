<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::all();
        return view('admin.admins.index', compact('admins'));
    }

    public function create(): Renderable
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'sometimes|nullable|file|image|max:3000',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        Admin::create($validated);

        session()->flash('success',__('messages.flash.create'));
        return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin): Renderable
    {
        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,// Password is optional; only update if provided
        ]);


        $admin->update($validated);

        session()->flash('success',__('messages.flash.create'));
        return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function editPassword(Admin $admin){
        return view('admin.admins.edit-password', compact('admin'));
    }

    public function updatePassword(Admin $admin,Request $request): RedirectResponse
    {
        $data = $request->validate([
            'password' => 'required|string|max:24|min:8|confirmed'
        ]);

        $data['password'] = bcrypt($data['password']);

        $admin->update($data);

        session()->flash('success',__('messages.flash.update'));
        return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully.');
    }
}
