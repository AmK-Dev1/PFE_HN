<?php

use Illuminate\Support\Facades\Route;

Route::get('/soumission', [\App\Http\Controllers\GF\SoumissionController::class, 'index']);
Route::post('/soumission', [\App\Http\Controllers\GF\SoumissionController::class, 'store']);
Route::get('/soumission/{id}', [\App\Http\Controllers\GF\SoumissionController::class, 'show']);
Route::put('/soumission/{id}', [\App\Http\Controllers\GF\SoumissionController::class, 'update']);
Route::delete('/soumission/{id}', [\App\Http\Controllers\GF\SoumissionController::class, 'destroy']);

Route::get('/article', [\App\Http\Controllers\GF\ArticleController::class, 'index']);
Route::post('/article', [\App\Http\Controllers\GF\ArticleController::class, 'store']);
Route::get('/article/{id}', [\App\Http\Controllers\GF\ArticleController::class, 'show']);
Route::put('/article/{id}', [\App\Http\Controllers\GF\ArticleController::class, 'update']);
Route::delete('/article/{id}', [\App\Http\Controllers\GF\ArticleController::class, 'destroy']);

Route::get('/client', [\App\Http\Controllers\GF\ClientController::class, 'index']);
Route::get('/client/{id}', [\App\Http\Controllers\GF\ClientController::class, 'show']);
Route::post('/client', [\App\Http\Controllers\GF\ClientController::class, 'store']);
Route::put('/client/{id}', [\App\Http\Controllers\GF\ClientController::class, 'update']);
Route::delete('/client/{id}', [\App\Http\Controllers\GF\ClientController::class, 'destroy']);

Route::get('/facture', [\App\Http\Controllers\GF\FactureController::class, 'index']);
Route::get('/facture/{id}', [\App\Http\Controllers\GF\FactureController::class, 'show']);
Route::post('/facture', [\App\Http\Controllers\GF\FactureController::class, 'store']);
Route::put('/facture/{id}', [\App\Http\Controllers\GF\FactureController::class, 'update']);
Route::delete('/facture/{id}', [\App\Http\Controllers\GF\FactureController::class, 'destroy']);

Route::get('/paiement', [\App\Http\Controllers\GF\PaiementController::class, 'index']);
Route::get('/paiement/{id}', [\App\Http\Controllers\GF\PaiementController::class, 'show']);
Route::post('/paiement', [\App\Http\Controllers\GF\PaiementController::class, 'store']);
Route::put('/paiement/{id}', [\App\Http\Controllers\GF\PaiementController::class, 'update']);
Route::delete('/paiement/{id}', [\App\Http\Controllers\GF\PaiementController::class, 'destroy']);

Route::get('/relance', [\App\Http\Controllers\GF\RelanceController::class, 'index']);
Route::get('/relance/{id}', [\App\Http\Controllers\GF\RelanceController::class, 'show']);
Route::post('/relance', [\App\Http\Controllers\GF\RelanceController::class, 'store']);
Route::put('/relance/{id}', [\App\Http\Controllers\GF\RelanceController::class, 'update']);
Route::delete('/relance/{id}', [\App\Http\Controllers\GF\RelanceController::class, 'destroy']);


