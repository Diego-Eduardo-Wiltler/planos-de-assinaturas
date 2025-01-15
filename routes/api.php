<?php

use App\Http\Controllers\Api\PlanoController;
use App\Http\Resources\PlanoResource;
use App\Models\Plano;
use Illuminate\Support\Facades\Route;

// Obter todos os planos
Route::get('/planos', [PlanoController::class, 'getTodosPlanos']);

// Obter todos os planos com seus produtos
Route::get('/planos/produtos', [PlanoController::class, 'getPlanoProdutos']);

// Associar produto a um plano
Route::post('/planos/{planoID}/produtos/{produtoID}', [PlanoController::class, 'postAssociarProduto']);

// Desassociar produto a um plano
Route::delete('/planos/{planoID}/produtos/{produtoID}', [PlanoController::class, 'deleteDesassociarProduto']);
