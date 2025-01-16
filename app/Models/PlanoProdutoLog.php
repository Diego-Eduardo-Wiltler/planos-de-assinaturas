<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanoProdutoLog extends Model
{
    protected $table = 'plano_produto_logs';

    protected $fillable = [
        'plano_id',
        'produto_id',
        'action'
    ];

    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
