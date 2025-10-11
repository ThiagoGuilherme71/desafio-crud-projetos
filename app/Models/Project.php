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

    // para visualização do progresso do projeto no datatable
    public function calcularProgresso(): float
    {
        $totalTarefas = $this->tasks()->count();

        if ($totalTarefas === 0) {
            return 0;
        }

        $tarefasConcluidas = $this->tasks()->concluidas()->count();

        return round(($tarefasConcluidas / $totalTarefas) * 100, 2);
    }

    public function getProgressoTarefas(): string
    {
        $tarefasConcluidas = $this->tasks()->concluidas()->count();
        $totalTarefas = $this->tasks()->count();

        return "{$tarefasConcluidas}/{$totalTarefas}";
    }
}
