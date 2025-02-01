<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProdutoResource;

use App\Services\ProdutoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProdutoFormRequest;
use App\Http\Requests\UpdateProdutoFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{

    protected $produtoService;

    public function __construct(ProdutoService $produtoService)
    {
        $this->produtoService = $produtoService;
    }


    public function getTodosProdutos(): JsonResponse
    {

        $result = $this->produtoService->getProdutos();

        if ($result['status']) {
            return $this->successResponse([
                ProdutoResource::collection($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }


    public function getPorIdProdutos($produtoId): JsonResponse
    {
        $result = $this->produtoService->getIdProdutos($produtoId);

        if ($result['status']) {
            return $this->successResponse([
                new ProdutoResource($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }


    public function store(StoreProdutoFormRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->produtoService->storeProdutos($data);

        if ($result['status']) {
            return $this->successResponse([
                new ProdutoResource($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }


    public function update(UpdateProdutoFormRequest $request, $produtoId): JsonResponse
    {
        $data = $request->validated();

        $result = $this->produtoService->updateProdutos($data, $produtoId);

        if ($result['status']) {
            return $this->successResponse([
                new ProdutoResource($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }


    public function destroyProdutos($produtoId)
    {

        $result = $this->produtoService->destroyProdutosPorId($produtoId);

        if ($result['status']) {
            return $this->successResponse([
                new ProdutoResource($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }
}
