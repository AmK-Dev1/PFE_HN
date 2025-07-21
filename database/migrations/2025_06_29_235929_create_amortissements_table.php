<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmortissementsTable extends Migration
{
    public function up(): void
    {
        Schema::create('amortissements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();

            $table->string('poste'); // ex: Matériel informatique
            $table->decimal('cout', 12, 2);
            $table->decimal('amort_cumule_anterieur', 12, 2)->nullable();
            $table->decimal('valeur_nette_anterieure', 12, 2)->nullable();
            $table->decimal('acquisition_annee', 12, 2)->nullable();
            $table->decimal('amortissement_annee', 12, 2)->nullable();
            $table->decimal('amortissement_mensuel', 12, 2)->nullable();
            $table->decimal('taux', 5, 2);
            $table->string('type_amortissement')->default('L'); // L = linéaire, D = dégressif

            $table->integer('year'); // année d’amortissement

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amortissements');
    }
}
