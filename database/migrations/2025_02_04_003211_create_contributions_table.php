<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->integer('year'); // Année
            $table->decimal('value', 8, 2); // Valeur en %

            // RRQ
            $table->decimal('rrq_max_salary', 10, 2)->nullable(); // Manuel
            $table->decimal('rrq_exemption', 10, 2)->nullable(); // Manuel
            $table->decimal('rrq_rate_employee', 5, 2)->nullable(); // Manuel
            $table->decimal('rrq_rate_employer', 5, 2)->nullable(); // Manuel
            $table->decimal('rrq_max_gains', 10, 2)->nullable(); // Calculé: rrq_max_salary - rrq_exemption
            $table->decimal('rrq_hourly_exemption', 10, 4)->nullable(); // Calculé: rrq_exemption / 2080
            $table->decimal('rrq_hourly_contribution', 10, 4)->nullable(); // Calculé: cotisation max RRQ / 2080

            // AE (Assurance Emploi)
            $table->decimal('ae_max_salary', 10, 2)->nullable(); // Manuel
            $table->decimal('ae_rate_employee', 5, 2)->nullable(); // Manuel
            $table->decimal('ae_rate_employer', 5, 2)->nullable(); // Manuel
            $table->decimal('ae_max_employee', 10, 2)->nullable(); // Calculé: ae_max_salary * ae_rate_employee
            $table->decimal('ae_max_employer', 10, 2)->nullable(); // Calculé: ae_rate_employer * ae_max_employee
            $table->decimal('ae_hourly_contribution', 10, 4)->nullable(); // Calculé: ae_max_employer / 2080

            // RQAP (Régime Québécois d'Assurance Parentale)
            $table->decimal('rqap_max_salary', 10, 2)->nullable(); // Manuel
            $table->decimal('rqap_rate_employee', 5, 2)->nullable(); // Manuel
            $table->decimal('rqap_rate_employer', 5, 2)->nullable(); // Manuel
            $table->decimal('rqap_max_contribution', 10, 2)->nullable(); // Calculé: rqap_max_salary * rqap_rate_employer
            $table->decimal('rqap_hourly_contribution', 10, 4)->nullable(); // Calculé: rqap_max_contribution / 2080

            // CNT (Cotisation aux Normes du Travail)
            $table->decimal('cnt_max_salary', 10, 2)->nullable(); // Manuel
            $table->decimal('cnt_rate', 5, 2)->nullable(); // Manuel
            $table->decimal('cnt_max_contribution', 10, 2)->nullable(); // Calculé: cnt_max_salary * cnt_rate
            $table->decimal('cnt_hourly_contribution', 10, 4)->nullable(); // Calculé: cnt_max_contribution / 2080

            // FSSQ (Fonds des Services de Santé du Québec)
            $table->decimal('fss_rate', 5, 2)->nullable(); // Manuel

            $table->timestamps(); // created_at et updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('contributions');
    }
};
