<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $fillable = ['company_id', 'type_id', 'code_start', 'code_end'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $exists = self::where('company_id', $model->company_id)
                ->where('type_id', $model->type_id)
                ->exists();
            if ($exists) {
                throw new \Exception("Configuration already exists for this type and company.");
            }
        });
    }
}
