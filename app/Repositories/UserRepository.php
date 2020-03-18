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
    protected $with = 'certificates';

    public function search($search)
    {
        $user = new User();
        if (isset($search['nickName'])) $user = $user->where('nickName','like', '%'.$search['nickName'].'%');
        if (isset($search['accuracy_min'])) {
            $user = $user->where('accuracy', '>=', $search['accuracy_min']);
        }
        if (isset($search['accuracy_max'])) {
            $user = $user->where('accuracy', '<=', $search['accuracy_max']);
        }
        return $user;
    }

    public function all(bool $bool = false, array $search = ['is' => false, 'model' => null])
    {
        //搜索
        if ($search['is']){
            if ($bool && !empty($this->with)){
                if (is_array($this->with)){
                    foreach ($this->with as $key => $value){
                        $model = $search['model']->with($value);
                    }
                    return $model->get();
                } else{
                    return $search['model']->with($this->with)->get();
                }
            }
            return $search['model']->get();
        } else {
            if ($bool && !empty($this->with)){
                if (is_array($this->with)){
                    foreach ($this->with as $key => $value){
                        if ($key == 0){
                            $model = $this->model::has($value)->with($value);
                        }
                        $model = $model->with($value);
                    }
                    return $model->get();
                } else{
                    return $this->model::with($this->with)->get();
                }
            }
            return $this->model::get();
        }
    }

    /*
     * 根据答题正确率排名
     */
    public function getOrderByRight()
    {
        return User::orderBy('accuracy','desc')->get();
    }

    /*
     * 根据答题总数排行
     */
    public function getOrderByTotal()
    {
        return User::orderBy('total','desc')->get();
    }

    /*
     * 登录时已有用户则更新，没有则添加
     */
    public function updateOrCreate($data)
    {
        $user = User::updateOrCreate(['openid' => $data['openid']], ['name' => $data['name'], 'avatar' => $data['avatar']]);
        return $user;
    }

    /*
     * 根据openid获取用户信息
     */
    public function getByOpenId($openid)
    {
        $user = User::where('openid', $openid)->first();
        return $user;
    }

    /*
     * 计分
     */
    public function calculateScores($user_id,$right,$total)
    {
        $user = User::find($user_id);
        $user->right += $right;
        $user->total += $total;
        $user->accuracy = round($user->right / $user->total * 100);
        if ($user->save()){
            return true;
        }
        return false;
    }
}
