<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'post',
        'avatar',
        'telephone',
        'statut',//['actif','inactif','en_conge']
        'role',//['employe','directeur']
        'date_embauche',
        'theme',
        'must_change_password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_embauche'=>'date',
        'statut'=>'string',
        'role'=>'string'
    ];
    public function tachesAssignees()
    {
        return $this->hasMany(Tache::class,'employe_id');
    }
    public function tachesCrees()
    {
        return $this->hasMany(Tache::class,'directeur_id');
    }
    public function pointages()
    {
        return $this->hasMany(Pointage::class,'employe_id');
    }
    public function reclamationsEmises()
    {
        return $this->hasMany(Reclamation::class,'employe_id');
    }
    public function reclamationsRecues()
    {
        return $this->hasMany(Reclamation::class,'directeur_id');
    }
    public function documentsRecus()
    {
        return $this->hasMany(Document::class,'recepteur_id');
    }

    
    public function estDirecteur()
    {
        return $this->role==='directeur';
    }
    public function estEmploye()
    {
        return $this->role==='employe';
    }

}
