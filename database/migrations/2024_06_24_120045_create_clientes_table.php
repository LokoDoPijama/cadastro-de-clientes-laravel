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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 120);
            $table->date('dataNasc');
            $table->string('cep', 9);
            $table->string('endereco', 120);
            $table->string('numero', 60);
            $table->string('bairro', 60);
            $table->string('cidade', 60);
            $table->string('uf', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
