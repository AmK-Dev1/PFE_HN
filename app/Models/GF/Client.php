<?php

namespace App\Models\GF;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'gf_clients';

    protected $fillable = ['name', 'email', 'phone', 'company_name', 'address', 'city', 'postal_code', 'country', 'notes'];
}
