<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'year',
        'code',
        'credit',
        'debit',
        'amount',
        'description',
        'type_id',
        'subtype_id',
    ];

    /**
     * Get the company that owns the budget.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Relation to Type
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    // Relation to Subtype
    public function subtype()
    {
        return $this->belongsTo(Subtype::class);
    }

}
