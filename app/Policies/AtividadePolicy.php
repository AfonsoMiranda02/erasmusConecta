<?php

namespace App\Policies;

use App\Models\User;
use App\Models\evento;
use Illuminate\Auth\Access\Response;

class AtividadePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Todos os utilizadores autenticados podem ver atividades
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, evento $evento): bool
    {
        // Admin vê tudo - verifica diretamente o num_processo
        if (!empty($user->num_processo) && strtoupper(trim($user->num_processo)[0]) === 'A') {
            return true;
        }

        // Professor vê as suas + aprovadas
        if (!empty($user->num_processo) && strtoupper(trim($user->num_processo)[0]) === 'P') {
            return $evento->created_by === $user->id || $evento->status === 'aprovado';
        }

        // Estudante e Intercambista: apenas aprovadas
        $primeiroChar = !empty($user->num_processo) ? strtoupper(trim($user->num_processo)[0]) : '';
        if (in_array($primeiroChar, ['E', 'I'])) {
            return $evento->status === 'aprovado';
        }

        // Fallback: se não conseguir determinar o cargo, permite ver se estiver aprovado
        return $evento->status === 'aprovado';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Verifica diretamente o num_processo
        if (empty($user->num_processo)) {
            \Log::info('AtividadePolicy::create - num_processo vazio', ['user_id' => $user->id]);
            return false;
        }
        
        $numProcesso = trim($user->num_processo);
        if (empty($numProcesso)) {
            \Log::info('AtividadePolicy::create - num_processo vazio após trim', ['user_id' => $user->id]);
            return false;
        }
        
        $primeiroChar = strtoupper($numProcesso[0]);
        
        \Log::info('AtividadePolicy::create - Verificação', [
            'user_id' => $user->id,
            'num_processo' => $user->num_processo,
            'primeiro_char' => $primeiroChar,
            'pode_criar' => in_array($primeiroChar, ['A', 'P', 'E'])
        ]);
        
        // Admin, Professor e Estudante podem criar
        // Intercambista nunca cria
        return in_array($primeiroChar, ['A', 'P', 'E']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, evento $evento): bool
    {
        if (empty($user->num_processo)) {
            return false;
        }
        
        $primeiroChar = strtoupper(trim($user->num_processo)[0]);
        
        // Admin pode editar qualquer atividade
        if ($primeiroChar === 'A') {
            return true;
        }

        // Professor só pode editar as atividades que ele criou
        if ($primeiroChar === 'P') {
            return $evento->created_by === $user->id;
        }

        // Estudante só pode editar as atividades que ele criou
        if ($primeiroChar === 'E') {
            return $evento->created_by === $user->id;
        }

        // Intercambista nunca edita
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, evento $evento): bool
    {
        if (empty($user->num_processo)) {
            return false;
        }
        
        $primeiroChar = strtoupper(trim($user->num_processo)[0]);
        
        // Admin pode eliminar qualquer atividade
        if ($primeiroChar === 'A') {
            return true;
        }

        // Professor só pode eliminar as atividades que ele criou
        if ($primeiroChar === 'P') {
            return $evento->created_by === $user->id;
        }

        // Estudante só pode eliminar as atividades que ele criou
        if ($primeiroChar === 'E') {
            return $evento->created_by === $user->id;
        }

        // Intercambista nunca elimina
        return false;
    }

    /**
     * Determine whether the user can archive the model.
     */
    public function archive(User $user, evento $evento): bool
    {
        // Mesmas regras que update e delete
        return $this->update($user, $evento);
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, evento $evento): bool
    {
        // Apenas Admin pode aprovar atividades
        if (empty($user->num_processo)) {
            return false;
        }
        return strtoupper(trim($user->num_processo)[0]) === 'A';
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, evento $evento): bool
    {
        // Apenas Admin pode rejeitar atividades
        if (empty($user->num_processo)) {
            return false;
        }
        return strtoupper(trim($user->num_processo)[0]) === 'A';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, evento $evento): bool
    {
        // Apenas Admin pode restaurar
        if (empty($user->num_processo)) {
            return false;
        }
        return strtoupper(trim($user->num_processo)[0]) === 'A';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, evento $evento): bool
    {
        // Apenas Admin pode eliminar permanentemente
        if (empty($user->num_processo)) {
            return false;
        }
        return strtoupper(trim($user->num_processo)[0]) === 'A';
    }
}
