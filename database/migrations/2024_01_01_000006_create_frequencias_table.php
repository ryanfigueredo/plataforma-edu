<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frequencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->foreignId('disciplina_id')->constrained()->onDelete('cascade');
            $table->date('data');
            $table->boolean('presente')->default(false);
            $table->text('justificativa')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            
            $table->unique(['aluno_id', 'disciplina_id', 'data']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frequencias');
    }
};
