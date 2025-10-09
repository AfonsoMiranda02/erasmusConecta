<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class disciplina extends Model
{
    protected $table = 'disciplinas';

    protected $fillable = [
        'nome',
        'sigla',
    ];

    protected $casts = [
        'nome' => 'string',
        'sigla' => 'string',
    ];

    public function cursos(){
        return $this->belongsToMany(curso::class, 'curso_disciplinas', 'disciplina_id', 'curso_id');
    }

    public function professores(){
        return $this->hasMany(escolaProfessor::class, 'disciplina_id');
    }
}
