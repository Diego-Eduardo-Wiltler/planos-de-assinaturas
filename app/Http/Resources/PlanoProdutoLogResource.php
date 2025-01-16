<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanoProdutoLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'plano_id' => $this->plano_id,
            'produto_id' => $this->produto_id,
            'nome' => $this->produto->nome,
            'action' => $this->action,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
