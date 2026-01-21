<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turmas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->string('ano_letivo', 10);
            $table->string('periodo', 20);
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turmas');
    }
};
