<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingRegistration extends Model
{
    protected $table = 'pending_registrations';

    protected $fillable = [
        'nome',
        'email',
        'num_processo',
        'password',
        'tipo',
        'codigo_mobilidade',
        'documento_path',
        'verification_code',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Verifica se o código expirou
     */
    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }
}

