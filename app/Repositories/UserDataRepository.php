<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\UserData;

class UserDataRepository extends Repository
{
    protected $model = UserData::class;

    /*
     * 根据地区和答题争取率排名
     */
    public function getOrderByRightAndDistrict($district_id)
    {
        $users = UserData::get();
        foreach ($users as &$user){
            $user->accuracy = number_format($user->right / $user->total, 2);
        }
        return $users->sortByDesc('accuracy');
    }

}