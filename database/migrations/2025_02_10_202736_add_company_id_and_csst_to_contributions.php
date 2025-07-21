<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id'); // Ajout du company_id
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->decimal('csst_rate', 5, 2)->nullable()->after('fss_rate'); // Ajout du taux CSST
        });
    }

    public function down()
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'csst_rate']);
        });
    }
};

