<?php

namespace App\Http\Controllers\GF;

use App\Http\Controllers\Controller;
use App\Models\GF\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function index()
    {
        try {
            $clients = Client::all();
            return response()->json([
                'status' => 'success',
                'data' => $clients
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request)
    {
        {
            try {

                $validatedData = $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:gf_clients,email',
                    'phone' => 'nullable|string|max:20',
                    'company_name' => 'nullable|string|max:255',
                    'address' => 'nullable|string|max:255',
                    'city' => 'nullable|string|max:100',
                    'postal_code' => 'nullable|string|max:20',
                    'country' => 'nullable|string|max:100',
                    'notes' => 'nullable|string',
                ]);

                $client = Client::create($validatedData);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Client created successfully',
                    'data' => $client
                ], 201);

            } catch (ValidationException $e) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $e->validator->errors()
                ], 422);

            } catch (\Exception $e) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred',
                    'details' => $e->getMessage()
                ], 500);
            }
        }

    }


    public function show($id)
    {
        try {

            $client = Client::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $client
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:gf_clients,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'company_name' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'country' => 'nullable|string|max:100',
                'notes' => 'nullable|string',
            ]);


            $client = Client::findOrFail($id);

            $client->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Client updated successfully',
                'data' => $client
            ], 200);

        } catch (ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->validator->errors()
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the client',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);

            $client->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Client deleted successfully',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the client',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
