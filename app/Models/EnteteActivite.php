<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnteteActivite extends Model
{
    use HasFactory;

    protected $table = 'entetes_activites'; // Nom de la table

    protected $fillable = [
        'nom',
        'annee',
        'nb_semaines',
        'nb_jours_feries',
        'pourcentage_pause',
        'pourcentage_temps_mort',
    ];
}
