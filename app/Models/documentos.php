<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class documentos extends Model
{
    protected $table = 'morph_documentos';

    protected $fillable = [
        'morph_type',
        'morph_id',
        'file_name',
        'file_path',
        'dh_entrega',
        'entregue_por_user_id',
    ];

    protected $casts = [
        'morph_type' => 'string',
        'morph_id' => 'integer',
        'file_name' => 'string',
        'file_path' => 'string',
        'dh_entrega' => 'datetime',
        'entregue_por_user_id' => 'integer',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'entregue_por_user_id');
    }
}
