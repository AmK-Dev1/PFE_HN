<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admins';
    protected $fillable = [
        'name',
        'image',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var array<string, string>
     */
    protected $appends = [
        'image_url','initial'
    ];

    public function adminPath()
    {
        return route('admin.admins.show', $this->id);
    }

    public function initial(): Attribute
    {
        return new Attribute(get: fn() => Str::limit($this->name,1,''));
    }

    /**
     * @param $token
     */


}
