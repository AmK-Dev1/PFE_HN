<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'meeting_date',
        'meeting_type',
        'reference',
        'task',
        'responsible_name',
        'responsible_email',
        'due_date',
        'status',
        'comments'
    ];

    protected $dates = ['due_date', 'meeting_date'];
}
