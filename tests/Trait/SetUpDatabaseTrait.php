<?php

namespace Tests\Traits;

use App\Models\Produto;
use App\Models\Plano;

trait SetUpDatabaseTrait
{
    protected $planos;
    protected $produtos;

    protected function setUpDatabase(): void
    {
        $this->produtos - Produto::factory()->count(6)->create();
    }
}
