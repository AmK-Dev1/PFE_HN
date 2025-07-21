<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoutCamion extends Model
{
    protected $table = 'couts_camions';

    protected $fillable = [
        'unite',
        'annee_de_construction',
        'no_plaque',
        'responsable',
        'marque',
        'cout_km',
        'km_parcourus',
        'cout_hr',
        'heures',
        'carburant',
        'entretien',
        'immatriculation',
        'assurance',
        'interet',
        'location',
        'amortissement',
        'total_depenses',
        'colonnes_personnalisees',
        'est_moyenne'
    ];

    protected $casts = [
        'annee_de_construction' => 'integer',
        'km_parcourus' => 'integer',
        'heures' => 'integer',
        'cout_km' => 'float',
        'cout_hr' => 'float',
        'carburant' => 'float',
        'entretien' => 'float',
        'immatriculation' => 'float',
        'assurance' => 'float',
        'interet' => 'float',
        'location' => 'float',
        'amortissement' => 'float',
        'total_depenses' => 'float',
        'colonnes_personnalisees' => 'array',
        'est_moyenne' => 'boolean',
    ];
}
