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
        try {
            $produtos = Produto::orderBy('id', 'ASC')->get();

            Log::info('Listando todos os produtos', ['quantidade' => $produtos->count()]);

            $reponse = [
                'status' => true,
                'message' => 'Os produtos foram listados',
                'data' => $produtos,
            ];
        } catch (Exception $e) {
            Log::error('Erro ao listar produtos', [
                'error_message' => $e->getMessage(),
            ]);

            $reponse = [
                'status' => false,
                'message' => 'Os produtos não foram listados',
            ];
        }
        return $reponse;
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
        $produtos = null;
        try {
            $produtos = Produto::findOrFail($produtoId);

            Log::info('Trazendo produto por Id', ['dados' => $produtos]);

            $response = [
                'status' => true,
                'message' => 'Listando produto por id',
                'data' => $produtos,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            Log::error('Erro ao tentar trazer produto', [
                'error_message' => $e->getMessage(),
                'produtoId' => $produtoId
            ]);

            $response = [
                'status' => false,
                'message' => 'Produto não foi listado',
            ];
        }

        return $response;
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
        try {

            Log::info('Iniciando cadastro', ['dados' => $data]);

            $produtos = produto::create($data);

            DB::commit();

            Log::info('Produto cadastrado!', ['produtos' => $produtos]);

            $response = [
                'status' => true,
                'message' => 'Produto Cadastrado',
                'data' => $produtos,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Erro ao tentar cadastrar produto', [
                'error_message' => $e->getMessage(),
                'dados' => $data
            ]);

            $response = [
                'status' => false,
                'message' => 'produto não cadastrado',
            ];
        }

        return $response;
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
        try {
            $produtos = Produto::findOrFail($produtoId);

            Log::info('Iniciando atualização', ['dados' => $data]);

            $produtos->update($data);

            DB::commit();

            Log::info('Produto atualizado!', ['produtos' => $produtos]);

            return [
                'status' => true,
                'message' => 'Produto atualizado',
                'data' => $produtos,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            DB::rollBack();

            Log::error('Erro ao tentar atualizar produto', [
                'error_message' => $e->getMessage(),
                'dados' => $data
            ]);

            return [
                'status' => false,
                'message' => 'Produto não atualizado',
            ];
        }
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
        $produtos = null;
        try {
            $produtos = Produto::findOrFail($produtoId);

            Log::info('Iniciando exclusão', ['dados' => $produtoId]);

            $produtos->delete();

            Log::info('Produto excluído!', ['produtos' => $produtos]);

            $reponse = [
                'status' => true,
                'message' => 'Produto excluído',
                'data' => $produtos,
            ];
        } catch (ModelNotFoundException | Exception $e) {

            Log::error('Erro ao tentar excluir produto', [
                'error_message' => $e->getMessage(),
                'dados' => $produtoId,
            ]);

            $reponse = [
                'status' => false,
                'message' => 'Produto não encontrado',
            ];
        }

        return $reponse;
    }
}
