<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class PostAssociarPlanoFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ajuste se necessário
    }

    public function rules(): array
    {
        return [];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $planoId = $this->route('planoId');
            $produtoId = $this->route('produtoId');


            $produtoJaAssociado = DB::table('plano_produto')
                ->where('plano_id', $planoId)
                ->where('produto_id', $produtoId)
                ->exists();

            if ($produtoJaAssociado) {
                $validator->errors()->add('produto_id', 'Este produto já está associado ao plano.');
            }
        });
    }
    public function messages(): array
    {
        return [

        ];
    }
}
