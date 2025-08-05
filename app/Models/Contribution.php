<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use HasFactory;

    /**
     * Définition des champs autorisés à l'enregistrement
     */
    protected $fillable = [
        'year', 'company_id', 'csst_rate', 

        // RRQ
        'rrq_max_salary', 'rrq_exemption', 'taux_de_cotisation_rrq', 'rrq_max_contribution', // Nouveau champ
        'rrq_max_gains', 'rrq_hourly_exemption', 'rrq_hourly_contribution', // ✅ AJOUT
    
        // AE
        'ae_max_salary', 'ae_rate_employee', 'ae_rate_employer',
        'ae_max_employee', 'ae_max_employer', 'ae_hourly_contribution', // ✅ AJOUT
    
        // RQAP
        'rqap_max_salary', 'rqap_rate_employee',
        'rqap_max_contribution', 'rqap_hourly_contribution', // ✅ AJOUT
    
        // CNT
        'cnt_max_salary', 'cnt_rate',
        'cnt_max_contribution', 'cnt_hourly_contribution', // ✅ AJOUT
    
        // FSSQ
        'fss_rate'
    ];

    
    public function company()
{
    return $this->belongsTo(Company::class, 'company_id');
}
    /**
     * Définition des valeurs calculées directement dans Laravel
     */

    // 🔹 Calcul du Maximum des gains cotisables pour RRQ
    public function getRrqMaxGainsAttribute()
    {
        return $this->rrq_max_salary - $this->rrq_exemption;
    }

    // 🔹 Calcul de l'Exemption RRQ à l'heure
    public function getRrqHourlyExemptionAttribute()
    {
        return $this->rrq_exemption / 2080;
    }

    // 🔹 Calcul de la Cotisation RRQ à l'heure
    public function getRrqHourlyContributionAttribute()
    {
        return $this->rrq_max_contribution/2080;
    }

    // 🔹 Calcul de la Cotisation max AE de l’employé
    public function getAeMaxEmployeeAttribute()
    {
        return $this->ae_max_salary * ($this->ae_rate_employee / 100);
    }

    // 🔹 Calcul de la Cotisation max AE de l’employeur
    public function getAeMaxEmployerAttribute()
    {
        return $this->ae_max_employee * $this->ae_rate_employer;
    }

    // 🔹 Calcul de la Cotisation max AE à l’heure
    public function getAeHourlyContributionAttribute()
    {
        return $this->ae_max_employer / 2080;
    }

    // 🔹 Calcul de la Cotisation max RQAP
    public function getRqapMaxContributionAttribute()
    {
        return $this->rqap_max_salary * ($this->rqap_rate_employee / 100);
    }

    // 🔹 Calcul de la Cotisation max CNT
    public function getCntMaxContributionAttribute()
    {
        return $this->cnt_max_salary * ($this->cnt_rate / 100);
    }

    // 🔹 Calcul de la Cotisation CNT à l’heure
    public function getCntHourlyContributionAttribute()
    {
        return $this->cnt_max_contribution / 2080;
    }
}
