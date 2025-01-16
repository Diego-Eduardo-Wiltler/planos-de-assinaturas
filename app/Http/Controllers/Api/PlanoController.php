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

        return response()->json(PlanoResource::collection($result['planos']), $status);
    }
    /**
     * Recupera todos os logs de planos
     *
     * Método utiliza o serviço PlanoService para buscar todos os logs relacionados aos planos
     * e retorna esses logs em uma formato JSON
     *
     * @return JsonResponse Retorna uma resposta JSON contendo os logs
     */
    public function getLogs(): JsonResponse
    {
        $result = $this->planoService->getTodosLogs();

        $status = $result['status'] ? 200 : 400;

        return response()->json(PlanoProdutoLogResource::collection($result['logs']), $status);
    }

    /**
     * Retorna um plano específico pelo ID
     *
     * Método utiliza o serviço PlanoService para buscar um plano pelo ID fornecido
     * e retorna a resposta em formato JSON
     *
     * @param int $planoId O ID do plano a ser buscado
     * @return JsonResponse Retorna uma resposta JSON com a lista de planos
     */
    public function getPorIdPlanos($planoId): JsonResponse
    {
        $result = $this->planoService->getIdPlanos($planoId);

        $status = $result['status'] ? 200 : 400;

        return response()->json(new PlanoResource($result['planos']), $status);
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

        return response()->json(PlanoProdutoResource::collection($result['planos']), $status);
    }

    /**
     * Novo plano e associa um produto a ele
     *
     * Método recebe dados de um novo plano e um ID de produto para associar ao plano criado
     *
     * Ele utiliza o serviço PlanoService para criar o plano e realizar a associação com o produto,
     * retornando uma resposta JSON com os dados do plano criado
     *
     * @param Request $request A solicitação HTTP contendo os dados do plano e o ID do produto a ser associado
     * @return JsonResponse Retorna uma resposta JSON contendo os dados do plano criado e o status da operação
     */
    public function store(StorePlanoFormRequest $request): JsonResponse
{
    $data = $request->validated();
    $produtoId = $data['produto_id'];

    $result = $this->planoService->storePlanos($data, $produtoId);

    if($result['status']){
        return response()->json(new PlanoResource($result['planos']));
    }
    return response()->json(['message' => $result['message']], 400);
}


    /**
     * Associa um produto a um plano.
     *
     * ID de plano e um ID de produto, e associa o produto ao plano correspondente
     *
     * @param Request $request A solicitação HTTP contendo os dados necessários para a operação
     * @param int $planoId O ID do plano ao qual o produto será associado
     * @param int $produtoId O ID do produto a ser associado ao plano
     * @return JsonResponse Retorna uma resposta JSON com o status da operação
     */
    public function postAssociarProduto(PostAssociarPlanoFormRequest $request, $planoId, $produtoId): JsonResponse
    {

        $result = $this->planoService->postPlanoProduto($planoId, $produtoId);

        $status = $result['status'] ? 200 : 400;

        return response()->json(new PlanoProdutoResource($result['planos']), $status);
    }

    /**
     * Atualiza os dados de um plano existente
     *
     * Método recebe os novos dados de um plano e os valida antes de atualizar o plano existente
     *
     * Ele utiliza o serviço PlanoService para atualizar o plano e retorna uma resposta JSON
     * com os dados do plano atualizado
     *
     * @param Request $request A solicitação HTTP contendo os dados do plano a ser atualizado
     * @param int $id O ID do plano a ser atualizado
     * @return JsonResponse Retorna uma resposta JSON contendo os dados do plano atualizado e o status da operação
     */
    public function update(UpdatePlanoFormRequest $request, $planoId): JsonResponse
    {
        $data = $request->validated();

        $result = $this->planoService->updatePlanos($data, $planoId);

        if ($result['status']) {

            return response()->json(new PlanoResource($result['plano']));
        }
        return response()->json(['message' => $result['message']], 400);
    }

    /**
     * Desassocia um produto a um plano
     *
     * ID de plano e um ID de produto, e Desassocia o produto ao plano correspondente
     *
     * @param Request $request A solicitação HTTP contendo os dados necessários para a operação
     * @param int $planoId O ID do plano ao qual o produto será associado
     * @param int $produtoId O ID do produto a ser associado ao plano
     * @return JsonResponse Retorna uma resposta JSON com o status da operação
     */
    public function destroyDesassociarProduto(Request $request, $planoId, $produtoId): JsonResponse
    {

        $result = $this->planoService->destroyDesassociarProduto($planoId, $produtoId);

        if ($result['status']) {

            return response()->json(new PlanoResource($result['planos']));
        }

        return response()->json(['message' => $result['message']], 400);
    }

    /**
     * Remove um plano específico pelo ID
     *
     * Método utiliza o serviço PlanoService para excluir um plano do banco de dados com base no ID fornecido
     * Retorna uma resposta JSON indicando o sucesso ou falha da operação, com os dados do plano removido
     *
     * @param int $planoId O ID do plano a ser removido
     * @return JsonResponse  Retorna uma resposta JSON com o status da operação
     */
    public function destroyPlanos(Request $request, $planoId)
    {

        $result = $this->planoService->destroyPlanosPorId($planoId);

        if ($result['status']) {

            return response()->json(new PlanoResource($result['planos']));
        }

        return response()->json(['message' => $result['message']], 400);
    }
}
