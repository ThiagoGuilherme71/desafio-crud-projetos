<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'ativo',
        'orcamento',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'orcamento' => 'decimal:2',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'projeto_id');
    }

    public function getProgressoTarefas(): string
    {
        $tarefasConcluidas = $this->tasks()->concluidas()->count();
        $totalTarefas = $this->tasks()->count();

        return "{$tarefasConcluidas}/{$totalTarefas}";
    }
}
