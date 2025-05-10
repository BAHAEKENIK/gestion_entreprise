<?php

// app/Policies/UserPolicy.php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    // Permet à un admin/directeur de tout faire (souvent utile)
    public function before(User $user, $ability)
    {
        if ($user->hasRole('directeur')) { // Ou un rôle super-admin si vous en avez
            // return true; // Attention, cela bypasserait toutes les autres checks de policy pour ce rôle
        }
    }

    public function viewAny(User $user)
    {
        return $user->can('user-list'); // Utilise la permission Spatie
    }

    public function view(User $user, User $model)
    {
        return $user->can('user-list'); // Ou une permission plus spécifique comme 'user-view'
    }

    public function create(User $user)
    {
        // L'utilisateur connecté peut-il créer un nouvel utilisateur ?
        return $user->hasRole('directeur') || $user->can('user-create');
    }

    public function update(User $user, User $model)
    {
        // L'utilisateur connecté peut-il modifier cet utilisateur ($model) ?
        // Empêcher un directeur de se modifier lui-même via cette interface s'il est listé
        // Ou empêcher la modification d'autres directeurs, etc.
        // if ($model->hasRole('directeur') && $user->id !== $model->id) return false;
        return $user->hasRole('directeur') || $user->can('user-edit');
    }

    public function delete(User $user, User $model)
    {
        // L'utilisateur connecté peut-il supprimer cet utilisateur ($model) ?
        // Empêcher de se supprimer soi-même
        if ($user->id === $model->id) {
            return false;
        }
        // Empêcher la suppression d'autres directeurs par exemple
        // if ($model->hasRole('directeur')) return false;
        return $user->hasRole('directeur') || $user->can('user-delete');
    }
    // ... autres méthodes de policy
}
