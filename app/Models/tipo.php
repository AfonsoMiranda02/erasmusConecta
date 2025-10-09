<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tipo extends Model
{
    protected $table = 'tipo';

    protected $fillable = [
        'nome',
    ];

    protected $casts = [
        'nome' => 'string',
    ];

        public function eventos()
    {
        return $this->hasMany(Evento::class, 'tipo_id');
    }
}
