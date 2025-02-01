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
     * Recupera todos os produtos ordenados por ID
     *
     * Método retorna uma lista de produtos da tabela de produtos
     *
     * @return array Retorna um array com o status e os produtos
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
     * Recupera um produto por ID
     *
     * Método retorna o produtos recuperado
     *
     * @param int $produtoId O ID do produto a ser buscado
     * @return array Retorna um array com o status e os produtos
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
     * Novo produto
     *
     * Método cria um novo produto com os dados fornecidos e associa o produto a um produto especificado
     * É realizada dentro de uma transação do banco de dados para garantir a consistência
     * Em caso de falha, a transação é revertida
     *
     * @param array $data Os dados do produto a ser criado
     * @param int $produtoId O ID do produto a ser associado ao produto
     * @return array Retorna um array contendo o status da operação
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
     * Método recebe os novos dados de um produto e os valida antes de atualizar o produto existente
     *
     * Ele utiliza o serviço ProdutoService para atualizar o produto e retorna uma resposta JSON
     * com os dados do produto atualizado
     *
     * @param Request $request A solicitação HTTP contendo os dados do produto a ser atualizado
     * @param int $id O ID do produto a ser atualizado
     * @return JsonResponse Retorna uma resposta JSON contendo os dados do produto atualizado e o status da operação
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
     * Exclui um produto específico pelo ID
     *
     * Método busca um produto pelo ID fornecido, tenta excluí-lo do banco de dados e retorna
     * um array indicando o sucesso ou falha da operação, incluindo os dados do produto excluído ou não
     *
     * @param int $produtoId O ID do produto a ser excluído
     * @return array Retorna um array com o status da operação e o produto excluído
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
