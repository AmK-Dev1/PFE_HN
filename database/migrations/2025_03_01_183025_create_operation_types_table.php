<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('operation_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade'); // 🔹 Lié à une entreprise
            $table->string('name')->unique(); // 🔹 Nom unique pour éviter les doublons
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('operation_types');
    }
};

