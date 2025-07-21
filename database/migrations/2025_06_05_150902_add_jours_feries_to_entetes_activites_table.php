<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('entetes_activites', function (Blueprint $table) {
        $table->integer('jours_feries')->default(0)->after('pourcentage_temps_mort');
    });
}

public function down()
{
    Schema::table('entetes_activites', function (Blueprint $table) {
        $table->dropColumn('jours_feries');
    });
}
};
