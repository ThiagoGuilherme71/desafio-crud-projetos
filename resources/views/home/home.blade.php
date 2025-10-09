@extends('layouts.master')

@section('title', 'Home - CRUD Projetos')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">📊 Gerenciar Projetos</h1>
        <button class="btn-create" onclick="window.location.href='{{ route('projects.create') }}'">
            ➕ Novo Projeto
        </button>
    </div>

    <div class="datatable-container">
        <table id="projectsTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Nome do Projeto</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Orçamento</th>
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
                <h2>⚠️ Confirmar Exclusão</h2>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o projeto:</p>
                <p class="project-name" id="deleteProjectName"></p>
                <p class="warning-text">Esta ação não pode ser desfeita!</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        🗑️ Excluir Projeto
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#projectsTable').DataTable({
            ajax: {
                url: '{{ route("projects.data") }}',
                dataSrc: 'data'
            },
            columns: [
                { data: 'nome' },
                {
                    data: 'descricao',
                    render: function(data) {
                        if (data.length > 100) {
                            return '<span class="description-cell" title="' + data + '">' +
                                   data.substring(0, 100) + '...</span>';
                        }
                        return '<span class="description-cell">' + data + '</span>';
                    }
                },
                {
                    data: 'ativo',
                    render: function(data) {
                        const statusClass = data ? 'status-ativo' : 'status-inativo';
                        let text = data ? 'Ativo' : 'Inativo';
                        return '<span class="status-badge ' + statusClass + '">' + text + '</span>';
                    }
                },
                {
                    data: 'orcamento',
                    render: function(data) {
                        return 'R$ ' + parseFloat(data).toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
                            <div class="action-buttons">
                                <button class="btn-action btn-view" title="Visualizar" onclick="window.location.href='/projects/${row.id}'">
                                    👁️
                                </button>
                                <button class="btn-action btn-edit" title="Editar" onclick="window.location.href='/projects/${row.id}/edit'">
                                    ✏️
                                </button>
                                <button class="btn-action btn-delete" title="Excluir" onclick="openDeleteModal(${row.id}, '${row.nome}')">
                                    🗑️
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/2.0.0/i18n/pt-BR.json'
            },
            pageLength: 10,
            order: [[0, 'asc']],
            responsive: true
        });
    });

    // Funções para controlar o modal de exclusão
    function openDeleteModal(projectId, projectName) {
        document.getElementById('deleteProjectName').textContent = projectName;
        document.getElementById('deleteForm').action = '/projects/' + projectId;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    // Fechar modal ao pressionar ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endpush

