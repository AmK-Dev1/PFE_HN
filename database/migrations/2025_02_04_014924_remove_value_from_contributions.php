<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->dropColumn('value');
        });
    }
    
    public function down()
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->decimal('value', 8, 2)->nullable(); // Remet la colonne si on rollback
        });
    }
};
