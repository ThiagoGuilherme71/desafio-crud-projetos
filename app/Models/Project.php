<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
