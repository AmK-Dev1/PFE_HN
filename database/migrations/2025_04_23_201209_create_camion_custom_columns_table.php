<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('camion_custom_columns', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // identifiant unique ex: col_1713945034000
            $table->string('title');          // titre affiché dans le tableau
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camion_custom_columns');
    }
};


