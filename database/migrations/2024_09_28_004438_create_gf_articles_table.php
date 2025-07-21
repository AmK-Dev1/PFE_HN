<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('gf_articles', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('unit_cost', 10, 2);
            $table->enum('type', ['maindoeuvre', 'transport', 'soutraitance', 'material']);
            $table->string('reference')->unique();
            $table->string('unit_of_measure')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gf_articles');
    }
};
