<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->text('descricao');
            $table->foreignId('projeto_id')->constrained('projects')->onDelete('cascade');
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->foreignId('tarefa_predecessora_id')->nullable()->constrained('tasks')->onDelete('set null');
            $table->boolean('concluida')->default(false);
            $table->timestamps();
            $table->unique(['projeto_id', 'descricao']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
