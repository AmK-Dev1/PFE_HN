<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_type_id')->constrained('operation_types')->onDelete('cascade'); // 🔹 Lié à une activité (Administration, Opération, Analyse)
            
            // 🔹 Informations de base de l'employé
            $table->string('employee_name'); // Nom de l'employé
            $table->string('position')->nullable(); // Poste
            $table->integer('hours_worked_annual')->nullable(); // Heures travaillées annuellement (incluant les congés)
            $table->integer('weeks_worked')->nullable(); // Nombre de semaines travaillées
            $table->decimal('vacation_rate', 5, 2)->nullable(); // Taux de vacance
            $table->decimal('hourly_rate', 10, 2)->nullable(); // Taux Horaire de base
            $table->decimal('annual_salary_base', 10, 2)->nullable(); // Salaire annuel de base
            $table->decimal('retirement_fund', 10, 2)->nullable(); // Fond de retraite
            $table->decimal('bonus', 10, 2)->nullable(); // Boni
            $table->decimal('group_insurance', 10, 2)->nullable(); // Assurance groupe
            $table->decimal('other_benefits_hourly', 10, 2)->nullable(); // Autres avantages (taux horaire)
            $table->decimal('paid_vacation', 10, 2)->nullable(); // Vacances Payées
            $table->decimal('paid_leave', 10, 2)->nullable(); // Congés Payés
            $table->decimal('adjusted_hourly_rate', 10, 2)->nullable(); // Taux horaire corrigé
            
            // 🔹 Charges et contributions sociales
            $table->decimal('rrq', 10, 2)->nullable(); // RRQ
            $table->decimal('ae', 10, 2)->nullable(); // AE
            $table->decimal('rqap', 10, 2)->nullable(); // RQAP
            $table->decimal('csst', 10, 2)->nullable(); // CSST
            $table->decimal('fssq', 10, 2)->nullable(); // FSSQ
            $table->decimal('cnt', 10, 2)->nullable(); // CNT
            $table->decimal('other_benefits', 10, 2)->nullable(); // Autres Bénéfices taux horaire

            // 🔹 Coût et taux
            $table->decimal('rate_before_downtime', 10, 2)->nullable(); // Taux avant pauses, congés et temps mort
            $table->decimal('total_annual_cost', 10, 2)->nullable(); // Coût Annuel Total
            $table->decimal('non_taxable_dividends', 10, 2)->nullable(); // Dividende et autres avantages non imposables
            
            // 🔹 Temps productif et non productif
            $table->integer('breaks_per_hour')->nullable(); // Pauses en minutes/heure
            $table->integer('idle_time_per_hour')->nullable(); // Temps mort en minutes/heure
            $table->integer('total_non_productive_time')->nullable(); // Total minutes non productives/heure
            $table->integer('productive_time')->nullable(); // Temps productif par heure
            $table->decimal('productive_time_percentage', 5, 2)->nullable(); // % de temps productif
            
            // 🔹 Taux avec fardeau
            $table->decimal('rate_with_burden', 10, 2)->nullable(); // Taux Avec Fardeau
            $table->decimal('burden_percentage', 5, 2)->nullable(); // Fardeau en %

            // 🔹 Date d'embauche et ancienneté
            $table->date('hire_date')->nullable(); // Date d'embauche
            $table->integer('seniority')->nullable(); // Ancienneté (Nb années)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};

