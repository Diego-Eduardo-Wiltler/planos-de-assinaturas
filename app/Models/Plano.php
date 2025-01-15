<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe Plano
 *
 * Representa um plano no sistema
 *
 * @property string $table Nome da tabela no banco de dados
 * @property array $fillable Campos que podem ser preenchidos no banco de dados
 */
class Plano extends Model
{
    /**
     * Nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'planos';

    /**
     * Campos preenchíveis no banco de dados.
     *
     * @var array
     */
    protected $fillable = [
        "nome",
        'descricao'
    ];

    /**
     * Define a relação entre planos e produtos
     *
     * Um plano pode estar associado a vários produtos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function produtos()
    {
        return $this->belongsToMany(Produto::class);
    }
}
