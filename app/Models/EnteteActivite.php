<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnteteActivite extends Model
{
    use HasFactory;

    protected $table = 'entetes_activites';

    protected $fillable = [
        'operation_type_id',
        'annee',
        'date_mise_a_jour',
        'minutes_pause',
        'minutes_temps_mort',
        'pourcentage_pause',
        'pourcentage_temps_mort',
        'jours_feries',
    ];

    public function operationType()
    {
        return $this->belongsTo(OperationType::class);
    }
}
