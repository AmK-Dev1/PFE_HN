<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OperationType;
use Illuminate\Support\Facades\Auth;

class OperationTypeController extends Controller
{
    public function index()
    {
        $company = Auth::user()->companies()->first();
        $operationTypes = OperationType::where('company_id', $company->id)->get();

        return view('user.fardeauMO.operation_types.index', compact('operationTypes'));
    }

    public function create()
    {
        return view('user.fardeauMO.operation_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $company = Auth::user()->companies()->first();

        OperationType::create([
            'name' => $request->name,
            'company_id' => $company->id,
        ]);

        return redirect()->route('user.fardeauMO.operation_types.index')->with('success', 'Type d’opération ajouté avec succès.');
    }

    public function show($id)
    {
        $operationType = OperationType::find($id);
        return view('user.fardeauMO.operations.show', compact('operationType'));
    }


    public function edit($id)
    {
        $operationType = OperationType::findOrFail($id);

        return view('user.fardeauMO.operation_types.edit', compact('operationType'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $operationType = OperationType::findOrFail($id);
        $operationType->update(['name' => $request->name]);

        return redirect()->route('user.fardeauMO.operation_types.index')->with('success', 'Type d’opération mis à jour.');
    }

    public function destroy($id)
    {
        OperationType::findOrFail($id)->delete();

        return redirect()->route('user.fardeauMO.operation_types.index')->with('success', 'Type d’opération supprimé.');
    }
}
