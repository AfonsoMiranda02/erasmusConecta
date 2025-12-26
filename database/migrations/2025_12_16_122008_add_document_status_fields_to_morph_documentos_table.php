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
        Schema::table('morph_documentos', function (Blueprint $table) {
            $table->enum('estado', ['pendente', 'aprovado', 'rejeitado'])->default('pendente')->after('dh_entrega');
            $table->text('mensagem_rejeicao')->nullable()->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('morph_documentos', function (Blueprint $table) {
            $table->dropColumn(['estado', 'mensagem_rejeicao']);
        });
    }
};
