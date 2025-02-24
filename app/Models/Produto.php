<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * Classe Produto
 *
 * Representa um produto no sistema
 *
 * @property string $table Nome da tabela associada ao modelo
 * @property array $fillable Campos que podem ser preenchidos no banco de dados
 */
class Produto extends Model
{
    /**
     * Nome da tabela associada ao modelo
     *
     * @var string
     */
    use HasFactory;
    protected $table = 'produtos';

    /**
     * Campos preenchíveis no banco de dados
     *
     * @var array
     */
    protected $fillable = [
        "nome",
        'descricao'
    ];

    /**
     * Define a relação entre produtos e planos
     *
     * Um produto pode estar associado a vários planos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function planos()
    {
        return $this->belongsToMany(Plano::class);
    }
}
