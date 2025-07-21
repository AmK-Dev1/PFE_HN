<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory,Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'image',
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

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */



    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @var array<string, string>
     */
    protected $appends = [
        'image_url','initial'
    ];

    public function userPath()
    {
        return route('admin.users.show', $this->id);
    }

    public function initial(): Attribute
    {
        return new Attribute(get: fn() => Str::limit($this->name,1,''));
    }

    /**
     * @param $token
     */

    protected $guard_name = 'web';

    protected function getDefaultGuardName(): string { return 'web'; }

}
