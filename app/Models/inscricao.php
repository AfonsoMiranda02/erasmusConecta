<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class inscricao extends Model
{
    protected $table = 'inscricoes';

    protected $fillable = [
        'evento_id',
        'user_id',
        'estado',
        'date_until_cancelation',
        'is_active',
    ];

    protected $casts = [
        'evento_id' => 'integer',
        'user_id' => 'integer',
        'estado' => 'string',
        'date_until_cancelation' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function evento(){
        return $this->belongsTo(evento::class, 'evento_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
