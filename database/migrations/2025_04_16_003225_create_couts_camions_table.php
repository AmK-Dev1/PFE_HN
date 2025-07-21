<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('couts_camions', function (Blueprint $table) {
            $table->id();
            $table->string('unite')->nullable();
            $table->year('annee_de_construction')->nullable();
            $table->string('no_plaque')->nullable();
            $table->string('responsable')->nullable();
            $table->string('marque')->nullable();
            $table->decimal('cout_km', 10, 2)->nullable();
            $table->integer('km_parcourus')->nullable();
            $table->decimal('cout_hr', 10, 2)->nullable();
            $table->integer('heures')->nullable();
            $table->decimal('carburant', 10, 2)->nullable();
            $table->decimal('entretien', 10, 2)->nullable();
            $table->decimal('immatriculation', 10, 2)->nullable();
            $table->decimal('assurance', 10, 2)->nullable();
            $table->decimal('interet', 10, 2)->nullable();
            $table->decimal('location', 10, 2)->nullable();
            $table->decimal('amortissement', 10, 2)->nullable();
            $table->decimal('total_depenses', 12, 2)->nullable();
            $table->json('colonnes_personnalisees')->nullable();
            $table->boolean('est_moyenne')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('couts_camions');
    }
};
