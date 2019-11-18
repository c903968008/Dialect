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

    /*
     * 根据答题正确率排名
     */
    public function getOrderByRight()
    {
        $users = User::get();
        foreach ($users as &$user){
            $user->accuracy = number_format($user->right / $user->total, 2);
        }
        return $users->sortByDesc('accuracy');
    }

}