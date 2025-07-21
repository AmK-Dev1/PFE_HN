<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationType extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'name'];

    /**
     * ✅ Relation avec la compagnie
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function employees()
{
    return $this->hasMany(Employee::class);
}
}
