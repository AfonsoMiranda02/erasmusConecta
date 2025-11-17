<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetCode extends Model
{
    protected $table = 'password_reset_codes';

    protected $fillable = [
        'email',
        'code',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Verifica se o código está expirado
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Verifica se o código é válido
     */
    public function isValid(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Apaga códigos expirados para um email
     */
    public static function deleteExpiredForEmail(string $email): void
    {
        static::where('email', $email)
            ->where('expires_at', '<', now())
            ->delete();
    }

    /**
     * Apaga todos os códigos para um email
     */
    public static function deleteAllForEmail(string $email): void
    {
        static::where('email', $email)->delete();
    }
}
