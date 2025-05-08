<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;
    protected $fillable=[
        'description',
        'statut',//['soumise','en_cours_traitement'.'resolue','regetee']
        'reponse',
        'employe_id',
        'directeur_id'
    ];
    protected $casts=[
        'statut'=>'string'
    ];
    public function auteur()
    {
        return $this->belongsTo(User::class,'employe_id');
    }
    public function destinataire()
    {
        return $this->belongsTo(User::class,'directeur_id');
    }
}
