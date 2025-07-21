<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('contributions', function (Blueprint $table) {
            // Ajouter la colonne de cotisation maximale RRQ
            $table->decimal('rrq_max_contribution', 10, 2)->nullable()->after('taux_de_cotisation_rrq');
        });
    }

    public function down()
    {
        Schema::table('contributions', function (Blueprint $table) {
            // Supprimer la colonne de cotisation maximale RRQ
            $table->dropColumn('rrq_max_contribution');
        });
    }
};
