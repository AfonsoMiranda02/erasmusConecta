<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class escolas extends Model
{
    protected $table = 'escolas';

    protected $fillable = [
        'nome',
        'morada',
        'codigo_postal',
        'instituicao',
        'abreviacao',
    ];

    protected $casts = [
        'nome' => 'string',
        'morada' => 'string',
        'codigo_postal' => 'string',
        'instituicao' => 'string',
        'abreviacao' => 'string',
    ];

    public function professores(){
        return $this->hasMany(escolaProfessor::class, 'escola_id');
    }

    public function alunos(){
        return $this->hasMany(escolaAluno::class, 'escola_id');
    }
}
