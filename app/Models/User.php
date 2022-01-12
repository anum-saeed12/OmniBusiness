<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'users';
    protected $hidden = ['password','remember_token'];
    protected $fillable = ['client_id', 'employee_id', 'email', 'username', 'password', 'user_role', 'created_by'];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id')->whereNull('deleted_at');
    }

    public function data()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id')
            ->with('position');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function prefix()
    {
        return $this->hasOne(Client::class,);
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */

    public function getJWTCustomClaims()
    {
        return [];
    }
}
