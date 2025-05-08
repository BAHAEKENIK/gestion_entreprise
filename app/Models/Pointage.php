<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pointage extends Model
{
    use HasFactory;
    protected $fillable=[
        'pointe_debut',
        'pointe_depart',
        'description',
        'employe_id'
    ];
    protected $casts=[
        'pointe_debut'=>'datetime',
        'pointe_depart'=>'datetime'
    ];
    public function employe()
    {
        return $this->belongsTo(User::class,'employe_id');
    }
}
