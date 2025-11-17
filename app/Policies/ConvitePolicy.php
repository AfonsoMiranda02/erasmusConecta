<?php

namespace App\Policies;

use App\Models\User;
use App\Models\convite;
use App\Models\evento;

class ConvitePolicy
{
    /**
     * Determine whether the user can create convites.
     */
    public function create(User $user, evento $evento): bool
    {
        // Verifica diretamente o num_processo
        if (empty($user->num_processo)) {
            return false;
        }
        
        $primeiroChar = strtoupper(trim($user->num_processo)[0]);
        
        // Intercambista nunca cria convites
        if ($primeiroChar === 'I') {
            return false;
        }
        
        // Admin, Professor e Estudante podem criar convites
        // Mas só para atividades que eles criaram
        if (in_array($primeiroChar, ['A', 'P', 'E'])) {
            // Admin pode criar convites para qualquer atividade
            if ($primeiroChar === 'A') {
                return true;
            }
            
            // Professor e Estudante só podem criar convites para atividades que criaram
            return $evento->created_by === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can view convites.
     */
    public function view(User $user, convite $convite): bool
    {
        // Pode ver se:
        // 1. É o destinatário do convite
        // 2. É quem enviou o convite
        // 3. É admin
        if (empty($user->num_processo)) {
            return false;
        }
        
        $primeiroChar = strtoupper(trim($user->num_processo)[0]);
        
        if ($primeiroChar === 'A') {
            return true; // Admin vê tudo
        }
        
        return $convite->for_user === $user->id || $convite->sent_by === $user->id;
    }

    /**
     * Determine whether the user can accept convites.
     */
    public function accept(User $user, convite $convite): bool
    {
        // Só o destinatário pode aceitar
        return $convite->for_user === $user->id && $convite->estado === 'pendente';
    }

    /**
     * Determine whether the user can reject convites.
     */
    public function reject(User $user, convite $convite): bool
    {
        // Só o destinatário pode rejeitar
        return $convite->for_user === $user->id && $convite->estado === 'pendente';
    }

    /**
     * Determine whether the user can delete convites.
     */
    public function delete(User $user, convite $convite): bool
    {
        if (empty($user->num_processo)) {
            return false;
        }
        
        $primeiroChar = strtoupper(trim($user->num_processo)[0]);
        
        // Admin pode eliminar qualquer convite
        if ($primeiroChar === 'A') {
            return true;
        }
        
        // Quem enviou pode eliminar o seu próprio convite
        return $convite->sent_by === $user->id;
    }
}
