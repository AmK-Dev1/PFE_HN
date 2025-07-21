<?php

namespace App\Http\Controllers\GF;

use App\Http\Controllers\Controller;
use App\Models\GF\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function index()
    {
        try {

            $articles = Article::all();
            return response()->json([
                'status' => 'success',
                'data' => $articles
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
        try {

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'unit_cost' => 'required|numeric|min:0',
                'type' => 'required|in:maindoeuvre,transport,soutraitance,material',
                'reference' => 'required|string|max:100|unique:gf_articles,reference',
                'unit_of_measure' => 'nullable|string|max:50'
            ]);


            $article = Article::create($validatedData);


            return response()->json([
                'status' => 'success',
                'message' => 'Article created successfully',
                'data' => $article
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


    public function show($id)
    {
        try {

            $article = Article::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $article
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
                'description' => 'nullable|string',
                'unit_cost' => 'required|numeric|min:0',
                'type' => 'required|in:maindoeuvre,transport,soutraitance,material',
                'reference' => 'required|string|max:100|unique:gf_articles,reference,' . $id,
                'unit_of_measure' => 'nullable|string|max:50'
            ]);


            $article = Article::findOrFail($id);


            $article->update($validatedData);


            return response()->json([
                'status' => 'success',
                'message' => 'Article updated successfully',
                'data' => $article
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
                'message' => 'An error occurred while updating the article',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {

            $article = Article::findOrFail($id);

            $article->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Article deleted successfully',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the article',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
