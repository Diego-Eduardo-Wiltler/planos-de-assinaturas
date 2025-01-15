<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    public function getAllPlanos(): JsonResponse
    {
        $result = $this->planoService->getPlanos();

        return response()->json($result, $result['status'] ? 200 : 400);
    }

    /**
     * Recupera todos os planos com seus produtos associados
     *
     * Método utiliza o serviço PlanoService para buscar todos os planos,
     * juntamente com seus respectivos produtos, e retorna a resposta em formato JSON
     *
     * @return JsonResponse Retorna uma resposta JSON com os planos e produtos
     */
    public function getAllPlanoProdutos(): JsonResponse
    {
        $result = $this->planoService->getPlanosProdutos();

        return response()->json($result);
    }
}
