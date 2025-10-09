<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjetosController extends Controller
{
    /**
     * Exibe a view de listagem de projetos
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            return view('projetos.index');
        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Erro ao carregar a página: ' . $e->getMessage());
        }
    }

    /**
     * Exibe o formulário para criar um novo projeto
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            return view('projetos.form', [
                'creating' => true
            ]);
        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Erro ao carregar o formulário: ' . $e->getMessage());
        }
    }

    /**
     * Salva um novo projeto no banco de dados
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validações
            $validated = $request->validate([
                'nome' => 'required|string|max:255|unique:projects,nome',
                'descricao' => 'nullable|string',
                'ativo' => 'required|boolean',
                'orcamento' => 'nullable|numeric|min:0'
            ], [
                'nome.required' => 'O nome do projeto é obrigatório.',
                'nome.unique' => 'Já existe um projeto com este nome.',
                'ativo.required' => 'O status do projeto é obrigatório.',
                'orcamento.numeric' => 'O orçamento deve ser um valor numérico.',
                'orcamento.min' => 'O orçamento não pode ser negativo.'
            ]);

            Project::create($validated);

            return redirect()->route('home')
                ->with('success', 'Projeto criado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar o projeto: ' . $e->getMessage());
        }
    }

    /**
     * Exibe os detalhes de um projeto (modo visualização)
     *
     * @param string $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $id)
    {
        try {
            $project = Project::findOrFail($id);

            return view('projetos.form', [
                'project' => $project,
                'viewing' => true
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('home')
                ->with('error', 'Projeto não encontrado.');
        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Erro ao visualizar o projeto: ' . $e->getMessage());
        }
    }

    /**
     * Exibe o formulário para editar um projeto
     *
     * @param string $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(string $id)
    {
        try {
            $project = Project::findOrFail($id);

            return view('projetos.form', [
                'project' => $project
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('home')
                ->with('error', 'Projeto não encontrado.');
        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Erro ao carregar o formulário: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza um projeto no banco de dados
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        try {
            $project = Project::findOrFail($id);

            $validated = $request->validate([
                'nome' => 'required|string|max:255|unique:projects,nome,' . $id,
                'descricao' => 'nullable|string',
                'ativo' => 'required|boolean',
                'orcamento' => 'nullable|numeric|min:0'
            ], [
                'nome.required' => 'O nome do projeto é obrigatório.',
                'nome.unique' => 'Já existe outro projeto com este nome.',
                'ativo.required' => 'O status do projeto é obrigatório.',
                'orcamento.numeric' => 'O orçamento deve ser um valor numérico.',
                'orcamento.min' => 'O orçamento não pode ser negativo.'
            ]);

            $project->update($validated);

            return redirect()->route('home')
                ->with('success', 'Projeto atualizado com sucesso!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('home')
                ->with('error', 'Projeto não encontrado.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar o projeto: ' . $e->getMessage());
        }
    }

    /**
     * Remove um projeto do banco de dados
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        try {
            $project = Project::findOrFail($id);
            $projectName = $project->nome;
            $project->delete();

            return redirect()->route('home')
                ->with('success', "Projeto '$projectName' excluído com sucesso!");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('home')
                ->with('error', 'Projeto não encontrado.');
        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Erro ao excluir o projeto: ' . $e->getMessage());
        }
    }

    /**
     * Retorna todos os projetos em formato JSON para o DataTable
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProjectsData()
    {
        try {
            $projects = Project::all();

            return response()->json([
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao carregar os projetos: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
