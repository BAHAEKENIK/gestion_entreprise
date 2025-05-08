<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    use HasFactory;

    protected $fillable=[
       'titre',
       'description',
       'statut',//['a_faire','enn_cours','terminee','en_revision','annulee']
       'date_assignation',
       'date_debut_prevue',
       'date_fin_prevue',
       'date_completion',
       'duree_estimee',
       'employe_id',
       'directeur_id'
    ];
    protected $casts=[
        'date_assignation'=>'datetime',
        'date_debut_prevue'=>'datetime',
        'date_fin_prevue'=>'datetime',
        'date_completion'=>'datetime',
        'statut'=>'string'
    ];
    public function employeAssignee()
    {
        return $this->belongsTo(User::class,'employe_id');
    }
    public function assignePar()
    {
        return $this->belongsTo(User::class,'directeur_id');
    }
    public function documents()
    {
        return $this->hasMany(Document::class,'tache_id');
    }
}
