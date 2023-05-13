<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;



class RemetenteController extends Controller
{
    public function index()
    {
        // Recebe as notas da função buscarNotas
        $notas = $this->buscarNotas();

        // Função para organizar as notas, fazer os cálculos e listas as notas organizadas
        $notasPorRemetente = $this->calcularInformacoes($notas);

        // Retorna a view com as notas dos remetentes para serem visualizadas em tela
        return view('index', compact('notasPorRemetente'));
        //return response()->json([
        //   'notas_por_remetente' => $notasPorRemetente,
        //], 200);

    }

    public function buscarNotas()
    {
        $url = 'http://homologacao3.azapfy.com.br/api/ps/notas';
        // recupera os dados da API
        $response = Http::get($url);

        // verifica se foi possível acessar a API, caso contrário encerra a execução
        if ($response === false) {
            die("Não foi possível conectar com a API.");
        }

        // retorna os dados da API em json 
        $notas = $response->json();
        return $notas;
    }

    public function calcularInformacoes($notas)
    {
        // Inicia um array para armazenar as informações
        $notasPorRemetente = [];

        // Para cada nota, organiza e faz os cálculos necessários
        foreach ($notas as $nota) {
            // Agrupa as notas por cnpj e inicia os campos
            if (!isset($notasPorRemetente[$nota['cnpj_remete']])) {
                $notasPorRemetente[$nota['cnpj_remete']] = [
                    'nome_remete' => $nota['nome_remete'],
                    'valor_total' => 0,
                    'valor_entregue' => 0,
                    'valor_nao_entregue' => 0,
                    'valor_atraso' => 0,
                    'notas' => [],
                ];
            }

            // Mostra as notas de cada cnpj com os dados da entrega
            $notasPorRemetente[$nota['cnpj_remete']]['notas'][] = $nota;
            // Valor total da nota
            $notasPorRemetente[$nota['cnpj_remete']]['valor_total'] += $nota['valor'];

            // Verifica as entregas que foram COMPRAVADAS ou estão em ABERTO
            if ($nota['status'] === 'COMPROVADO') {

                // Verifica se existe atraso na entrega, se tiver soma no valor atrasado
                $diasAtraso = $this->calcularDiasAtraso($nota['dt_emis'], $nota['dt_entrega']);
                if ($diasAtraso > 2) {
                    $notasPorRemetente[$nota['cnpj_remete']]['valor_atraso'] += $nota['valor'];
                }
                else {
                    //Caso não tenha atraso, soma no valor entregue
                    $notasPorRemetente[$nota['cnpj_remete']]['valor_entregue'] += $nota['valor'];
                }

            } else {
                // Se o status não for COMPROVADO, soma ao valor não entregue
                $notasPorRemetente[$nota['cnpj_remete']]['valor_nao_entregue'] += $nota['valor'];
            }
        }

        // Retorna as notas organizadas para o index
        return $notasPorRemetente;
    }

    public function calcularDiasAtraso($dataEntrega, $dataEmissao)
    {
        // Inicia as variaveis de acordo com as datas da API
        $dataEntrega = \DateTime::createFromFormat('d/m/Y H:i:s', $dataEntrega);
        $dataEmissao = \DateTime::createFromFormat('d/m/Y H:i:s', $dataEmissao);

        //Faz o calculo da diferença entre a dt de entrega e emissão
        $diferenca = $dataEntrega->diff($dataEmissao);

        // Recupera os dias, horas e minutos em atraso 
        $diasAtraso = $diferenca->days;
        $horasAtraso = $diferenca->h;
        $minutosAtraso = $diferenca->i;

        // Para a entrega ser válida ela precisa ser feita em no máximo dois dias.
        // O if abaixo verifica se o prazo de entrega foi feita em no máximo de dois dias e se tem atraso em horas ou minutos
        // Caso tenha atraso em min ou horas, adiciona mais um dia de atraso a entrega

        if($diasAtraso == 2 && ($horasAtraso > 0 || $minutosAtraso > 0))
            $diasAtraso++;

        // retorna os dias de atraso dos pedidos
        return $diasAtraso;
    }

    public function enviarNotas()
    {
        // Recupera as notas e organiza elas através da função calcularInformações
        $notas = $this->buscarNotas();
        $notasPorRemetente = $this->calcularInformacoes($notas);

        // Retorna as notas em json para api
        return response()->json([
           'notas_por_remetente' => $notasPorRemetente,
        ], 200);
    }

}
