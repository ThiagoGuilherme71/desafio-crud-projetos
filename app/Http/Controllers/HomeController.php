<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        return view('home.home');
    }

    public function getProjectsData()
    {
        // mock de dados
        $projects = [
            [
                'nome' => 'Sistema de Gestão Empresarial',
                'descricao' => 'Desenvolvimento de um sistema completo de gestão empresarial com módulos de vendas, estoque, financeiro e relatórios gerenciais integrados.',
                'status' => 'Ativo',
                'orcamento' => 150000.00
            ],
            [
                'nome' => 'Aplicativo Mobile E-commerce',
                'descricao' => 'Criação de aplicativo mobile para plataforma de e-commerce com integração de pagamento, carrinho de compras e sistema de avaliações.',
                'status' => 'Ativo',
                'orcamento' => 85000.50
            ],
            [
                'nome' => 'Portal de Notícias',
                'descricao' => 'Portal de notícias responsivo com painel administrativo, sistema de categorias, comentários e newsletter automático.',
                'status' => 'Inativo',
                'orcamento' => 45000.00
            ],
            [
                'nome' => 'Sistema de Reservas Online',
                'descricao' => 'Plataforma web para reservas de hotéis e pousadas com calendário interativo, pagamento online e gestão de disponibilidade.',
                'status' => 'Ativo',
                'orcamento' => 120000.75
            ],
            [
                'nome' => 'Dashboard Analytics',
                'descricao' => 'Dashboard analítico para visualização de dados em tempo real com gráficos interativos, relatórios customizáveis e exportação de dados.',
                'status' => 'Inativo',
                'orcamento' => 65000.00
            ]
        ];

        return response()->json([
            'data' => $projects
        ]);
    }
}
