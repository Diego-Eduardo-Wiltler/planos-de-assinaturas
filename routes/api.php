<?php

use App\Http\Controllers\Api\PlanoController;
use App\Http\Resources\PlanoResource;
use App\Models\Plano;
use Illuminate\Support\Facades\Route;

// Obter todos os planos
Route::get('/planos', [PlanoController::class, 'getTodosPlanos']);

// Obter plano por id
Route::get('planos/{planoId}', [PlanoController::class, 'getPorIdPlanos']);

// Obter todos os planos com seus produtos
Route::get('/planos/produtos', [PlanoController::class, 'getPlanoProdutos']);

// Criar um novo plano
Route::post('/planos', [PlanoController::class,'store']);

// Associar produto a um plano
Route::post('/planos/{planoID}/produtos/{produtoID}', [PlanoController::class, 'postAssociarProduto']);

// Desassociar produto a um plano
Route::delete('/planos/{planoID}/produtos/{produtoID}', [PlanoController::class, 'destroyDesassociarProduto']);

// Deletar um plano
Route::delete('/planos/{planoId}', [PlanoController::class, 'destroyPlanos']);
