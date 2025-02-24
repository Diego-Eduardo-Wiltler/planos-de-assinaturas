<?php

namespace Database\Factories;

use App\Enums\ProdutoNameEnum;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->randomElement(ProdutoNameEnum::cases())->value,
            'descricao' => $this->faker->paragraph(2)
        ];
    }
}
