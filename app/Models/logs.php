<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class logs extends Model
{
    protected $table = 'morph_logs';

    protected $fillable = [
        'log_name',
        'user_id',
        'morph_type',
        'morph_id',
        'action',
    ];

    protected $casts = [
        'log_name' => 'string',
        'user_id' => 'integer',
        'morph_type' => 'string',
        'morph_id' => 'integer',
        'action' => 'string',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function morphable(){
        return $this->morphTo();
    }
}
