<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'descricao',
        'projeto_id',
        'data_inicio',
        'data_fim',
        'tarefa_predecessora_id',
        'concluida',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'concluida' => 'boolean',
    ];
    // Relação com tabela Project
    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'projeto_id');
    }
    // Relação com outras tarefas
    public function tarefaPredecessora(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'tarefa_predecessora_id');
    }
    // Relação com tarefas que dependem desta (somente para visualização)
    public function tarefasSucessoras(): HasMany
    {
        return $this->hasMany(Task::class, 'tarefa_predecessora_id');
    }
    // Scopes para facilitar consultas
    public function scopeConcluidas($query)
    {
        return $query->where('concluida', true);
    }

    public function scopeNaoConcluidas($query)
    {
        return $query->where('concluida', false);
    }

    public function scopePorProjeto($query, $projetoId)
    {
        return $query->where('projeto_id', $projetoId);
    }
    // Validações adicionais
    public function podeSerExcluida(): bool
    {
        return $this->tarefasSucessoras()->count() === 0;
    }

    public function datasValidas(): bool
    {
        if ($this->data_inicio && $this->data_fim) {
            return $this->data_fim >= $this->data_inicio;
        }
        return true;
    }
}
