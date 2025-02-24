<?php

namespace App\Enums;

enum PlanoNameEnum: string {

    CASE CLARO_POS = 'Claro Pós 100GB';
    CASE CLARO_CONTROLE = 'Claro Controle 30GB';
    CASE CLARO_PRE = 'Claro Pré 15GB';
    CASE CLARO_FAMILIA = 'Claro Família 200G';
    CASE CLARO_ILIMITADO = 'Claro Ilimitado 500GB';
    CASE CLARO_EMPRESAS = 'Claro Empresas 1TB';

    public function label(): string
    {
        return $this->value;
    }
}






