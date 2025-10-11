<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Lista todas as tarefas com filtros opcionais
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $projects = Project::where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('tarefas.index', compact('projects'));
    }

    /**
     * Exibe o formulário para criar uma nova tarefa
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $projects = Project::where('ativo', true)
            ->orderBy('nome')
            ->get();
        $tasks = Task::where('concluida', false)
            ->orderBy('descricao')
            ->get();

        return view('tarefas.form', [
            'creating'  => true,
            'projects'  => $projects,
            'tasks'     => $tasks
        ]);
    }

    /**
     * Salva uma nova tarefa no banco de dados
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:1000',
            'projeto_id' => 'required|exists:projects,id,ativo,1', // só projetos ativos
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'tarefa_predecessora_id' => 'nullable|exists:tasks,id',
        ], [
            'descricao.required' => 'A descrição da tarefa é obrigatória.',
            'projeto_id.required' => 'O projeto é obrigatório.',
            'projeto_id.exists' => 'O projeto selecionado não existe ou não está ativo.',
            'data_fim.after_or_equal' => 'A data de fim não pode ser anterior à data de início.',
            'tarefa_predecessora_id.exists' => 'A tarefa predecessora selecionada não existe.',
        ]);
        // regra de duplicidade
        $exists = Task::where('projeto_id', $validated['projeto_id'])
            ->where('descricao', $validated['descricao'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'descricao' => 'Já existe uma tarefa com esta descrição neste projeto.',
            ]);
        }

        // tarefa predecessora deve ser do mesmo projeto
        if (isset($validated['tarefa_predecessora_id'])) {
            $predecessora = Task::find($validated['tarefa_predecessora_id']);
            if ($predecessora->projeto_id != $validated['projeto_id']) {
                throw ValidationException::withMessages([
                    'tarefa_predecessora_id' => 'A tarefa predecessora deve pertencer ao mesmo projeto.',
                ]);
            }
        }

        $validated['concluida'] = false; // garantir que nova tarefa inicia como não concluída

        $task = Task::create($validated);

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Tarefa criada com sucesso!');
    }

    /**
     * Exibe uma tarefa específica (modo visualização)
     *
     * @param Task $task
     * @return \Illuminate\View\View
     */
    public function show(Task $task)
    {
        $task->load(['projeto', 'tarefaPredecessora', 'tarefasSucessoras']);

        $projects = Project::where('ativo', true)
            ->orderBy('nome')
            ->get();
        $tasks = Task::where('id', '!=', $task->id)
            ->where('projeto_id', $task->projeto_id)
            ->orderBy('descricao')
            ->get();

        return view('tarefas.form', [
            'task'      => $task,
            'viewing'   => true,
            'projects'  => $projects,
            'tasks'     => $tasks
        ]);
    }

    /**
     * Exibe o formulário para editar uma tarefa
     *
     * @param Task $task
     * @return \Illuminate\View\View
     */
    public function edit(Task $task)
    {
        $projects = Project::where('ativo', true)
            ->orderBy('nome')
            ->get();
        $tasks = Task::where('id', '!=', $task->id)
            ->where('projeto_id', $task->projeto_id)
            ->orderBy('descricao')
            ->get();

        return view('tarefas.form', [
            'task'      => $task,
            'projects'  => $projects,
            'tasks'     => $tasks
        ]);
    }

    /**
     * Atualiza uma tarefa existente no banco de dados
     *
     * @param Request $request
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:1000',
            'projeto_id' => 'required|exists:projects,id',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'tarefa_predecessora_id' => 'nullable|exists:tasks,id',
            'concluida' => 'boolean',
        ], [
            'descricao.required' => 'A descrição da tarefa é obrigatória.',
            'projeto_id.required' => 'O projeto é obrigatório.',
            'projeto_id.exists' => 'O projeto selecionado não existe.',
            'data_fim.after_or_equal' => 'A data de fim não pode ser anterior à data de início.',
            'tarefa_predecessora_id.exists' => 'A tarefa predecessora selecionada não existe.',
        ]);

        // regra de duplicidade
        $exists = Task::where('projeto_id', $validated['projeto_id'])
            ->where('descricao', $validated['descricao'])
            ->where('id', '!=', $task->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'descricao' => 'Já existe uma tarefa com esta descrição neste projeto.',
            ]);
        }

        // Validação n permitir que a tarefa seja predecessora dela mesma
        if (isset($validated['tarefa_predecessora_id']) && $validated['tarefa_predecessora_id'] == $task->id) {
            throw ValidationException::withMessages([
                'tarefa_predecessora_id' => 'Uma tarefa não pode ser predecessora de si mesma.',
            ]);
        }

        // Validação tarefa predecessora deve ser do mesmo projeto
        if (isset($validated['tarefa_predecessora_id'])) {
            $predecessora = Task::find($validated['tarefa_predecessora_id']);
            if ($predecessora->projeto_id != $validated['projeto_id']) {
                throw ValidationException::withMessages([
                    'tarefa_predecessora_id' => 'A tarefa predecessora deve pertencer ao mesmo projeto.',
                ]);
            }
        }

        $task->update($validated);

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Tarefa atualizada com sucesso!');
    }

    /**
     * Remove uma tarefa do banco de dados
     *
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        // Verifica se a tarefa pode ser excluída
        if (!$task->podeSerExcluida()) {
            return redirect()
                ->back()
                ->with('error', 'Esta tarefa não pode ser excluída pois é predecessora de outras tarefas.');
        }
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tarefa excluída com sucesso!');
    }

    /**
     * Atualiza o status da tarefa (concluída/não concluída)
     *
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(Task $task)
    {
        $task->concluida = !$task->concluida;
        $task->save();

        $status = $task->concluida ? 'concluída' : 'não concluída';

        return redirect()
            ->back()
            ->with('success', "Tarefa marcada como {$status}!");
    }

    /**
     * Retorna dados das tarefas em formato JSON para o DataTable
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasksData(Request $request)
    {
        $query = Task::with(['projeto', 'tarefaPredecessora']);

        // Filtro por projeto
        if ($request->has('projeto_id') && $request->projeto_id) {
            $query->porProjeto($request->projeto_id);
        }

        // Filtro por status (concluída ou n)
        if ($request->has('concluida') && $request->concluida !== '') {
            if ($request->concluida === '1' || $request->concluida === 'true') {
                $query->concluidas();
            } elseif ($request->concluida === '0' || $request->concluida === 'false') {
                $query->naoConcluidas();
            }
        }

        // Pesquisa por descrição
        if ($request->has('search') && $request->search) {
            $query->where('descricao', 'like', '%' . $request->search . '%');
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $tasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'descricao' => $task->descricao,
                    'projeto' => $task->projeto->nome ?? 'N/A',
                    'projeto_id' => $task->projeto_id,
                    'data_inicio' => $task->data_inicio ? $task->data_inicio->format('d/m/Y') : 'N/A',
                    'data_fim' => $task->data_fim ? $task->data_fim->format('d/m/Y') : 'N/A',
                    'predecessora' => $task->tarefaPredecessora->descricao ?? 'Nenhuma',
                    'concluida' => $task->concluida,
                    'concluida_label' => $task->concluida ? 'Concluída' : 'Não Concluída',
                    'created_at' => $task->created_at->format('d/m/Y H:i'),
                ];
            })
        ]);
    }
}
