@extends('layouts.master')

@section('title', isset($creating) ? 'Novo Projeto' : (isset($viewing) ? 'Visualizar Projeto' : 'Editar Projeto'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/form-projetos.css') }}">
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
