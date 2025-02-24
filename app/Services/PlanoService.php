<?php

namespace App\Services;

use App\Models\Plano;
use App\Models\PlanoProdutoLog;
use App\Models\Produto;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Return_;

class PlanoService
{
    /**
     * Obtém uma lista de planos ordenada por ID
     *
     * @return array{data: \Illuminate\Database\Eloquent\Collection|null}
     * @throws Exception Se houver falha ao listar os planos
     */
    public function getPlanos()
    {
        $planos = Plano::orderBy('id', 'ASC')->get();

        return [
            'data' => $planos,
        ];
    }

    /**
     * Obtém um planos por id
     *
     * @param int $planoId O ID do plano a ser encontrado
     * @return array{data: \App\Models\Plano|null}
     * @throws ModelNotFoundException Se o plano não for encontrado
     * @throws Exception Se houver falha ao buscar o plano
     */
    public function getIdPlanos($planoId)
    {
        $planos = null;

        $planos = Plano::findOrFail($planoId);

        return [
            'data' => $planos,
        ];
    }

    /**
     * Obtém uma lista de planos e seus produtos
     *
     * @return array{data:  \Illuminate\Database\Eloquent\Collection|null}
     * @throws Exception Se houver falha ao buscar o plano
     */
    public function getPlanosProdutos()
    {
        $planos = Plano::with(['produtos', 'logs'])->get();

        return [
            'data' => $planos,
        ];
    }

    /**
     * Obtém uma lista de logs
     *
     * @return array{data: \Illuminate\Database\Eloquent\Collection|null}
     * @throws Exception Se houver falha ao buscar os logs
     */
    public function getTodosLogs()
    {
        $logs = PlanoProdutoLog::with('produto')->paginate(5);

        return [
            'data' => $logs,
        ];
    }

    /**
     * Cria novo plano e associa um produto a ele
     *
     * @param array $data Os dados do plano a ser criado
     * @param int $produtoId O ID do produto a ser associado ao plano
     * @return array{data: \App\Models\Plano|null}
     * @throws Exception Se houver falha durante a criação do plano
     */
    public function storePlanos(array $data, $produtoId)
    {
        DB::beginTransaction();

        $planos = Plano::create($data);
        $produto = Produto::findOrFail($produtoId);

        $planos->produtos()->attach($produto->id);

        DB::commit();

        return [
            'data' => $planos,
        ];
    }

    /**
     * Associa um produto a um plano
     *
     * @param int $planoId O ID do plano ao qual o produto será associado
     * @param int $produtoId O ID do produto a ser associado ao plano
     * @return array{data: \App\Models\Plano|null}
     * @throws Exception Se houver falha durante a associação
     */
    public function postPlanoProduto($planoId, $produtoId)
    {
        $planos = Plano::findOrFail($planoId);
        $produto = Produto::findOrFail($produtoId);

        $planos->produtos()->attach($produto->id);

        PlanoProdutoLog::create([
            'plano_id' => $planoId,
            'produto_id' => $produtoId,
            'action' => 'Adicionado',
        ]);

        return [
            'data' => $planos,
        ];
    }

    /**
     * Atualiza os dados de um plano existente
     *
     * @param array $data Os dados do plano a ser atualizado
     * @param int $planoId O ID do plano a ser atualizado
     * @param int $produtoId O ID do produto a ser associado ao plano
     * @return array{data: \App\Models\Plano|null}
     * @throws ModelNotFoundException Se o plano não for encontrado
     * @throws Exception Se houver falha durante a batalha
     */
    public function updatePlanos(array $data, $planoId)
    {
        $plano = Plano::findOrFail($planoId);

        $plano->update($data);

        DB::commit();

        return [
            'data' => $plano,
        ];
    }

    /**
     * Desassocia um produto a um plano
     *
     * @param int $planoId O ID do plano a ter produto desassociado
     * @param int $produtoId O ID do produto a ser desassociado ao plano
     * @return array{data: \App\Models\Plano|null}
     * @throws ModelNotFoundException Se o plano não for encontrado
     * @throws Exception Se houver falha durante a batalha
     */
    public function destroyDesassociarProduto($planoId, $produtoId)
    {
        $planos = Plano::findOrFail($planoId);
        $produto = Produto::findOrFail($produtoId);

        $planos->produtos()->detach($produto->id);

        PlanoProdutoLog::create([
            'plano_id' => $planoId,
            'produto_id' => $produtoId,
            'action' => 'Removido',
        ]);

        return [
            'data' => $planos,
        ];
    }

    /**
     * Exclui um plano existente pelo ID
     *
     * @param int $planoId O ID do plano a ser exluido
     * @return array{data: \App\Models\Plano|null}
     * @throws ModelNotFoundException Se o plano não for encontrado
     * @throws Exception Se houver falha durante a batalha
     */

    public function destroyPlanosPorId($planoId)
    {
        $planos = Plano::findOrFail($planoId);

        $planos->delete();

        return [
            'data' => $planos,
        ];
    }
}
