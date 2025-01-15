<?php

namespace Database\Seeders;

use App\Models\Plano;
use App\Models\Produto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssinaturaSeeder extends Seeder
{
    /**
     * Executa os seeds para popular a tabela de planos e produtos
     *
     * Cria planos e produtos e associa os produtos aos planos correspondentes
     *
     * 1- Cria dois planos: "Claro Pós 50GB" e "Claro Controle 25GB"
     * 2- Cria três produtos: "Internet", "Internet" (outro pacote) e "WhatsApp Ilimitado"
     * 3- Associa os produtos aos planos, criando as relações de N:N
     *
     * @return void
     */
    public function run(): void
    {
        $plano1 = Plano::create(['nome'=> "Claro Pós 50GB", 'descricao'=> 'Plano pós-pago com 50GB de internet e aplicativos ilimitados.']);
        $plano2 = Plano::create(['nome'=> "Claro Controle 25GB", 'descricao'=> 'Plano controle com 25GB de internet e WhatsApp ilimitado.']);

        $produto1 = Produto::create(['nome'=> 'Internet', 'descricao'=> '25GB de internet + 25GB para redes sociais e vídeos.']);
        $produto2 = Produto::create(['nome'=> 'Internet', 'descricao'=> '15GB de internet + 5GB para YouTube + 5GB de bônus.']);
        $produto3 = Produto::create(['nome'=> 'WhatsApp Ilimitado', 'descricao'=> 'Mensagens e chamadas ilimitadas no WhatsApp.']);

        $plano1->produtos()->attach($produto1->id);
        $plano2->produtos()->attach([$produto1->id, $produto2->id, $produto3->id]);
    }
}
