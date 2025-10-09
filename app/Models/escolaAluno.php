<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class escolaAluno extends Model
{
    protected $table = 'escola_alunos';

    protected $fillable = [
        'escola_id',
        'curso_id',
        'aluno_id',
    ];

    protected $casts = [
        'escola_id' => 'integer',
        'curso_id' => 'integer',
        'aluno_id' => 'integer',
    ];

    public function escola(){
        return $this->belongsTo(escolas::class, 'escola_id');
    }

    public function curso(){
        return $this->belongsTo(curso::class, 'curso_id');
    }

    public function aluno(){
        return $this->belongsTo(User::class, 'aluno_id');
    }
}
