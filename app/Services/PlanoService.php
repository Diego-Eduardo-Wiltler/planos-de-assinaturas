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
     * @return array{status: bool, message: string, data: \Illuminate\Database\Eloquent\Collection|null}
     * @throws Exception Se houver falha ao listar os planos
     */
    public function getPlanos()
    {
        $planos = null;

        try {
            $planos = Plano::orderBy('id', 'ASC')->get();

            Log::info('Listando todos os planos', ['quantidade' => $planos->count()]);

            $response = [
                'status' => true,
                'message' => 'Todos os planos foram listados',
                'data' => $planos,
            ];
        } catch (Exception $e) {
            Log::error('Erro ao listar planos', [
                'error_message' => $e->getMessage(),
            ]);

            $response = [
                'status' => false,
                'message' => 'planos não foram listados',
            ];
        }
        return $response;
    }

     /**
     * Obtém um planos por id
     *
     * @param int $planoId O ID do plano a ser encontrado
     * @return array{status: bool, message: string, data: \App\Models\Plano|null}
     * @throws ModelNotFoundException Se o plano não for encontrado
     * @throws Exception Se houver falha ao buscar o plano
     */
    public function getIdPlanos($planoId)
    {
        $planos = null;

        try {
            $planos = Plano::findOrFail($planoId);
            Log::info('Trazendo plano por Id', ['dados' => $planos]);

            $response = [
                'status' => true,
                'data' => $planos,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            Log::error('Erro ao tentar trazer plano', [
                'error_message' => $e->getMessage(),
                'planoId' => $planoId
            ]);

            $response = [
                'status' => false,
                'message' => 'plano não foi listado',
            ];
        }
        return $response;
    }

     /**
     * Obtém uma lista de planos e seus produtos
     *
     * @return array{status: bool, message: string, data:  \Illuminate\Database\Eloquent\Collection|null}
     * @throws Exception Se houver falha ao buscar o plano
     */
    public function getPlanosProdutos()
    {
        try {
            $planos = Plano::with(['produtos', 'logs'])->get();

            $response =  [
                'status' => true,
                'message' => 'Planos e seus produtos listados',
                'data' => $planos,
            ];
        } catch (Exception $e) {
            $response = [
                'status' => true,
                'message' => 'Planos e seus produtos não listados',

            ];
        }
        return  $response;
    }

    /**
     * Obtém uma lista de logs
     *
     * @return array{status: bool, message: string, data: \Illuminate\Database\Eloquent\Collection|null}
     * @throws Exception Se houver falha ao buscar os logs
     */
    public function getTodosLogs()
    {
        try {
            $logs = PlanoProdutoLog::with('produto')->paginate(5);
            $response = [
                'status' => true,
                'message' => 'Logs Listados',
                'data' => $logs,
            ];
        } catch (Exception $e) {
            $response = [
                'status' => true,
                'message' => 'Não foi possível listar logs',
            ];
        }
        return $response;
    }

    /**
     * Cria novo plano e associa um produto a ele
     *
     * @param array $data Os dados do plano a ser criado
     * @param int $produtoId O ID do produto a ser associado ao plano
     * @return array{status: bool, message: string, data: \App\Models\Plano|null}
     * @throws Exception Se houver falha durante a criação do plano
     */
    public function storePlanos(array $data, $produtoId)
    {
        $produto = null;
        DB::beginTransaction();
        try {
            $planos = Plano::create($data);
            $produto = Produto::findOrFail($produtoId);

            $planos->produtos()->attach($produto->id);

            DB::commit();

            $reponse = [
                'status' => true,
                'message' => 'Plano Cadastrado',
                'data' => $planos,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $reponse = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        return $reponse;
    }

    /**
     * Associa um produto a um plano
     *
     * @param int $planoId O ID do plano ao qual o produto será associado
     * @param int $produtoId O ID do produto a ser associado ao plano
     * @return array{status: bool, message: string, data: \App\Models\Plano|null}
     * @throws Exception Se houver falha durante a associação
     */
    public function postPlanoProduto($planoId, $produtoId)
    {
        try {

            $planos = Plano::findOrFail($planoId);
            $produto = Produto::findOrFail($produtoId);

            $planos->produtos()->attach($produto->id);

            PlanoProdutoLog::create([
                'plano_id' => $planoId,
                'produto_id' => $produtoId,
                'action' => 'Adicionado',
            ]);

            $response = [
                'status' => true,
                'data' => $planos,
            ];
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Não foi possível associar produto ao plano',
            ];
        }
        return $response;
    }

     /**
     * Atualiza os dados de um plano existente
     *
     * @param array $data Os dados do plano a ser atualizado
     * @param int $planoId O ID do plano a ser atualizado
     * @param int $produtoId O ID do produto a ser associado ao plano
     * @return array{status: bool, message: string, data: \App\Models\Plano|null}
     * @throws ModelNotFoundException Se o plano não for encontrado
     * @throws Exception Se houver falha durante a batalha
     */
    public function updatePlanos(array $data, $planoId)
    {
        DB::beginTransaction();
        try {
            $plano = Plano::findOrFail($planoId);

            Log::info('Iniciando atualização do plano', ['dados' => $data]);

            $plano->update($data);

            DB::commit();

            Log::info('Plano atualizado com sucesso!', ['plano' => $plano]);


            $response = [
                'status' => true,
                'message' => 'Plano atualizado',
                'data' => $plano,
            ];
        } catch (ModelNotFoundException | Exception $e) {

            DB::rollBack();

            Log::error('Erro ao tentar atualizar o plano', [
                'error_message' => $e->getMessage(),
                'dados' => $data,
            ]);

            // Retornar erro com a mensagem
            $response = [
                'status' => false,
                'message' => 'Plano não atualizado',
            ];
        }
        return $response;
    }

    /**
     * Desassocia um produto a um plano
     *
     * @param int $planoId O ID do plano a ter produto desassociado
     * @param int $produtoId O ID do produto a ser desassociado ao plano
     * @return array{status: bool, message: string, data: \App\Models\Plano|null}
     * @throws ModelNotFoundException Se o plano não for encontrado
     * @throws Exception Se houver falha durante a batalha
     */
    public function destroyDesassociarProduto($planoId, $produtoId)
    {
        $planos = null;
        $produto = null;
        try {

            $planos = Plano::findOrFail($planoId);
            $produto = Produto::findOrFail($produtoId);

            Log::info('Iniciando desassociação do produto', [
                'plano_id' => $planoId,
                'produto_id' => $produtoId
            ]);

            $planos->produtos()->detach($produto->id);


            Log::info('Produto desassociado do plano', [
                'plano_id' => $planoId,
                'produto_id' => $produtoId
            ]);

            PlanoProdutoLog::create([
                'plano_id' => $planoId,
                'produto_id' => $produtoId,
                'action' => 'Removido',
            ]);


            $response = [
                'status' => true,
                'message' => 'Produto desassociado do plano com sucesso.',
                'data' => $planos,
            ];
        } catch (ModelNotFoundException | Exception $e) {

            Log::error('Erro ao tentar desassociar produto', [
                'error_message' => $e->getMessage(),
                'plano_id' => $planoId,
                'produto_id' => $produtoId
            ]);

            $response = [
                'status' => false,
                'message' => 'Erro ao tentar desassociar o produto do plano.',
            ];
        }

        return $response;
    }

    /**
     * Exclui um plano existente pelo ID
     *
     * @param int $planoId O ID do plano a ser exluido
     * @return array{status: bool, message: string, data: \App\Models\Plano|null}
     * @throws ModelNotFoundException Se o plano não for encontrado
     * @throws Exception Se houver falha durante a batalha
     */

    public function destroyPlanosPorId($planoId)
    {
        $planos = null;
        try {
            $planos = Plano::findOrFail($planoId);

            Log::info('Iniciando exclusão', ['dados' => $planoId]);

            $planos->delete();

            Log::info('Plano excluído!', ['produtos' => $planos]);

            $reponse = [
                'status' => true,
                'message' => 'Plano excluído',
                'data' => $planos,
            ];
        } catch (ModelNotFoundException | Exception $e) {

            Log::error('Erro ao tentar excluir produto', [
                'error_message' => $e->getMessage(),
                'dados' => $planoId,
            ]);

            $reponse = [
                'status' => false,
                'message' => 'Plano não excluído',
            ];
        }
        return $reponse;
    }
}
