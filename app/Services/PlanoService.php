<?php

namespace App\Services;

use App\Models\Plano;
use App\Models\Produto;

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
        $plano = Plano::find($planoId);
        $produto = Produto::find($produtoId);

        $plano->produtos()->attach($produto->id);

        return [
            'status' => true,
            'planosProdutos' => [
                'Plano' => [
                    'id' => $plano->id,
                    'nome' => $plano->nome,
                    'descricao' => $plano->descricao,
                ],
                'Produto' => [
                    'id' => $produto->id,
                    'nome' => $produto->nome,
                    'descricao' => $produto->descricao,
                ]
            ]
        ];
    }
}
