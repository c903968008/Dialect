<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\Certificate;
use App\User;
use App\UserCertificate;
use App\UserData;

class UserCertificateRepository extends Repository
{
    protected $model = UserCertificate::class;

    /*
     * 根据用户id查询证书数
     */
    public function countByUser($user_id)
    {
        return UserCertificate::where('user_id',$user_id)->count();
    }

    /*
     * 根据用户id查询
     */
    public function getByUser($user_id)
    {
        return UserCertificate::where('user_id',$user_id)->pluck('certificate_id');
    }

    /*
     * 判断是否获取证书
     */
    public function getCertificate($user_id, $district_id)
    {
        $user_data = UserData::where(['user_id' => $user_id, 'district_id' => $district_id])->first();
        $has_certificates = UserCertificate::where('user_id',$user_id)->pluck('certificate_id');
        $certificates = Certificate::whereNotIn('id',$has_certificates)->where('district_id',$district_id)->get();
        // 用户正确答题数  $user_data->right;
        // 证书需要的答题数 $certificates->num;
        $certificate_ids = [];
        foreach ($certificates as $value){
            if ($user_data->right >= $value->num){
                //获得证书
                array_push($certificate_ids, $value->id);
            }
        }
        if (!empty($certificate_ids)){
            $user = User::find($user_id);
            $user->certificates()->sync($certificate_ids);
            return Certificate::whereIn('id',$certificate_ids)->get(['id','name']);
        }
        return false;
    }
}
