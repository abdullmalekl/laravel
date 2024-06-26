<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'last_name',
        'image',
        'role',
        'email',
        'password',
    ];
    protected $hidden = [
        'password',
       ];

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
