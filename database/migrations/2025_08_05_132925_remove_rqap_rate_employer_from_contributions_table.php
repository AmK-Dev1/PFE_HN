<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->dropColumn('rqap_rate_employer');
        });
    }

    public function down()
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->decimal('rqap_rate_employer', 5, 2)->nullable();
        });
    }
};