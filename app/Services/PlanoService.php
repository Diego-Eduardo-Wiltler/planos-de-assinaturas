<?php

namespace App\Services;

use App\Models\Plano;
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
        $planos = Plano::with('produtos')->get();

        return [
            'status' => true,
            'planos' => $planos,
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

        return [
            'status' => true,
            'planos' => $planos,
        ];
    }
}
