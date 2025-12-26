<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'users';
    
    protected $fillable = [
        'nome',
        'email',
        'num_processo',
        'password',
        'is_active',
        'is_aprovado',
        'codigo_mobilidade',
        'documento_path',
        'telefone',
        'avatar_path',
        'email_verified_at',
        'email_verification_token',
        'prefer_email',
        'locale',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'nome' => 'string',
        'email' => 'string',
        'num_processo' => 'string',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'is_aprovado' => 'boolean',
        'codigo_mobilidade' => 'string',
        'documento_path' => 'string',
        'telefone' => 'string',
        'avatar_path' => 'string',
        'email_verified_at' => 'datetime',
        'email_verification_token' => 'string',
    ];

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
        return $this->morphMany(logs::class, 'morphable');
    }

    public function notificacoes(){
        return $this->hasMany(notificacoes::class, 'user_id');
    }

    public function pushSubscriptions(){
        return $this->hasMany(PushSubscription::class, 'user_id');
    }

    /**
     * Obtém a URL do avatar ou retorna null
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar_path) {
            return Storage::url($this->avatar_path);
        }
        return null;
    }

    /**
     * Obtém as iniciais do nome
     */
    public function getIniciaisAttribute()
    {
        $nome = $this->nome;
        $iniciais = strtoupper(substr($nome, 0, 1));
        $partes = explode(' ', $nome);
        if (count($partes) > 1) {
            $iniciais .= strtoupper(substr($partes[1], 0, 1));
        } else {
            $iniciais .= strtoupper(substr($nome, 1, 1));
        }
        return $iniciais;
    }

    /**
     * Obtém o cargo baseado no primeiro caractere do num_processo
     * A → Admin, P → Professor, I → Intercambista, E → Estudante
     * A fonte da verdade é o primeiro carácter do num_processo
     */
    public function getCargoAttribute()
    {
        // Se não tiver num_processo, retorna null
        if (empty($this->num_processo) || !is_string($this->num_processo)) {
            return null;
        }

        // Pega o primeiro carácter e converte para maiúscula
        $numProcesso = trim($this->num_processo);
        if (empty($numProcesso)) {
            return null;
        }
        
        $primeiroChar = strtoupper($numProcesso[0]);
        
        // Retorna o cargo baseado no primeiro carácter
        switch($primeiroChar) {
            case 'A':
                return 'admin';
            case 'P':
                return 'professor';
            case 'I':
                return 'intercambista';
            case 'E':
                return 'estudante';
            default:
                return null;
        }
    }

    /**
     * Verifica se o utilizador é admin
     * Baseado no primeiro carácter do num_processo ser 'A'
     * A fonte da verdade é o primeiro carácter do num_processo
     */
    public function isAdmin(): bool
    {
        $cargo = $this->cargo;
        return $cargo === 'admin';
    }

    /**
     * Verifica se o utilizador é professor
     * Baseado no primeiro carácter do num_processo ser 'P'
     * A fonte da verdade é o primeiro carácter do num_processo
     */
    public function isProfessor(): bool
    {
        $cargo = $this->cargo;
        return $cargo === 'professor';
    }

    /**
     * Verifica se o utilizador é intercambista
     * Baseado no primeiro carácter do num_processo ser 'I'
     * A fonte da verdade é o primeiro carácter do num_processo
     */
    public function isIntercambista(): bool
    {
        $cargo = $this->cargo;
        return $cargo === 'intercambista';
    }

    /**
     * Verifica se o utilizador é estudante
     * Baseado no primeiro carácter do num_processo ser 'E'
     * A fonte da verdade é o primeiro carácter do num_processo
     */
    public function isEstudante(): bool
    {
        $cargo = $this->cargo;
        return $cargo === 'estudante';
    }
}