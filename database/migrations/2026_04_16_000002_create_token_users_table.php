<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('token_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('usuarios')->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->timestamp('valido_ate')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('token_users');
    }
};

