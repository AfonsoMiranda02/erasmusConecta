<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class presencas extends Model
{
    protected $table = 'presencas';

    protected $fillable =[
        'user_id',
        'evento_id',
        'is_user_present',
        'obs',
        'submited_by',
        'data_fim_atividade',
    ];

    protected $casts = [
        'user_id'               =>'Integer',
        'evento_id'             =>'Integer',
        'is_user_present'       =>'Boolean',
        'submited_by'           =>'Integer',
        'obs'                   =>'String',
        'data_fim_atividade'    =>'DateTime',
    ];
}
