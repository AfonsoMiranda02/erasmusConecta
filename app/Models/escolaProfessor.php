<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class escolaProfessor extends Model
{
    protected $table = 'escola_professores';

    protected $fillable = [
        'escola_id',
        'professor_id',
        'disciplina_id',
    ];

    protected $casts = [
        'escola_id' => 'integer',
        'professor_id' => 'integer',
        'disciplina_id' => 'integer',
    ];

    public function escola(){
        return $this->belongsTo(escolas::class, 'escola_id');
    }

    public function professor(){
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function disciplina(){
        return $this->belongsTo(disciplina::class, 'disciplina_id');
    }
}
