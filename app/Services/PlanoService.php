<?php

namespace App\Services;

use App\Models\Plano;

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
}
