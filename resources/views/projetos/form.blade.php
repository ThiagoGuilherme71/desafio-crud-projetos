@extends('layouts.master')

@section('title', isset($creating) ? 'Novo Projeto' : (isset($viewing) ? 'Visualizar Projeto' : 'Editar Projeto'))

@push('styles')
<style>
    .form-container {
        max-width: 800px;
        margin: 2rem auto;
        background: #f0f0f0ff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }

    .form-header {
        margin-bottom: 2rem;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 1rem;
    }

    .form-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-group label .required {
        color: #e53e3e;
        margin-left: 4px;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #53b4e3;
        box-shadow: 0 0 0 3px rgba(83, 180, 227, 0.1);
    }

    .form-control.is-invalid {
        border-color: #e53e3e;
    }

    .invalid-feedback {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
        font-family: inherit;
    }

    select.form-control {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%234a5568' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 12px;
        padding-right: 2.5rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #e2e8f0;
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

    .btn-primary {
        background: linear-gradient(135deg, #53b4e3 0%, #307192ff 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(83, 180, 227, 0.4);
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #4a5568;
    }

    .btn-secondary:hover {
        background: #cbd5e0;
    }

    .help-text {
        font-size: 0.875rem;
        color: #718096;
        margin-top: 0.25rem;
    }

    /* Responsivo */
    @media (max-width: 768px) {
        .form-container {
            margin: 1rem;
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1>
            {{ isset($creating) ? 'Novo Projeto' : (isset($viewing) ? 'Visualizar Projeto' : 'Editar Projeto') }}
        </h1>
    </div>

    <form action="{{ isset($creating) ? route('projects.store') : route('projects.update', $project->id) }}"
          method="POST"
          id="projectForm"
          >
        @csrf
        @if(!isset($creating) && !isset($viewing))
            @method('PUT')
        @endif

        <!-- Input "Nome" -->
        <div class="form-group">
            <label for="nome">
                Nome do Projeto
                <span class="required">*</span>
            </label>
            <input
                type="text"
                class="form-control @error('nome') is-invalid @enderror"
                id="nome"
                name="nome"
                value="{{ old('nome', $project->nome ?? '') }}"
                placeholder="Digite o nome do projeto"
                {{ isset($viewing) ? 'readonly' : 'required' }}
                maxlength="255"
            >
            @error('nome')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            @if(!isset($viewing))
                <small class="help-text">O nome do projeto deve ser único e descritivo</small>
            @endif
        </div>

        <!-- Input "Descrição do Projeto" -->
        <div class="form-group">
            <label for="descricao">
                Descrição do Projeto
            </label>
            <textarea
                class="form-control @error('descricao') is-invalid @enderror"
                id="descricao"
                name="descricao"
                placeholder="Descreva os objetivos e características do projeto"
                rows="5"
                {{ isset($viewing) ? 'readonly' : '' }}
            >{{ old('descricao', $project->descricao ?? '') }}</textarea>
            @error('descricao')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            @if(!isset($viewing))
                <small class="help-text">Campo opcional</small>
            @endif
        </div>

        <!-- Input "Status" -->
        <div class="form-group">
            <label for="ativo">
                Status do Projeto
                <span class="required">*</span>
            </label>
            <select
                class="form-control @error('ativo') is-invalid @enderror"
                id="ativo"
                name="ativo"
                {{ isset($viewing) ? 'disabled' : 'required' }}
            >
                <option value="">Selecione o status</option>
                <option value="1" {{ (old('ativo', isset($project) ? $project->ativo : '') == 1 || old('ativo', isset($project) ? $project->ativo : '') === true) ? 'selected' : '' }}>
                    Ativo
                </option>
                <option value="0" {{ (old('ativo', isset($project) ? $project->ativo : '') == 0 || old('ativo', isset($project) ? $project->ativo : '') === false) ? 'selected' : '' }}>
                    Inativo
                </option>
            </select>
            @error('ativo')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            @if(!isset($viewing))
                <small class="help-text">Define se o projeto está em andamento ou pausado</small>
            @endif
        </div>

        <!-- Input "Orçamento" -->
        <div class="form-group">
            <label for="orcamento">
                Orçamento Disponível
            </label>
            <input
                type="number"
                class="form-control @error('orcamento') is-invalid @enderror"
                id="orcamento"
                name="orcamento"
                value="{{ old('orcamento', $project->orcamento ?? '') }}"
                placeholder="0.00"
                step="0.01"
                min="0"
                {{ isset($viewing) ? 'readonly' : '' }}
            >
            @error('orcamento')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            @if(!isset($viewing))
                <small class="help-text">Campo opcional - Valor em reais (R$)</small>
            @endif
        </div>

        <!-- Área de ações -->
        <div class="form-actions">
            <a href="{{ route('home') }}" class="btn btn-secondary">
                {{ isset($viewing) ? 'Voltar' : 'Cancelar' }}
            </a>
            @if(!isset($viewing))
                <button type="submit" class="btn btn-primary">
                    {{ isset($creating) ? 'Criar Projeto' : 'Salvar Alterações' }}
                </button>
            @else
                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary">
                    ✏️ Editar Projeto
                </a>
            @endif
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Formatação do campo de orçamento
    const orcamentoInput = document.getElementById('orcamento');

    if (orcamentoInput) {
        orcamentoInput.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    }

    // Impede o envio do formulário em modo de visualização
    @if(isset($viewing))
    document.getElementById('projectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        return false;
    });
    @else
    // Validação do formulário
    document.getElementById('projectForm').addEventListener('submit', function(e) {
        const nome = document.getElementById('nome').value.trim();
        const ativo = document.getElementById('ativo').value;

        if (!nome) {
            e.preventDefault();
            alert('Por favor, preencha o nome do projeto.');
            document.getElementById('nome').focus();
            return false;
        }

        if (ativo === '') {
            e.preventDefault();
            alert('Por favor, selecione o status do projeto.');
            document.getElementById('ativo').focus();
            return false;
        }
    });
    @endif
</script>
@endpush
