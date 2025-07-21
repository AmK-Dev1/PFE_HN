<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subtype;
use App\Models\Type;
use Illuminate\Http\Request;

class SubtypeController extends Controller
{
    public function index(Request $request)
    {
        $subtypes = Subtype::with('type')->get(); // Fetch all subtypes with their related types
        if ($request->wantsJson()) {
            return response()->json(compact('subtypes'));
        }
        return view('admin.subtypes.index', compact('subtypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Type::all(); // Fetch all types for the dropdown
        return view('admin.subtypes.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type_id' => 'required|exists:types,id',
        ]);

        Subtype::create([
            'name' => $request->name,
            'type_id' => $request->type_id,
        ]);
        session()->flash('success', __('messages.flash.create'));
        return redirect()->route('admin.subtypes.index')->with('success', 'Subtype created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subtype $subtype)
    {
        $types = Type::all(); // Fetch all types for the dropdown
        return view('admin.subtypes.edit', compact('subtype', 'types'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subtype $subtype)
    {
        $request->validate([
            'name' => 'required',
            'type_id' => 'required|exists:types,id',
        ]);

        $subtype->update([
            'name' => $request->name,
            'type_id' => $request->type_id,
        ]);
        session()->flash('success', __('messages.flash.create'));
        return redirect()->route('admin.subtypes.index')->with('success', 'Subtype updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subtype $subtype)
    {
        $subtype->delete();
        session()->flash('success', __('messages.flash.delete'));
        return redirect()->route('admin.subtypes.index');
    }
}
