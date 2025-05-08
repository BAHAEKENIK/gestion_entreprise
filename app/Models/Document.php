<?php

namespace App\Models;

use App\Models\Tache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;
    protected $fillable=[
        'nom_original',
        'chemin_stockage',
        'type_mime',
        'description',
        'expediteur_id',
        'recepteur_id',
        'tache_id'
    ];
    public function expediteur()
    {
        return $this->belongsTo(User::class,'expediteur_id');
    }
    public function recepteur()
    {
        return $this->belongsTo(User::class,'recepteur_id');
    }
    public function tache()
    {
        return $this->belongsTo(Tache::class,'tache_id');
    }

}
