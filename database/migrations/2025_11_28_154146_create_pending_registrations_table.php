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
        Schema::create('pending_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('num_processo');
            $table->string('password'); // Hash da password
            $table->string('tipo'); // estudante, professor, intercambista
            $table->string('codigo_mobilidade')->nullable();
            $table->string('documento_path')->nullable();
            $table->string('verification_code', 6); // Código de 6 dígitos
            $table->timestamp('expires_at'); // Expira em 15 minutos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_registrations');
    }
};
