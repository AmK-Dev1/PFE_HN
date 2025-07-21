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
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->date('meeting_date')->nullable();
        $table->string('meeting_type')->nullable();
        $table->string('reference')->nullable();
        $table->text('task')->nullable();
        $table->string('responsible_name')->nullable();  // NOM
        $table->string('responsible_email')->nullable(); // EMAIL
        $table->date('due_date')->nullable();
        $table->enum('status', ['À faire', 'En cours', 'Terminé', 'Bloqué'])->default('À faire');
        $table->text('comments')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
