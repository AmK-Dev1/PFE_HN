<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('entetes_activites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_type_id')->constrained('operation_types')->onDelete('cascade'); // 🔹 Lié à une activité (Administration, Opération, Analyse, CCQ)
            
            // 🔹 Informations de l'entête
            $table->year('annee')->nullable(); // Année de l'activité (ex : 2024)
            $table->date('date_mise_a_jour')->nullable(); // Date de mise à jour (ex : 2025-05-12)
            
            // 🔹 Paramètres de gestion du temps
            $table->integer('minutes_pause')->nullable(); // Minutes de pause par heure (ex : 0)
            $table->integer('minutes_temps_mort')->nullable(); // Minutes de temps mort par heure (ex : 0)
            $table->decimal('pourcentage_pause', 5, 2)->nullable(); // Pourcentage de temps de pause (ex : 0.00 %)
            $table->decimal('pourcentage_temps_mort', 5, 2)->nullable(); // Pourcentage de temps mort (ex : 0.00 %)

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entetes_activites');
    }
};

