<?php
namespace App\Policies;

use App\Models\Tache;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Termwind\Components\Ul;

class TachePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user):bool
    {
        return $user->hasRole(['directeur','employe']);
    }
    /**
     * Determine whether the user can view the model
     *
     */
    public function view(User $user,Tache $tache):bool
    {
        if($user->hasRole('directeur'))
        {
            return true;
        }
        return $user->hasRole('employe') && $tache->employe_id===$user->id;
    }
    /**
     * Determine whether the user can create models .
     */
    public function create(User $user):bool
    {
        return $user->hasRole('directeur');
    }
    /**
     * Determine whether the user can upadate the model .
     */
    public function update(User $user ,Tache $tache)
    {
        if($user->hasRole('directeur'))
        {
            //le directeur peut toujours mettre a jour les taches qu'il a creer ou celles des employes
            return true;//ou $tache->directeur_id===$user->id; pour plus de restriction
        }
        //Un employes ne peut mettre a jour que se proprs taches et si ellees ne sont pas deja terminees/annulees
        return $user->hasRole('employe') && $tache->employe_id===$user->id && !in_array($tache->statut,['terminee','annulee']);
    }
    /**
     * Detrmine whether the user can delete the model
     */
    public function delete(User $user,Tache $tache):bool
    {
        return $user->hasRole('directeur');//Seul le directeur peut supprimer
    }
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user,Tache $tache)
    {
        return $user->hasRole('directeur');
    }
    /**
     * Determine whether the user can permanently delete the model.
     *
     */
    public function forceDelete(User $user,Tache $tache):bool
    {
        return $user->hasRole('directeur');
    }
}
