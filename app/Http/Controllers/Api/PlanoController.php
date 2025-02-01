<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostAssociarPlanoFormRequest;
use App\Http\Requests\StorePlanoFormRequest;
use App\Http\Requests\UpdatePlanoFormRequest;
use App\Http\Resources\PlanoProdutoLogResource;
use App\Http\Resources\PlanoProdutoResource;
use App\Http\Resources\PlanoResource;
use App\Services\PlanoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanoController extends Controller
{

    protected $planoService;


    public function __construct(PlanoService $planoService)
    {
        $this->planoService = $planoService;
    }


    public function getTodosPlanos(): JsonResponse
    {

        $result = $this->planoService->getPlanos();

        if ($result['status']) {
            return $this->successResponse([
                PlanoResource::collection($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }


    public function getLogs(): JsonResponse
    {
        $result = $this->planoService->getTodosLogs();

        if ($result['status']) {
            return $this->successResponse([
                PlanoProdutoLogResource::collection($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }


    public function getPorIdPlanos($planoId): JsonResponse
    {
        $result = $this->planoService->getIdPlanos($planoId);

        if ($result['status']) {
            return $this->successResponse([
                new PlanoResource($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }


    public function getPlanoProdutos(): JsonResponse
    {
        $result = $this->planoService->getPlanosProdutos();

        if ($result['status']) {
            return $this->successResponse([
                PlanoProdutoResource::collection($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }


    public function store(StorePlanoFormRequest $request): JsonResponse
    {
        $data = $request->validated();
        $produtoId = $data['produto_id'];

        $result = $this->planoService->storePlanos($data, $produtoId);

        if ($result['status']) {
            return $this->successResponse([
                new PlanoResource($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }


    public function postAssociarProduto($planoId, $produtoId): JsonResponse
    {

        $result = $this->planoService->postPlanoProduto($planoId, $produtoId);

        if ($result['status']) {
            return $this->successResponse([
                new PlanoProdutoResource($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }

    public function update(UpdatePlanoFormRequest $request, $planoId): JsonResponse
    {
        $data = $request->validated();

        $result = $this->planoService->updatePlanos($data, $planoId);

        if ($result['status']) {
            return $this->successResponse([
                new PlanoResource($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }


    public function destroyDesassociarProduto(Request $request, $planoId, $produtoId): JsonResponse
    {

        $result = $this->planoService->destroyDesassociarProduto($planoId, $produtoId);

        if ($result['status']) {
            return $this->successResponse([
                new PlanoResource($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }


    public function destroyPlanos($planoId)
    {

        $result = $this->planoService->destroyPlanosPorId($planoId);

        if ($result['status']) {
            return $this->successResponse([
                new PlanoResource($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }
}
