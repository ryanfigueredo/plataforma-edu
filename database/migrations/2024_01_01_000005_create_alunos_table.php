<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('matricula', 20)->unique();
            $table->foreignId('turma_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->date('data_ingresso');
            $table->enum('status', ['ativo', 'inativo', 'evadido', 'concluido', 'excluido'])->default('ativo');
            $table->decimal('evasao_score', 3, 2)->default(0)->comment('Score de risco de evasão (0-1)');
            $table->timestamp('ultima_analise_evasao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
