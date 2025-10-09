<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class curso extends Model
{
    protected $table = 'cursos';

    protected $fillable = [
        'nome',
    ];

    protected $casts = [
        'nome' => 'string',
    ];

    public function disciplinas(){
        return $this->belongsToMany(disciplina::class, 'curso_disciplinas', 'curso_id', 'disciplina_id');
    }

    public function alunos(){
        return $this->hasMany(escolaAluno::class, 'curso_id');
    }
}
