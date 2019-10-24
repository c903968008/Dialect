<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\User;

class UserRepository extends Repository
{
    protected $model = User::class;

    public function search($search)
    {
        $user = new User();
        if (isset($search['name'])) $user = $user->where('name','like', '%'.$search['name'].'%');
        return $user;
    }

}