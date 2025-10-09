<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class codigoMobilidade extends Model
{
    protected $table = 'codigos_mobilidade';

    protected $fillable = [
        'user_id',
        'cod_mobilidade',
        'is_validado',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'cod_mobilidade' => 'string',
        'is_validado' => 'boolean',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
