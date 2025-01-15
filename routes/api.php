<?php

use App\Http\Controllers\Api\PlanoController;
use Illuminate\Support\Facades\Route;

// Rota para obter todos os planos
Route::get('/planos', [PlanoController::class, 'getAllPlanos']);

// Rota para obter todos os planos com seus produtos
Route::get('/planos/produtos', [PlanoController::class, 'getAllPlanoProdutos']);
