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
    }
</style>
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">📊 Gerenciar Projetos</h1>
        <button class="btn-create" onclick="alert('Funcionalidade de criar projeto em desenvolvimento!')">
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
                    data: 'status',
                    render: function(data) {
                        const statusClass = data.toLowerCase() === 'ativo' ? 'status-ativo' : 'status-inativo';
                        return '<span class="status-badge ' + statusClass + '">' + data + '</span>';
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
                    render: function(data, type, row) {
                        return `
                            <div class="action-buttons">
                                <button class="btn-action btn-view" title="Visualizar" onclick="alert('Visualizar projeto: ${row.nome}')">
                                    👁️
                                </button>
                                <button class="btn-action btn-edit" title="Editar" onclick="alert('Editar projeto: ${row.nome}')">
                                    ✏️
                                </button>
                                <button class="btn-action btn-delete" title="Excluir" onclick="alert('Excluir projeto: ${row.nome}')">
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
</script>
@endpush

