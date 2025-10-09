<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cursoDisciplina extends Model
{
    protected $table = 'curso_disciplinas';

    protected $fillable = [
        'curso_id',
        'disciplina_id',
    ];

    protected $casts = [
        'curso_id' => 'integer',
        'disciplina_id' => 'integer',
    ];

    public function curso(){
        return $this->belongsTo(curso::class, 'curso_id');
    }

    public function disciplina(){
        return $this->belongsTo(disciplina::class, 'disciplina_id');
    }
}
