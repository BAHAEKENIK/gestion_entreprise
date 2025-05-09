<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importer BelongsTo

class Pointage extends Model
{
    use HasFactory;

    protected $fillable = [
        'pointe_debut',
        'pointe_fin',
        'description',
        'employe_id'
    ];

    protected $casts = [
        'pointe_debut' => 'datetime',
        'pointe_fin' => 'datetime',  
    ];

    public function employe(): BelongsTo // Type de retour ajouté
    {
        return $this->belongsTo(User::class, 'employe_id');
    }

    // Helper pour savoir si le pointage est terminé (départ pointé)
    public function estTermine(): bool
    {
        return !is_null($this->pointe_fin);
    }
}
