<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class convite extends Model
{
    protected $table = 'convites';

    protected $fillable = [
        'sent_by',
        'evento_id',
        'for_user',
        'titulo',
        'descricao',
        'data_hora',
        'estado',
        'time_until_expire',
    ];

    protected $casts = [
        'sent_by' => 'integer',
        'evento_id' => 'integer',
        'for_user' => 'integer',
        'titulo' => 'string',
        'descricao' => 'string',
        'data_hora' => 'datetime',
        'estado' => 'string',
        'time_until_expire' => 'datetime',
    ];

        public function evento(){
        return $this->belongsTo(evento::class, 'evento_id');
    }

    public function remetente(){
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function destinatario(){
        return $this->belongsTo(User::class, 'for_user');
    }
}
