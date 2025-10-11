@extends('layouts.master')

@section('title', 'Tarefas - CRUD Projetos')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/task.css') }}">

@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">📋 Gerenciar Tarefas</h1>
        <button class="btn-create" onclick="window.location.href='{{ route('tasks.create') }}'">
            Nova Tarefa
        </button>
    </div>

    <!-- Filtros -->
    <div class="filters-container">
        <div class="filters-row">
            <div class="filter-group">
                <label for="projeto_filter">Projeto:</label>
                <select id="projeto_filter" class="form-control" style="width: 200px;">
                    <option value="">Todos os Projetos</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="status_filter">Status:</label>
                <select id="status_filter" class="form-control" style="width: 150px;">
                    <option value="">Todos</option>
                    <option value="0">Não Concluída</option>
                    <option value="1">Concluída</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="search_input">Buscar:</label>
                <input type="text" id="search_input" class="form-control" placeholder="Descrição da tarefa" style="width: 250px;">
            </div>

            <div class="filter-buttons">
                <button id="filterBtn" class="btn btn-primary">
                    Filtrar
                </button>
                <button id="clearBtn" class="btn btn-secondary">
                    Limpar
                </button>
            </div>
        </div>
    </div>

    <div class="datatable-container">
        <table id="tasksTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Projeto</th>
                    <th>Data Início</th>
                    <th>Data Fim</th>
                    <th>Predecessora</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dados serão carregados via AJAX -->
            </tbody>
        </table>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteModal" class="modal" style="display: none;">
        <div class="modal-overlay" onclick="closeDeleteModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmar Exclusão</h2>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir a tarefa:</p>
                <p class="project-name" id="deleteTaskDescription"></p>
                <p class="warning-text">Esta ação não pode ser desfeita!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        🗑️ Excluir Tarefa
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let tasksTable;

    $(document).ready(function() {
        tasksTable = $('#tasksTable').DataTable({
            ajax: {
                url: '{{ route("tasks.data") }}',
                data: function(d) {
                    d.projeto_id = $('#projeto_filter').val();
                    d.concluida = $('#status_filter').val();
                    d.search = $('#search_input').val();
                },
                dataSrc: 'data'
            },
            columns: [
                { data: 'id', width: '50px' },
                {
                    data: 'descricao',
                    render: function(data) {
                        if (data.length > 80) {
                            return '<span class="description-cell" title="' + data + '">' +
                                   data.substring(0, 80) + '...</span>';
                        }
                        return '<span class="description-cell">' + data + '</span>';
                    }
                },
                { data: 'projeto' },
                { data: 'data_inicio' },
                { data: 'data_fim' },
                {
                    data: 'predecessora',
                    render: function(data) {
                        if (data === 'Nenhuma') {
                            return '<span style="color: #999;">Nenhuma</span>';
                        }
                        if (data.length > 40) {
                            return '<span title="' + data + '">' + data.substring(0, 40) + '...</span>';
                        }
                        return data;
                    }
                },
                {
                    data: 'concluida',
                    render: function(data, type, row) {
                        const statusClass = data ? 'status-ativo' : 'status-inativo';
                        const text = data ? 'Concluída' : 'Não Concluída';
                        return '<span class="status-badge ' + statusClass + '">' + text + '</span>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        const toggleIcon = row.concluida ? '❌' : '✅';
                        const toggleTitle = row.concluida ? 'Marcar como Não Concluída' : 'Marcar como Concluída';

                        return `
                            <div class="action-buttons">
                                <button class="btn-action btn-view" title="Visualizar" onclick="window.location.href='/tasks/${row.id}'">
                                    👁️
                                </button>
                                <button class="btn-action btn-edit" title="Editar" onclick="window.location.href='/tasks/${row.id}/edit'">
                                    ✏️
                                </button>
                                <button class="btn-action btn-success" title="${toggleTitle}" onclick="toggleTaskStatus(${row.id})">
                                    ${toggleIcon}
                                </button>
                                <button class="btn-action btn-delete" title="Excluir" onclick="openDeleteModal(${row.id}, '${escapeHtml(row.descricao)}')">
                                    🗑️
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            pageLength: 10,
            order: [[0, 'desc']],
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
            }
        });

        // Evento de filtro
        $('#filterBtn').on('click', function() {
            tasksTable.ajax.reload();
        });

        // Limpar filtros
        $('#clearBtn').on('click', function() {
            $('#projeto_filter').val('');
            $('#status_filter').val('');
            $('#search_input').val('');
            tasksTable.ajax.reload();
        });

        // Filtrar ao pressionar Enter no campo de busca
        $('#search_input').on('keypress', function(e) {
            if (e.which === 13) {
                tasksTable.ajax.reload();
            }
        });
    });

    // Alternar status da tarefa
    function toggleTaskStatus(taskId) {
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

    // Funções para controlar o modal de exclusão
    function openDeleteModal(taskId, taskDescription) {
        document.getElementById('deleteTaskDescription').textContent = taskDescription;
        document.getElementById('deleteForm').action = '/tasks/' + taskId;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    // Função para escapar HTML
    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Fechar modal ao pressionar ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endpush
