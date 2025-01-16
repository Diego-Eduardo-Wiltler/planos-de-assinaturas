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
    /**
     * Instância do serviço ProdutoService
     *
     * @var ProdutoService
     */
    protected $produtoService;

    /**
     * Cria uma nova instância do controlador ProdutoController
     *
     * @param ProdutoService $produtoService Instância do serviço de produtos
     */
    public function __construct(ProdutoService $produtoService)
    {
        $this->produtoService = $produtoService;
    }

    /**
     * Recupera todos os produtos
     *
     * Método utiliza o serviço ProdutoService para buscar todos os produtos
     * e retorna a resposta em formato JSON
     *
     * @return JsonResponse Retorna uma resposta JSON com a lista de produtos
     */
    public function getTodosProdutos(): JsonResponse
    {

        $result = $this->produtoService->getProdutos();

        $status = $result['status'] ? 200 : 400;

        return response()->json(ProdutoResource::collection($result['produtos']), $status);
    }

    /**
     * Retorna um produto específico pelo ID
     *
     * Método utiliza o serviço ProdutoService para buscar um produto pelo ID fornecido
     * e retorna a resposta em formato JSON
     *
     * @param int $produtoId O ID do produto a ser buscado
     * @return JsonResponse Retorna uma resposta JSON com a lista de produtos
     */
    public function getPorIdProdutos($produtoId): JsonResponse
    {
        $result = $this->produtoService->getIdProdutos($produtoId);

        if ($result['status']) {

            return response()->json(new ProdutoResource($result['produtos']));
        }

        return response()->json(['message' => $result['message']], 400);
    }

    /**
     * Cria um novo produto
     *
     * Método recebe os dados de um novo produto e os valida antes de realizar a criação
     *
     * Ele utiliza o serviço ProdutoService para criar o produto e retorna uma resposta JSON
     * com os dados do produto criado
     *
     * @param Request $request A solicitação HTTP contendo os dados do produto a ser criado
     * @return JsonResponse Retorna uma resposta JSON contendo os dados do produto criado e o status da operação
     */
    public function store(StoreProdutoFormRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->produtoService->storeProdutos($data);

        $status = $result['status'] ? 200 : 400;

        return response()->json(new ProdutoResource($result['produtos']), $status);
    }

    /**
     * Atualiza os dados de um produto existente
     *
     * Método recebe os novos dados de um produto e os valida antes de atualizar o produto existente
     *
     * Ele utiliza o serviço ProdutoService para atualizar o produto e retorna uma resposta JSON
     * com os dados do produto atualizado
     *
     * @param Request $request A solicitação HTTP contendo os dados do produto a ser atualizado
     * @param int $id O ID do produto a ser atualizado
     * @return JsonResponse Retorna uma resposta JSON contendo os dados do produto atualizado e o status da operação
     */
    public function update(UpdateProdutoFormRequest $request, $produtoId): JsonResponse
    {
        $data = $request->validated();

        $result = $this->produtoService->updateProdutos($data, $produtoId);

        if ($result['status']) {

            return response()->json(new ProdutoResource($result['produtos']));
        }

        return response()->json(['message' => $result['message']], 400);
    }

    /**
     * Remove um produto existente
     *
     * Método recebe o ID de um produto para removê-lo do sistema
     *
     * Ele utiliza o serviço ProdutoService para realizar a remoção e retorna uma resposta JSON
     * indicando o status da operação
     *
     * @param Request $request A solicitação HTTP contendo os dados do produto a ser removido
     * @param int $produtoId O ID do produto a ser removido
     * @return JsonResponse Retorna uma resposta JSON contendo o status da operação
     */
    public function destroyProdutos(Request $request, $produtoId)
    {

        $result = $this->produtoService->destroyProdutosPorId($produtoId);

        if ($result['status']) {

            return response()->json(new ProdutoResource($result['produtos']));
        }

        return response()->json(['message' => $result['message']], 400);
    }
}
