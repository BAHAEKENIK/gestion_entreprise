<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employe_id',   // Auteur de la réclamation
        'directeur_id', // Destinataire/Gestionnaire
        'sujet',        // Ajouté
        'description',
        'statut',
        'reponse',
        'date_reponse', // Ajouté
    ];

    protected $casts = [
        'statut' => 'string', // Pour gérer l'enum de la BDD
        'date_reponse' => 'datetime',
    ];

    /**
     * L'employé qui a soumis la réclamation.
     */
    public function auteur() // Renommé de employe() pour plus de clarté
    {
        return $this->belongsTo(User::class, 'employe_id');
    }

    /**
     * Le directeur qui doit traiter la réclamation.
     */
    public function destinataire() // Renommé de directeur() pour plus de clarté
    {
        return $this->belongsTo(User::class, 'directeur_id');
    }
}
