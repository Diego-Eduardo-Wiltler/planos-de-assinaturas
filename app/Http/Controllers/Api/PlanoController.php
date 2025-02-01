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

    /**
     * GET /api/planos
     *
     * Retorna lista de planos cadastrados
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: array<PlanoResource>
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     */

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

     /**
     * GET /api/plano-produto-logs
     *
     * Retorna uma lista de logs
     *
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PlanoProdutoLogResource
     * }
     *
     *  @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     */
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

    /**
     * GET /api/planos/{id}
     *
     * Retorna um unico plano pelo id
     *
     * @urlParam id int required ID do plano a ser encontrado. Example: 1
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PlanoResource
     * }
     *
     *  @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param $id ID do Plano a ser encontrado
     *
     */
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

    /**
     * GET /api/planos-produtos
     *
     * Retorna uma lista de logs
     *
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PlanoProdutoLogResource
     * }
     *
     *  @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     */
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

    /**
     * POST /api/planos
     *
     * Cadastra um novo plano
     *
     * @bodyParam nome string required Nome do plano. Example: Claro Pro 12 gb
     * @bodyParam descricao string Descricao do plano. Example: Plano pós-pago com 50GB de internet
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PlanoResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param StorePlanoFormRequest $request Requisição contendo os dados do plano.
     * @return JsonResponse
     *
     *
     */
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

     /**
     * POST /api/planos/{planoId}/produtos/{produtoID}
     *
     * Associa um plano a um produto
     *
     * @urlParam id int required ID do plano que ira receber um produto. Example: 1
     * @urlParam id int required ID do produto que sera integrado a um plano. Example: 2
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PlanoProdutoResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param int $planoId ID do plano que recebe produto
     * @param int $produtoId ID do produto que integra no plano
     * @return JsonResponse
     *
     */
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

    /**
     * POST /api/planos/{planoId}
     *
     * Atualiza um plano existente
     *
     * @bodyParam nome string Nome do plano. Example: Claro Pro 12 gb
     * @bodyParam descricao string Descricao do plano. Example: Plano pós-pago com 50GB de internet
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PlanoResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param UpdatePlanoFormRequest $request Requisição contendo os dados do plano.
     * @param int $id ID do plano a ser atualizado
     * @return JsonResponse
     *
     *
     */
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

     /**
     * DELETE api/planos/{planoId}/produtos/{produtoID}
     *
     * Remove um produto de um plano existente
     *
     * @urlParam id int required ID do plano que irá ter um plano removido. Example: 1
     * @urlParam id int required ID do produto que irá ser removido. Example: 2
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PlanoResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param int $id planoId do plano que o produto será removido
     * @param int $produtoId ID do produto a ser removido
     * @return JsonResponse
     */
    public function destroyDesassociarProduto($planoId, $produtoId): JsonResponse
    {

        $result = $this->planoService->destroyDesassociarProduto($planoId, $produtoId);

        if ($result['status']) {
            return $this->successResponse([
                new PlanoResource($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }

     /**
     * DELETE api/planos/{planoId}/produtos/{produtoID}
     *
     * Remove um plano existente
     *
     * @urlParam id int required ID do plano que irá ser removido. Example: 1
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PlanoResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param int $planoId ID do plano a ser removido
     * @return JsonResponse
     */
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
