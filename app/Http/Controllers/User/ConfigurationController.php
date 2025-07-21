<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = Session::get('company_id');
        $configurations = Configuration::where('company_id', $companyId)->with('type')->get();

        return view('user.configurations.index', compact('configurations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Type::all();
        return view('user.configurations.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $companyId = Session::get('company_id');

        foreach ($request->types as $type_id => $code_range) {
            // Check if a configuration for this type and company already exists
            $exists = Configuration::where('company_id', $companyId)
                ->where('type_id', $type_id)
                ->exists();
            if ($exists) {
                return back()->withErrors("Configuration for type ID {$type_id} already exists.");
            }

            // Create configuration for each type
            Configuration::create([
                'company_id' => $companyId,
                'type_id' => $type_id,
                'code_start' => $code_range['start'],
                'code_end' => $code_range['end'],
            ]);
        }

        return redirect()->route('user.configurations.index')
            ->with('success', 'Configuration(s) created successfully.');
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
    public function edit(string $id)
    {
        $companyId = Session::get('company_id');
        $configuration = Configuration::where('id', $id)
            ->where('company_id', $companyId)
            ->firstOrFail();
        $types = Type::all();

        return view('user.configurations.edit', compact('configuration', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $companyId = Session::get('company_id');
        $configuration = Configuration::where('id', $id)
            ->where('company_id', $companyId)
            ->firstOrFail();

        $request->validate([
            'type_id' => 'required|exists:types,id',
            'code_range_start' => 'required|integer',
            'code_range_end' => 'required|integer',
        ]);

        // Ensure no duplicate configuration exists for the same type and company
        $exists = Configuration::where('company_id', $companyId)
            ->where('type_id', $request->type_id)
            ->where('id', '!=', $id)
            ->exists();
        if ($exists) {
            return back()->withErrors("Configuration for this type already exists.");
        }

        $configuration->update([
            'type_id' => $request->type_id,
            'code_range_start' => $request->code_range_start,
            'code_range_end' => $request->code_range_end,
        ]);

        return redirect()->route('user.configurations.index')
            ->with('success', 'Configuration updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $companyId = Session::get('company_id');
        $configuration = Configuration::where('id', $id)
            ->where('company_id', $companyId)
            ->firstOrFail();

        $configuration->delete();

        return redirect()->route('user.configurations.index')
            ->with('success', 'Configuration deleted successfully.');
    }
}
