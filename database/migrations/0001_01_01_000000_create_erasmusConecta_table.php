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
        //=============================================
        // Tables Creation
        //=============================================

        // Users Table
        Schema::create('users', function (Blueprint $table){
            $table->id();
            $table->string('nome')->unique();
            $table->string('email')->unique();
            $table->string('num_processo');
            $table->string('password');
            $table->boolean('is_active')->default(1); // 0 - Inactive 1 - Active
            $table->boolean('is_aprovado')->default(0); // 0 - Not Approved 1 - Approved
            $table->timestamps();
        });

        // Escolas Table
        Schema::create('escolas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('morada');
            $table->string('codigo_postal', 20);
            $table->string('instituicao');
            $table->string('abreviacao', 50);
            $table->timestamps();
        });

        //Disciplinas Table
        Schema::create('disciplinas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('sigla', 50);
            $table->timestamps();
        });

        // Cursos Table
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        // Curso_Disciplinas Table
        Schema::create('curso_disciplinas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curso_id');
            $table->unsignedBigInteger('disciplina_id');
            $table->timestamps();
        });

        // Escola_Professores Table
        Schema::create('escola_professores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('escola_id');
            $table->unsignedBigInteger('professor_id');
            $table->unsignedBigInteger('disciplina_id');
            $table->timestamps();
        });

        // Escola_Alunos Table
        Schema::create('escola_alunos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('escola_id');
            $table->unsignedBigInteger('curso_id');
            $table->unsignedBigInteger('aluno_id');
            $table->timestamps();
        });

        // Codigos_Mobilidade Table
        Schema::create('codigos_mobilidade', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('cod_mobilidade', 100);
            $table->boolean('is_validado')->default(0);
            $table->timestamps();
        });

        // Tipo Table
        Schema::create('tipo', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        // Evento Table
        Schema::create('evento', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->unsignedBigInteger('tipo_id');
            $table->text('descricao');
            $table->dateTime('data_hora');
            $table->string('local');
            $table->integer('capacidade')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('aprovado_por')->nullable();
            $table->enum('status', ['pendente', 'aprovado', 'rejeitado'])->default('pendente');
            $table->timestamps();
        });

        // Inscricoes Table
        Schema::create('inscricoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evento_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('estado', ['pendente', 'aprovada', 'rejeitada', 'cancelada'])->default('pendente');
            $table->dateTime('date_until_cancelation');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        // Convites Table
        Schema::create('convites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sent_by');
            $table->unsignedBigInteger('evento_id');
            $table->unsignedBigInteger('for_user');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->dateTime('data_hora');
            $table->enum('estado', ['pendente', 'aceite', 'recusado', 'expirado'])->default('pendente');
            $table->dateTime('time_until_expire');
            $table->timestamps();
        });
        //=============================================
        // END Tables Creation
        //=============================================

        //=============================================
        // Morphological Tables
        //=============================================

        // Morph Documentos Table
        Schema::create('morph_documentos', function (Blueprint $table) {
            $table->id();
            $table->string('morph_type', 50);
            $table->unsignedBigInteger('morph_id');
            $table->string('file_name')->nullable();
            $table->string('file_path');
            $table->dateTime('dh_entrega');
            $table->unsignedBigInteger('entregue_por_user_id');
            $table->timestamps();
        });

        // Morph Logs Table
        Schema::create('morph_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name');
            $table->unsignedBigInteger('user_id');
            $table->string('morph_type', 250);
            $table->unsignedBigInteger('morph_id');
            $table->string('action');
            $table->timestamps();
        });

        // Morph Notificacoes Table
        Schema::create('morph_notificacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('morph_type', 50);
            $table->unsignedBigInteger('morph_id');
            $table->string('titulo');
            $table->text('mensagem');
            $table->boolean('is_seen')->default(0);
            $table->timestamps();
        });
        //=============================================
        // END Morphological Tables
        //=============================================

        // ===========================================================
        // FOREIGN KEYS
        // ===========================================================
        Schema::table('curso_disciplinas', function (Blueprint $table) {
            $table->foreign('curso_id')->references('id')->on('cursos');
            $table->foreign('disciplina_id')->references('id')->on('disciplinas');
        });

        Schema::table('escola_professores', function (Blueprint $table) {
            $table->foreign('escola_id')->references('id')->on('escolas');
            $table->foreign('professor_id')->references('id')->on('users');
            $table->foreign('disciplina_id')->references('id')->on('disciplinas');
        });

        Schema::table('escola_alunos', function (Blueprint $table) {
            $table->foreign('escola_id')->references('id')->on('escolas');
            $table->foreign('curso_id')->references('id')->on('cursos');
            $table->foreign('aluno_id')->references('id')->on('users');
        });

        Schema::table('codigos_mobilidade', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('evento', function (Blueprint $table) {
            $table->foreign('tipo_id')->references('id')->on('tipo');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('aprovado_por')->references('id')->on('users');
        });

        Schema::table('inscricoes', function (Blueprint $table) {
            $table->foreign('evento_id')->references('id')->on('evento');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('convites', function (Blueprint $table) {
            $table->foreign('sent_by')->references('id')->on('users');
            $table->foreign('evento_id')->references('id')->on('evento');
            $table->foreign('for_user')->references('id')->on('users');
        });

        Schema::table('morph_documentos', function (Blueprint $table) {
            $table->foreign('entregue_por_user_id')->references('id')->on('users');
        });

        Schema::table('morph_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('morph_notificacoes', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
        // ===========================================================
        // END FOREIGN KEYS
        // ===========================================================
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('morph_notificacoes');
        Schema::dropIfExists('morph_logs');
        Schema::dropIfExists('morph_documentos');
        Schema::dropIfExists('convites');
        Schema::dropIfExists('inscricoes');
        Schema::dropIfExists('evento');
        Schema::dropIfExists('tipo');
        Schema::dropIfExists('codigos_mobilidade');
        Schema::dropIfExists('escola_alunos');
        Schema::dropIfExists('escola_professores');
        Schema::dropIfExists('curso_disciplinas');
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('disciplinas');
        Schema::dropIfExists('escolas');
        Schema::dropIfExists('users');

        Schema::enableForeignKeyConstraints();
    }
};