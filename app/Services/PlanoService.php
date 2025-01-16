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
     * Recupera todos os planos ordenados por ID
     *
     * Método retorna uma lista de planos da tabela de planos
     *
     * @return array Retorna um array com o status e os planos
     */
    public function getPlanos()
    {
        try {
            $planos = Plano::orderBy('id', 'ASC')->get();

            Log::info('Listando todos os planos', ['quantidade' => $planos->count()]);

            return [
                'status' => true,
                'planos' => $planos,
            ];
        } catch (Exception $e) {
            Log::error('Erro ao listar planos', [
                'error_message' => $e->getMessage(),
            ]);

            return [
                'status' => false,
                'message' => 'planos não foram listados',
            ];
        }
    }

    /**
     * Recupera um plano por ID
     *
     * Método retorna o plano recuperado
     *
     * @param int $planoId O ID do plano a ser buscado
     * @return array Retorna um array com o status e os planos
     */
    public function getIdPlanos($planoId)
    {
        try {
            $planos = Plano::findOrFail($planoId);
            Log::info('Trazendo plano por Id', ['dados' => $planos]);

            $response = [
                'status' => true,
                'planos' => $planos,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            Log::error('Erro ao tentar trazer plano', [
                'error_message' => $e->getMessage(),
                'planoId' => $planoId
            ]);

            $response = [
                'status' => false,
                'planos' => null,
                'message' => 'plano não foi listado',
            ];
        }
        return $response;
    }

    /**
     * Recupera todos os planos junto com seus produtos associados
     *
     * Método retorna uma lista de planos, com seus respectivos produtos associados, utilizando
     * a N:N entre planos e produtos
     *
     * @return array Retorna um array com o status e os planos com produtos
     */
    public function getPlanosProdutos()
    {
        $planos = Plano::with(['produtos', 'logs'])->get();

        return [
            'status' => true,
            'planos' => $planos,
        ];
    }

    /**
     * Recuperar todos os logs de planos e produtos
     *
     * @return array
     */
    public function getTodosLogs()
    {
        $logs = PlanoProdutoLog::with('produto')->paginate(5);
        return [
            'status' => true,
            'logs' => $logs,
        ];
    }

    /**
     * Novo plano e associa um produto a ele
     *
     * Método cria um novo plano com os dados fornecidos e associa o plano a um produto especificado
     * É realizada dentro de uma transação do banco de dados para garantir a consistência
     * Em caso de falha, a transação é revertida
     *
     * @param array $data Os dados do plano a ser criado
     * @param int $produtoId O ID do produto a ser associado ao plano
     * @return array Retorna um array contendo o status da operação
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
                'planos' => $planos,
                'message' => 'Plano Cadastrado'
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $reponse = [
                'status' => false,
                'planos' => null,
                'message' => $e->getMessage(),
            ];
        }
        return $reponse;
    }


    /**
     * Associa um produto a um plano
     *
     * Método encontra um plano e um produto com base nos IDs fornecidos e associa o produto ao plano
     * Depois da associação, ele retorna um array com o status da operação e os dados do plano e produto associados
     *
     * @param int $planoId O ID do plano ao qual o produto será associado
     * @param int $produtoId O ID do produto a ser associado ao plano
     * @return array Retorna um array com o status da operação e os dados do plano e do produto
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
            'status' => true,
            'planos' => $planos,
        ];
    }

    /**
     * Atualiza os dados de um plano existente
     *
     * Método recebe os novos dados de um plano e realiza a atualização no sistema
     *
     * Ele utiliza o serviço PlanoService para atualizar o plano e retorna um array
     * indicando o status da operação e os dados do plano atualizado
     *
     * @param array $data Os dados atualizados do plano
     * @param int $planoId O ID do plano a ser atualizado
     * @return array Retorna um array contendo o status da operação, os dados do plano atualizado e uma mensagem
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


            return [
                'status' => true,
                'plano' => $plano,
                'message' => 'Plano atualizado',
            ];
        } catch (ModelNotFoundException | Exception $e) {

            DB::rollBack();

            Log::error('Erro ao tentar atualizar o plano', [
                'error_message' => $e->getMessage(),
                'dados' => $data,
            ]);

            // Retornar erro com a mensagem
            return [
                'status' => false,
                'plano' => null,
                'message' => 'Plano não atualizado',
            ];
        }
    }



    /**
     * Desassocia um produto a um plano
     *
     * Método encontra um plano e um produto com base nos IDs fornecidos e Desassocia o produto ao plano
     * Depois da desassociação, ele retorna um array com o status da operação e os dados do plano e produto
     *
     * @param int $planoId O ID do plano ao qual o produto será desassociado
     * @param int $produtoId O ID do produto a ser desassociado ao plano
     * @return array Retorna um array com o status da operação e os dados do plano e do produto
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
                'planos' => $planos,
                'message' => 'Produto desassociado do plano com sucesso.',
            ];
        } catch (ModelNotFoundException | Exception $e) {

            Log::error('Erro ao tentar desassociar produto', [
                'error_message' => $e->getMessage(),
                'plano_id' => $planoId,
                'produto_id' => $produtoId
            ]);

            $response = [
                'status' => false,
                'planos' => null,
                'message' => 'Erro ao tentar desassociar o produto do plano.',
            ];
        }

        return $response;
    }


    /**
     * Exclui um plano específico pelo ID
     *
     * Método busca um plano pelo ID fornecido, tenta excluí-lo do banco de dados e retorna
     * um array indicando o sucesso ou falha da operação, incluindo os dados do plano excluído ou não
     *
     * @param int $planoId O ID do plano a ser excluído
     * @return array Retorna um array com o status da operação e o plano excluído
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
                'planos' => $planos,
                'message' => 'Plano excluído',
            ];
        } catch (ModelNotFoundException | Exception $e) {

            Log::error('Erro ao tentar excluir produto', [
                'error_message' => $e->getMessage(),
                'dados' => $planoId,
            ]);

            $reponse = [
                'status' => false,
                'planos' => null,
                'message' => 'Plano não excluído',
            ];
        }
        return $reponse;
    }
}
