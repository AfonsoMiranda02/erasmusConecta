<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'email',
        'num_processo',
        'password',
        'role_id',
        'is_active',
        'is_aprovado',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'num_processo' => 'string',
        'password' => 'hashed',
        'role_id' => 'integer',
        'is_active' => 'boolean',
        'is_aprovado' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }


    public function eventosCriados(){
        return $this->hasMany(evento::class, 'created_by');
    }

    public function eventosAprovados(){
        return $this->hasMany(evento::class, 'aprovado_por');
    }

    public function inscricoes(){
        return $this->hasMany(inscricao::class, 'user_id');
    }

    public function convitesEnviados(){
        return $this->hasMany(convite::class, 'sent_by');
    }

    public function convitesRecebidos(){
        return $this->hasMany(convite::class, 'for_user');
    }

    public function documentos(){
        return $this->hasMany(documentos::class, 'entregue_por_user_id');
    }

    public function logs(){
        return $this->hasMany(logs::class, 'user_id');
    }
    public function notificacoes(){
        return $this->hasMany(notificacoes::class, 'user_id');
    }
}