@extends('layouts.master')

@section('title', isset($creating) ? 'Nova Tarefa' : (isset($viewing) ? 'Visualizar Tarefa' : 'Editar Tarefa'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/form-projetos.css') }}">
@endpush

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1>
            {{ isset($creating) ? '📋 Nova Tarefa' : (isset($viewing) ? '👁️ Visualizar Tarefa' : '✏️ Editar Tarefa') }}
        </h1>
    </div>

    <form action="{{ isset($creating) ? route('tasks.store') : route('tasks.update', $task->id) }}"
          method="POST"
          id="taskForm"
          >
        @csrf
        @if(!isset($creating) && !isset($viewing))
            @method('PUT')
        @endif

        <!-- Input "Descrição" -->
        <div class="form-group">
            <label for="descricao">
                Descrição da Tarefa
                <span class="required">*</span>
            </label>
            <textarea
                class="form-control @error('descricao') is-invalid @enderror"
                id="descricao"
                name="descricao"
                placeholder="Descreva a tarefa a ser realizada"
                rows="4"
                {{ isset($viewing) ? 'readonly' : 'required' }}
                maxlength="1000"
            >{{ old('descricao', $task->descricao ?? '') }}</textarea>
            @error('descricao')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            @if(!isset($viewing))
                <small class="help-text">A descrição deve ser clara e objetiva (máximo 1000 caracteres)</small>
            @endif
        </div>

        <!-- Input "Projeto" -->
        <div class="form-group">
            <label for="projeto_id">
                Projeto
                <span class="required">*</span>
            </label>
            <select
                class="form-control @error('projeto_id') is-invalid @enderror"
                id="projeto_id"
                name="projeto_id"
                {{ isset($viewing) ? 'disabled' : 'required' }}
            >
                <option value="">Selecione o projeto</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}"
                        {{ (old('projeto_id', $task->projeto_id ?? '') == $project->id) ? 'selected' : '' }}>
                        {{ $project->nome }}
                    </option>
                @endforeach
            </select>
            @error('projeto_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            @if(!isset($viewing))
                <small class="help-text">Toda tarefa deve estar associada a um projeto</small>
            @endif
        </div>

        <!-- Linha com Data Início e Data Fim -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="data_inicio">
                    Data de Início
                </label>
                <input
                    type="date"
                    class="form-control @error('data_inicio') is-invalid @enderror"
                    id="data_inicio"
                    name="data_inicio"
                    value="{{ old('data_inicio', isset($task) && $task->data_inicio ? $task->data_inicio->format('Y-m-d') : '') }}"
                    {{ isset($viewing) ? 'readonly' : '' }}
                >
                @error('data_inicio')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                @if(!isset($viewing))
                    <small class="help-text">Campo opcional</small>
                @endif
            </div>

            <div class="form-group col-md-6">
                <label for="data_fim">
                    Data de Fim
                </label>
                <input
                    type="date"
                    class="form-control @error('data_fim') is-invalid @enderror"
                    id="data_fim"
                    name="data_fim"
                    value="{{ old('data_fim', isset($task) && $task->data_fim ? $task->data_fim->format('Y-m-d') : '') }}"
                    {{ isset($viewing) ? 'readonly' : '' }}
                >
                @error('data_fim')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                @if(!isset($viewing))
                    <small class="help-text">Campo opcional</small>
                @endif
            </div>
        </div>

        <!-- Input "Tarefa Predecessora" -->
        <div class="form-group">
            <label for="tarefa_predecessora_id">
                Tarefa Predecessora
            </label>
            <select
                class="form-control @error('tarefa_predecessora_id') is-invalid @enderror"
                id="tarefa_predecessora_id"
                name="tarefa_predecessora_id"
                {{ isset($viewing) ? 'disabled' : '' }}
            >
                <option value="">Nenhuma (tarefa independente)</option>
                @foreach($tasks as $taskOption)
                    <option value="{{ $taskOption->id }}"
                        {{ (old('tarefa_predecessora_id', $task->tarefa_predecessora_id ?? '') == $taskOption->id) ? 'selected' : '' }}>
                        {{ Str::limit($taskOption->descricao, 80) }}
                    </option>
                @endforeach
            </select>
            @error('tarefa_predecessora_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            @if(!isset($viewing))
                <small class="help-text">Selecione se esta tarefa depende da conclusão de outra tarefa</small>
            @endif
        </div>

        <!-- Input "Status" -->
        @if(!isset($creating))
        <div class="form-group">
            <label for="concluida">
                Status da Tarefa
            </label>
            <select
                class="form-control @error('concluida') is-invalid @enderror"
                id="concluida"
                name="concluida"
                {{ isset($viewing) ? 'disabled' : '' }}
            >
                <option value="0" {{ (old('concluida', $task->concluida ?? 0) == 0) ? 'selected' : '' }}>
                    ❌ Não Concluída
                </option>
                <option value="1" {{ (old('concluida', $task->concluida ?? 0) == 1) ? 'selected' : '' }}>
                    ✅ Concluída
                </option>
            </select>
            @error('concluida')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            @if(!isset($viewing))
                <small class="help-text">Marque a tarefa como concluída quando finalizada</small>
            @endif
        </div>
        @endif

        <!-- Informações adicionais em modo de visualização -->
        @if(isset($viewing) && isset($task))
        <div class="info-section">
            <h3>Informações Adicionais</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Status:</strong>
                    <span class="status-badge {{ $task->concluida ? 'status-ativo' : 'status-inativo' }}">
                        {{ $task->concluida ? '✅ Concluída' : '❌ Não Concluída' }}
                    </span>
                </div>
                <div class="info-item">
                    <strong>Criado em:</strong>
                    <span>{{ $task->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <strong>Última atualização:</strong>
                    <span>{{ $task->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($task->tarefasSucessoras->count() > 0)
                <div class="info-item">
                    <strong>Tarefas Dependentes:</strong>
                    <span class="badge badge-warning">{{ $task->tarefasSucessoras->count() }} tarefa(s)</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Área de ações -->
        <div class="form-actions">
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                {{ isset($viewing) ? 'Voltar' : 'Cancelar' }}
            </a>
            @if(!isset($viewing))
                <button type="submit" class="btn btn-primary">
                    {{ isset($creating) ? 'Criar Tarefa' : 'Salvar Alterações' }}
                </button>
            @else
                <div>
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">
                        ✏️ Editar Tarefa
                    </a>
                    <button type="button"
                            class="btn {{ $task->concluida ? 'btn-warning' : 'btn-success' }}"
                            onclick="toggleTaskStatus({{ $task->id }})">
                        {{ $task->concluida ? '❌ Marcar como Não Concluída' : '✅ Marcar como Concluída' }}
                    </button>
                </div>
            @endif
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Validação de datas
    const dataInicioInput = document.getElementById('data_inicio');
    const dataFimInput = document.getElementById('data_fim');

    if (dataInicioInput && dataFimInput) {
        dataFimInput.addEventListener('change', function() {
            const dataInicio = dataInicioInput.value;
            const dataFim = dataFimInput.value;

            if (dataInicio && dataFim && dataFim < dataInicio) {
                showToast('A data de fim não pode ser anterior à data de início!', 'error');
                dataFimInput.value = '';
            }
        });
    }

    // Atualizar lista de tarefas predecessoras ao mudar o projeto
    const projetoSelect = document.getElementById('projeto_id');
    const predecessoraSelect = document.getElementById('tarefa_predecessora_id');

    @if(!isset($viewing))
    if (projetoSelect && predecessoraSelect) {
        projetoSelect.addEventListener('change', function() {
            const projetoId = this.value;

            if (!projetoId) {
                predecessoraSelect.innerHTML = '<option value="">Selecione um projeto primeiro</option>';
                predecessoraSelect.disabled = true;
                return;
            }

            // Buscar tarefas do projeto selecionado
            fetch(`/tasks/data?projeto_id=${projetoId}&concluida=0`)
                .then(response => response.json())
                .then(data => {
                    predecessoraSelect.innerHTML = '<option value="">Nenhuma (tarefa independente)</option>';

                    let taskCount = 0;
                    data.data.forEach(task => {
                        @if(!isset($creating))
                        // Não incluir a própria tarefa na lista
                        if (task.id != {{ $task->id ?? 'null' }}) {
                        @endif
                            const option = document.createElement('option');
                            option.value = task.id;
                            option.textContent = task.descricao.length > 80
                                ? task.descricao.substring(0, 80) + '...'
                                : task.descricao;
                            predecessoraSelect.appendChild(option);
                            taskCount++;
                        @if(!isset($creating))
                        }
                        @endif
                    });

                    predecessoraSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Erro ao carregar tarefas:', error);
                    showToast('Erro ao carregar a lista de tarefas predecessoras.', 'error');
                    predecessoraSelect.innerHTML = '<option value="">Erro ao carregar tarefas</option>';
                });
        });
    }
    @endif

    // Impede o envio do formulário em modo de visualização
    @if(isset($viewing))
    document.getElementById('taskForm').addEventListener('submit', function(e) {
        e.preventDefault();
        return false;
    });
    @else
    // Validação do formulário
    document.getElementById('taskForm').addEventListener('submit', function(e) {
        const descricao = document.getElementById('descricao').value.trim();
        const projetoId = document.getElementById('projeto_id').value;

        if (!descricao) {
            e.preventDefault();
            showToast('Por favor, preencha a descrição da tarefa.', 'warning');
            document.getElementById('descricao').focus();
            return false;
        }

        if (!projetoId) {
            e.preventDefault();
            showToast('Por favor, selecione o projeto.', 'warning');
            document.getElementById('projeto_id').focus();
            return false;
        }
    });
    @endif

    // Função para alternar status da tarefa
    function toggleTaskStatus(taskId) {
        @if(isset($task))
        const currentStatus = {{ $task->concluida ? 'true' : 'false' }};
        const actionText = currentStatus ? 'marcar como não concluída' : 'marcar como concluída';
        @else
        const actionText = 'alterar o status';
        @endif

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/tasks/' + taskId + '/toggle-status';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();

    }
</script>

<style>
    .form-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .form-row .form-group {
        flex: 1;
        min-width: 250px;
    }

    .info-section {
        margin-top: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #007bff;
    }

    .info-section h3 {
        margin-top: 0;
        margin-bottom: 15px;
        color: #333;
        font-size: 1.2rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .info-item strong {
        color: #666;
        font-size: 0.9rem;
    }

    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #000;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #000;
    }
</style>
@endpush
