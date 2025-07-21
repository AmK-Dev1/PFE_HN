<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('recapitulatifs_activites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_type_id')->constrained('operation_types')->onDelete('cascade'); // 🔹 Lié à une activité (Administration, Opération, Analyse, CCQ)

            // 🔹 Informations de base
            $table->decimal('total_heures', 10, 2)->nullable(); // Total Heures (ex: 7 500 h)
            $table->decimal('salaire_total', 10, 2)->nullable(); // Salaire Total (ex : 316 804 $)
            $table->decimal('total_general', 10, 2)->nullable(); // Total général (ex : 437 848 $)
            
            // 🔹 Cotisations et avantages (format standard)
            $table->decimal('vacances_total', 10, 2)->nullable(); // Vacances (ex : 1 560 $)
            $table->decimal('avantages_sociaux_total', 10, 2)->nullable(); // Avantages Sociaux (ex : 7 885 $)
            $table->decimal('rrq_total', 10, 2)->nullable(); // RRQ (ex : 21 824 $)
            $table->decimal('ae_total', 10, 2)->nullable(); // AE (ex : 6 110 $)
            $table->decimal('rqap_total', 10, 2)->nullable(); // RQAP (ex : 2 320 $)
            $table->decimal('cnt_total', 10, 2)->nullable(); // CNT (ex : 102 $)
            $table->decimal('fssq_total', 10, 2)->nullable(); // FSSQ (ex : 6 654 $)
            $table->decimal('csst_total', 10, 2)->nullable(); // CSST (ex : 4 415 $)
            $table->decimal('boni_total', 10, 2)->nullable(); // BONUS (ex : -)
            $table->decimal('assurance_groupe_total', 10, 2)->nullable(); // Assurance Groupe - ADMIN (ex : 3 121 $)

            // 🔹 Cotisation CCQ (spécifique électriciens et autres CCQ)
            $table->decimal('ccq_total', 10, 2)->nullable(); // CCQ (ex : 20 060 $)

            // 🔹 Total général (à utiliser pour toutes les activités)
            $table->decimal('cout_total', 10, 2)->nullable(); // Coût Total (ex : 437 848 $)
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recapitulatifs_activites');
    }
};

