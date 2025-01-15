<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanoResource;
use App\Services\PlanoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanoController extends Controller
{
    /**
     * Instância do serviço PlanoService
     *
     * @var PlanoService
     */
    protected $planoService;

    /**
     * Cria uma nova instância do controlador PlanoController
     *
     * @param PlanoService $planoService Instância do serviço de planos
     */
    public function __construct(PlanoService $planoService)
    {
        $this->planoService = $planoService;
    }

    /**
     * Recupera todos os planos
     *
     * Método utiliza o serviço PlanoService para buscar todos os planos
     * e retorna a resposta em formato JSON
     *
     * @return JsonResponse Retorna uma resposta JSON com a lista de planos
     */
    public function getTodosPlanos(): JsonResponse
    {
        $result = $this->planoService->getPlanos();

        $status = $result['status'] ? 200 : 400;

        return response()->json($result, $status);
    }

    /**
     * Recupera todos os planos com seus produtos associados
     *
     * Método utiliza o serviço PlanoService para buscar todos os planos,
     * juntamente com seus respectivos produtos, e retorna a resposta em formato JSON
     *
     * @return JsonResponse Retorna uma resposta JSON com os planos e produtos
     */
    public function getPlanoProdutos(): JsonResponse
    {
        $result = $this->planoService->getPlanosProdutos();

        $status = $result['status'] ? 200 : 400;

        return response()->json(PlanoResource::collection($result['planos']), $status);
    }
    /**
     * Associa um produto a um plano.
     *
     * ID de plano e um ID de produto, e associa o produto ao plano correspondente
     *
     * @param Request $request A solicitação HTTP contendo os dados necessários para a operação
     * @param int $planoID O ID do plano ao qual o produto será associado
     * @param int $produtoId O ID do produto a ser associado ao plano
     * @return JsonResponse Retorna uma resposta JSON com o status da operação
     */
    public function postAssociarProduto(Request $request, $planoID, $produtoId): JsonResponse
    {
        $result = $this->planoService->postPlanoProduto($planoID, $produtoId);

        $status = $result['status'] ? 200 : 400;

        return response()->json($result, $status);
    }

    public function deleteDesassociarProduto(Request $request, $planoID, $produtoId): JsonResponse
    {
        $result = $this->planoService->destroyDesassociarProduto($planoID, $produtoId);

        $status = $result['status'] ? 200 : 400;

        return response()->json($result, $status);
    }

}
