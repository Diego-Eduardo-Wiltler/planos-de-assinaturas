<?php

use App\Http\Controllers\Api\PlanoController;
use App\Http\Resources\PlanoResource;
use App\Models\Plano;
use Illuminate\Support\Facades\Route;

// Rota para obter todos os planos
Route::get('/planos', [PlanoController::class, 'getAllPlanos']);

// Rota para obter todos os planos com seus produtos
Route::get('/planos/produtos', [PlanoController::class, 'getAllPlanoProdutos']);
