<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\UserCertificate;

class UserCertificateRepository extends Repository
{
    protected $model = UserCertificate::class;

    /*
     * 根据用户id查询证书数
     */
    public function countWithUser($user_id)
    {
        return UserCertificate::where('user_id',$user_id)->count();
    }
}
