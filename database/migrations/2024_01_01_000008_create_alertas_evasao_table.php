<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alertas_evasao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->decimal('score_evasao', 3, 2);
            $table->json('motivos')->nullable();
            $table->enum('status', ['pendente', 'visualizado', 'acao_tomada', 'resolvido'])->default('pendente');
            $table->foreignId('visualizado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('visualizado_em')->nullable();
            $table->json('acoes_tomadas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertas_evasao');
    }
};
