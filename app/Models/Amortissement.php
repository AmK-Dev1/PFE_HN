<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amortissement extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'poste',
        'cout',
        'amort_cumule_anterieur',
        'valeur_nette_anterieure',
        'acquisition_annee',
        'amortissement_annee',
        'amortissement_mensuel',
        'taux',
        'type_amortissement',
        'year',
    ];
}
