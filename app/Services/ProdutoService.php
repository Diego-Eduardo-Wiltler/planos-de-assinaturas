<?php

namespace App\Services;

use App\Models\Produto;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDOException;

class ProdutoService
{
    /**
     * Obtém uma lista de produtos ordenada por ID
     *
     * @return array{status: bool, message: string, data: \Illuminate\Database\Eloquent\Collection|null}
     * @throws Exception Se houver falha ao listar os produtos
     */
    public function getProdutos()
    {
        $produtos = Produto::orderBy('id', 'ASC')->get();

        return  [
            'status' => true,
            'message' => 'Os produtos foram listados',
            'data' => $produtos,
        ];
    }

    /**
     * Obtém um produto por ID
     *
     * @param int $produtoId O ID do produto a ser encontrado
     * @return array{status: bool, message: string, data: \App\Models\Produto}
     * @throws ModelNotFoundException Se o produto não for encontrado
     * @throws Exception Se houver falha ao buscar o produto
     *
     */
    public function getIdProdutos($produtoId)
    {
        $produtos = Produto::findOrFail($produtoId);

        return [
            'status' => true,
            'message' => 'Listando produto por id',
            'data' => $produtos,
        ];
    }
    /**
     * Cria um novo produto
     *
     * @param array $data Os dados do produto a ser criado
     * @return array{status: bool, message: string, data: \App\Models\Produto}
     * @throws Exception Se houver falha ao buscar o produto
     *
     */
    public function storeProdutos(array $data)
    {
        DB::beginTransaction();

        $produtos = produto::create($data);

        DB::commit();

        return [
            'status' => true,
            'message' => 'Produto Cadastrado',
            'data' => $produtos,
        ];
    }

    /**
     * Atualiza os dados de um produto existente
     *
     * @param int $produtoId O ID do produto a ser atualizado
     * @return array{status: bool, message: string, data: \App\Models\Produto}
     * @throws ModelNotFoundException Se o produto não for encontrado
     * @throws Exception Se houver falha ao buscar o produto
     *
     */
    public function updateProdutos(array $data, $produtoId)
    {
        DB::beginTransaction();

        $produtos = Produto::findOrFail($produtoId);

        $produtos->update($data);

        DB::commit();

        return [
            'status' => true,
            'message' => 'Produto atualizado',
            'data' => $produtos,
        ];
    }

    /**
     * Exclui um produto existente
     *
     * @param int $produtoId O ID do produto a ser excluido
     * @return array{status: bool, message: string, data: \App\Models\Produto}
     * @throws ModelNotFoundException Se o produto não for encontrado
     * @throws Exception Se houver falha ao buscar o produto
     *
     */

    public function destroyProdutosPorId($produtoId)
    {
        $produtos = Produto::findOrFail($produtoId);

        $produtos->delete();

        return [
            'status' => true,
            'message' => 'Produto excluído',
            'data' => $produtos,
        ];
    }
}
