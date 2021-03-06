<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickName', 'avatarUrl', 'openid', 'right', 'total'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'openid',
    ];

    /**
     * JWT
     *
     * @author AdamTyn
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * JWT
     *
     * @author AdamTyn
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function certificates()
    {
        return $this->belongsToMany(Certificate::class, 'user_certificate', 'user_id', 'certificate_id')
            ->as('user_certificate');
    }

}
