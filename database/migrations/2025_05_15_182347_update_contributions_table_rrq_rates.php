<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('contributions', function (Blueprint $table) {
            // Supprimer les anciennes colonnes
            $table->dropColumn(['rrq_rate_employee', 'rrq_rate_employer']);
            
            // Ajouter la nouvelle colonne
            $table->decimal('taux_de_cotisation_rrq', 5, 2)->nullable()->after('rrq_max_salary');
        });
    }

    public function down()
    {
        Schema::table('contributions', function (Blueprint $table) {
            // Restaurer les colonnes supprimées
            $table->decimal('rrq_rate_employee', 5, 2)->nullable();
            $table->decimal('rrq_rate_employer', 5, 2)->nullable();
            $table->dropColumn('taux_de_cotisation_rrq');
        });
    }
};
