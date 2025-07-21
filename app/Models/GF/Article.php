<?php

namespace App\Models\GF;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = 'gf_articles';

    protected $fillable = ['name', 'description', 'unit_cost', 'type', 'reference', 'unit_of_measure'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($article) {
            // Unité de mesure est obligatoire pour maind'œuvre, transport, et soutraitance
            if (in_array($article->type, ['maindoeuvre', 'transport', 'soutraitance'])) {
                if (is_null($article->unit_of_measure)) {
                    throw new ValidationException("L'unité de mesure est obligatoire pour les articles de type maind'œuvre, transport et soutraitance.");
                }
            } else {
                // Si l'article est de type 'material', pas d'unité de mesure
                $article->unit_of_measure = null; // On s'assure qu'il n'y a pas d'unité de mesure pour 'material'
            }
        });
    }
}
