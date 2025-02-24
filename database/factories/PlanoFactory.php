<?php

namespace Database\Factories;

use App\Enums\PlanoNameEnum;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plano>
 */
class PlanoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->randomElement(PlanoNameEnum::cases())->value,
            'descricao' => $this->faker->paragraph(2),
        ];
    }
}
