<?php

use App\Http\Controllers\Api\PlanoController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Resources\PlanoResource;
use App\Models\Plano;
use Illuminate\Support\Facades\Route;

// Obter todos os planos
Route::get('/planos', [PlanoController::class, 'getTodosPlanos']);

// Obter plano por id
Route::get('planos/{planoId}', [PlanoController::class, 'getPorIdPlanos']);

// Obter todos os planos com seus produtos
Route::get('/planos-produtos', [PlanoController::class, 'getPlanoProdutos']);

// Obter todos os logs separados
Route::get('plano-produto-logs', [PlanoController::class, 'getLogs']);

// Criar um novo plano
Route::post('/planos', [PlanoController::class,'store']);

// Associar produto a um plano
Route::post('/planos/{planoID}/produtos/{produtoID}', [PlanoController::class, 'postAssociarProduto']);

Route::put('/planos/{planoID}', [PlanoController::class,'update']);

// Desassociar produto a um plano
Route::delete('/planos/{planoID}/produtos/{produtoID}', [PlanoController::class, 'destroyDesassociarProduto']);

// Deletar um plano
Route::delete('/planos-deletar/{planoId}', [PlanoController::class, 'destroyPlanos']);

# Rotas para Produto

// Obter todos os produtos
Route::get('/produtos', [ProdutoController::class, 'getTodosProdutos']);

// Obter produto por id
Route::get('produtos/{produtoId}', [ProdutoController::class, 'getPorIdProdutos']);

// Criar um novo produto
Route::post('/produtos', [ProdutoController::class,'store']);

Route::put('/produtos/{produtoId}', [ProdutoController::class,'update']);

// Deletar um produto
Route::delete('/produtos-deletar/{produtoId}', [ProdutoController::class, 'destroyProdutos']);
