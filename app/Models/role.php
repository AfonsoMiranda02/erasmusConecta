<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class role extends Model
{    
    protected $table = 'roles';

    protected $fillable = [
        'nome',
    ];

    protected $casts = [
        'nome' => 'string',
    ];

    public function users(){
        return $this->hasMany(User::class, 'role_id');
    }
}
