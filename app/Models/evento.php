<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class evento extends Model
{
    protected $table = 'evento';

    protected $fillable = [
        'titulo',
        'tipo_id',
        'descricao',
        'data_hora',
        'local',
        'capacidade',
        'created_by',
        'aprovado_por',
        'status',
    ];

    protected $casts = [
        'titulo' => 'string',
        'tipo_id' => 'integer',
        'descricao' => 'string',
        'data_hora' => 'datetime',
        'local' => 'string',
        'capacidade' => 'integer',
        'created_by' => 'integer',
        'aprovado_por' => 'integer',
        'status' => 'string',
    ];
        
    public function tipo(){
        return $this->belongsTo(tipo::class, 'tipo_id');
    }

    public function criador(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function aprovador(){
        return $this->belongsTo(User::class, 'aprovado_por');
    }

    public function inscricoes(){
        return $this->hasMany(inscricao::class, 'evento_id');
    }

    public function convites(){
        return $this->hasMany(convite::class, 'evento_id');
    }
}
