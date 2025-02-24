<?php

namespace App\Enums;

enum ProdutoNameEnum: string
{
    CASE INTERNET = 'Internet';
    CASE TELEFONE = 'Telefone';
    CASE CELULAR = 'Celular';
    CASE TELEVISAO = 'Televisão';
    CASE APLICATIVO = 'Aplicativo';
    CASE ARMAZENAMENTO = 'Armazenamento';

    public function label(): string
    {
        return $this->value;
    }
}
