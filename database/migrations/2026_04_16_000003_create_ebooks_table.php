<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('usuarios')->cascadeOnDelete();
            $table->string('titulo');
            $table->string('autor');
            $table->string('editora')->nullable();
            $table->date('data_publicacao');
            $table->string('categoria', 120)->nullable();
            $table->text('resumo');
            $table->longText('conteudo');
            $table->string('arquivo_pdf')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};

