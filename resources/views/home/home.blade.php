@extends('layouts.master')

@section('title', 'Home - CRUD Projetos')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
    }
    .btn-secondary {
        background: #e2e8f0;
        color: #4a5568;
    }

    .btn-secondary:hover {
        background: #cbd5e0;
    }
    .btn-create {
        background: linear-gradient(135deg, #53b4e3 0%, #307192ff 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(83, 180, 227, 0.4);
    }

    .datatable-container {
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    /* Status badges */
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-ativo {
        background: #c6f6d5;
        color: #276749;
    }

    .status-inativo {
        background: #fed7d7;
        color: #c53030;
    }

    /* Descrição truncada */
    .description-cell {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Ações */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        font-size: 16px;
    }

    .btn-view {
        background: #bee3f8;
        color: #2c5282;
    }

    .btn-view:hover {
        background: #90cdf4;
    }

    .btn-edit {
        background: #fefcbf;
        color: #744210;
    }

    .btn-edit:hover {
        background: #faf089;
    }

    .btn-delete {
        background: #fed7d7;
        color: #c53030;
    }

    .btn-delete:hover {
        background: #fc8181;
    }

    /* DataTable customização */
    table.dataTable thead th {
        background: #f7fafc;
        font-weight: 600;
        color: #2d3748;
        border-bottom: 2px solid #e2e8f0;
    }

    table.dataTable tbody tr:hover {
        background: #f7fafc;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        margin-left: 0.5rem;
    }
    .text-center {
        text-align: center !important;
    }

    /* Modal Styles */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .modal-content {
        position: relative;
        background: white;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 500px;
        width: 90%;
        z-index: 10000;
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
        color: #2d3748;
    }

    .modal-body {
        padding: 2rem 1.5rem;
    }

    .modal-body p {
        margin: 0.5rem 0;
        color: #4a5568;
        font-size: 1rem;
    }

    .modal-body .project-name {
        font-weight: 700;
        font-size: 1.1rem;
        color: #2d3748;
        padding: 0.75rem;
        background: #f7fafc;
        border-radius: 8px;
        margin: 1rem 0;
    }

    .modal-body .warning-text {
        color: #e53e3e;
        font-weight: 600;
        font-size: 0.95rem;
        margin-top: 1rem;
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 2px solid #e2e8f0;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-danger {
        background: linear-gradient(135deg, #fc8181 0%, #e53e3e 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(229, 62, 62, 0.4);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .btn-create {
            width: 100%;
            justify-content: center;
        }

        .modal-content {
            width: 95%;
        }

        .modal-footer {
            flex-direction: column-reverse;
        }

        .modal-footer .btn,
        .modal-footer .btn-danger {
            width: 100%;
            justify-content: center;
        }
    }
</style>
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

