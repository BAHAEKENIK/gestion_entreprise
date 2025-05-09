<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
 use Spatie\Permission\Traits\HasRoles; // Décommentez si vous configurez et utilisez Spatie
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // use HasApiTokens, HasFactory, Notifiable, HasRoles; // Décommentez HasRoles si Spatie est utilisé
    use HasApiTokens, HasFactory, Notifiable,HasRoles;


    use HasApiTokens, HasFactory, Notifiable, HasRoles; // HasRoles est crucial

    protected $fillable = [
        'name', // Un seul champ pour le nom
        'email',
        'password',
        'post',
        'avatar',
        'telephone',
        'statut',
        'date_embauche',
        'theme',
        'must_change_password' // Si vous gardez ce flux
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_embauche' => 'date',
        'statut' => 'string',
        'must_change_password' => 'boolean',
    ];

    // Relation avec Pointages
    public function pointages(): HasMany
    {
        return $this->hasMany(Pointage::class, 'employe_id');
    }

    // ... autres relations (taches, reclamations, documents)
    public function tachesAssignees()
    {
        return $this->hasMany(Tache::class,'employe_id');
    }
    public function tachesCrees(): HasMany
    {
        return $this->hasMany(Tache::class,'directeur_id');
    }
    public function reclamationsEmises() // Supposant que 'employe_id' est l'auteur
    {
        return $this->hasMany(Reclamation::class,'employe_id');
    }
    public function reclamationsRecues() // Supposant que 'directeur_id' est le destinataire
    {
        return $this->hasMany(Reclamation::class,'directeur_id');
    }
    public function documentsEnvoyes() // Ajout pour la complétude
    {
        return $this->hasMany(Document::class, 'expediteur_id');
    }
    public function documentsRecus()
    {
        return $this->hasMany(Document::class,'recepteur_id');
    }


    // Méthodes pour vérifier le rôle (si on n'utilise pas Spatie activement pour cela)
    public function estDirecteur(): bool
{
    return $this->hasRole('directeur');
}

public function estEmploye(): bool
{
    return $this->hasRole('employe');
}
}
