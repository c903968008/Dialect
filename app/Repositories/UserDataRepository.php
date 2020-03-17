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
     * 根据地区和答题正确率率排名
     */
    public function getOrderByRightAndDistrict($district_id)
    {
        $users = UserData::where('district_id',$district_id)->with(['user' => function($query){
            $query->select('id','nickName','avatarUrl');
        }])->get();
        foreach ($users as &$user){
            $user->accuracy = number_format($user->right / $user->total, 2) * 100;
        }
        return $users->sortByDesc('accuracy');
    }

    /*
     * 计分
     */
    public function calculateScores($user_id,$district_id,$right,$total)
    {
        $user_data = UserData::where([
            'user_id' => $user_id,
            'district_id' => $district_id
        ])->first();
        if (isset($user_data)){         //记录已存在
            $user_data->right += $right;
            $user_data->total += $total;
            if($user_data->save()){
                return true;
            }
            return false;
        } else {                        //记录不存在
            $model = $this->insert([
                'user_id' => $user_id,
                'district_id' => $district_id,
                'right' => $right,
                'total' => $total,
            ]);
            if (isset($model)){
                return true;
            }
            return false;
        }
    }

}