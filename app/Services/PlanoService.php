<?php

namespace App\Services;

use App\Models\Plano;
use App\Models\PlanoProdutoLog;
use App\Models\Produto;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class PlanoService
{
    /**
     * Recupera todos os planos ordenados por ID
     *
     * Método retorna uma lista de planos da tabela de planos
     *
     * @return array Retorna um array com o status e os planos
     */
    public function getPlanos()
    {
        $planos = Plano::orderBy('id', 'ASC')->get();

        return [
            'status' => true,
            'planos' => $planos,
        ];
    }

    /**
     * Recupera um plano por ID
     *
     * Método retorna o plano recuperado
     *
     * @param int $planoId O ID do plano a ser buscado
     * @return array Retorna um array com o status e os planos
     */
    public function getIdPlanos($planoId)
    {
        $planos = Plano::find($planoId);

        return [
            'status' => true,
            'planos' => $planos,
        ];
    }

    /**
     * Recupera todos os planos junto com seus produtos associados
     *
     * Método retorna uma lista de planos, com seus respectivos produtos associados, utilizando
     * a N:N entre planos e produtos
     *
     * @return array Retorna um array com o status e os planos com produtos
     */
    public function getPlanosProdutos()
    {
        $planos = Plano::with('produtos')->with('logs')->get();

        return [
            'status' => true,
            'planos' => $planos,
        ];
    }

    /**
     * Recuperar todos os logs de planos e produtos.
     *
     * @return array
     */
    public function getTodosLogs()
    {
        $logs = PlanoProdutoLog::with('produto')->get();
        return [
            'status' => true,
            'logs' => $logs,
        ];
    }

    /**
     * Novo plano e associa um produto a ele
     *
     * Método cria um novo plano com os dados fornecidos e associa o plano a um produto especificado
     * É realizada dentro de uma transação do banco de dados para garantir a consistência
     * Em caso de falha, a transação é revertida.
     *
     * @param array $data Os dados do plano a ser criado
     * @param int $produtoId O ID do produto a ser associado ao plano
     * @return array Retorna um array contendo o status da operação
     */
    public function storePlanos(array $data, $produtoId)
    {
        DB::beginTransaction();
        try {
            $planos = Plano::create($data);
            $produto = Produto::find($produtoId);

            $planos->produtos()->attach($produto->id);

            DB::commit();

            return [
                'status' => true,
                'planos' => $planos,
                'message' => 'Plano Cadastrado'
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'message' => 'Plano não cadastrado',
            ];
        }
    }

    /**
     * Associa um produto a um plano
     *
     * Método encontra um plano e um produto com base nos IDs fornecidos e associa o produto ao plano
     * Depois da associação, ele retorna um array com o status da operação e os dados do plano e produto associados
     *
     * @param int $planoId O ID do plano ao qual o produto será associado
     * @param int $produtoId O ID do produto a ser associado ao plano
     * @return array Retorna um array com o status da operação e os dados do plano e do produto
     */
    public function postPlanoProduto($planoId, $produtoId)
    {
        $planos = Plano::find($planoId);
        $produto = Produto::find($produtoId);

        $planos->produtos()->attach($produto->id);

        PlanoProdutoLog::create([
            'plano_id' => $planoId,
            'produto_id' => $produtoId,
            'action' => 'Adicionado',
        ]);

        return [
            'status' => true,
            'planos' => $planos,
        ];
    }

    /**
     * Desassocia um produto a um plano
     *
     * Método encontra um plano e um produto com base nos IDs fornecidos e Desassocia o produto ao plano
     * Depois da desassociação, ele retorna um array com o status da operação e os dados do plano e produto
     *
     * @param int $planoId O ID do plano ao qual o produto será desassociado
     * @param int $produtoId O ID do produto a ser desassociado ao plano
     * @return array Retorna um array com o status da operação e os dados do plano e do produto
     */
    public function destroyDesassociarProduto($planoId, $produtoId)
    {
        $planos = Plano::find($planoId);
        $produto = Produto::find($produtoId);

        $planos->produtos()->detach($produto->id);

        PlanoProdutoLog::create([
            'plano_id' => $planoId,
            'produto_id' => $produtoId,
            'action' => 'Removido',
        ]);

        return [
            'status' => true,
            'planos' => $planos,
        ];
    }

    /**
     * Exclui um plano específico pelo ID
     *
     * Método busca um plano pelo ID fornecido, tenta excluí-lo do banco de dados e retorna
     * um array indicando o sucesso ou falha da operação, incluindo os dados do plano excluído ou não
     *
     * @param int $planoId O ID do plano a ser excluído.
     * @return array Retorna um array com o status da operação e o plano excluído
     */
    public function destroyPlanosPorId($planoId)
    {
        $planos = Plano::find($planoId);

        try {
            $planos->delete();

            return [
                'status' => true,
                'planos' => $planos,
                'message' => 'Plano excluído',
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'planos' => $planos,
                'message' => 'Plano não excluído',
            ];
        }
    }
}
