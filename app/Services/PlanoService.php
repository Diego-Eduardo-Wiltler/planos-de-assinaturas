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
