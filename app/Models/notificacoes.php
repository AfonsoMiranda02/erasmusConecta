<?php

namespace App\Models;

use App\Events\NotificacaoCreated;
use Illuminate\Database\Eloquent\Model;

class notificacoes extends Model
{
    protected $table = 'morph_notificacoes';

    protected $fillable = [
        'user_id',
        'morph_type',
        'morph_id',
        'titulo',
        'mensagem',
        'is_seen',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'morph_type' => 'string',
        'morph_id' => 'integer',
        'titulo' => 'string',
        'mensagem' => 'string',
        'is_seen' => 'boolean',
    ];

    /**
     * The event map for the model.
     */
    protected $dispatchesEvents = [
        'created' => NotificacaoCreated::class,
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function morphable(){
        return $this->morphTo();
    }
}
