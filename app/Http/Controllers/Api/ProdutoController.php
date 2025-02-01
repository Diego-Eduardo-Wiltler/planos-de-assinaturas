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

    /**
     * GET /api/produtos
     *
     * Retorna lista de produtos cadastrados
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: array<ProdutoResource>
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     */

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

     /**
     * GET /api/produtos/{id}
     *
     * Retorna um unico produto pelo id
     *
     * @urlParam id int required ID do produto a ser encontrado. Example: 1
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: ProdutoResource
     * }
     *
     *  @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param $id ID do produto a ser encontrado
     *
     */
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

    /**
     * POST /api/produtos
     *
     * Cadastra um novo produto
     *
     * @bodyParam nome string required Nome do produto. Example: Telefone
     * @bodyParam descricao string Descricao do produto. Example: Acesse + de 1000 mil ligações a qualquer momento
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: ProdutoResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param StoreProdutoFormRequest $request Requisição contendo os dados do produto.
     * @return JsonResponse
     *
     */
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

     /**
     * POST /api/produtos
     *
     * Atualiza um produto existente
     *
     * @bodyParam nome string Nome do produtos. Example: Telefone
     * @bodyParam descricao string Descricao do produtos. Example: Acesse + de 1000 mil ligações a qualquer momento
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: ProdutoResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param UpdateProdutoFormRequest $request Requisição contendo os dados do produto.
     * @return JsonResponse
     *
     */
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

 /**
     * DELETE api/produtos-deletar/{produtoId}
     *
     * Remove um produto existente
     *
     * @urlParam id int required ID do produto que irá ser removido. Example: 1
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: ProdutoResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param int $produtoId ID do produto a ser removido
     * @return JsonResponse
     */
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
