<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers;


use App\Repositories\CertificateRepository;
use App\Repositories\UserCertificateRepository;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function __construct(Request $request, CertificateRepository $repository, bool $is_with = true,
                                UserCertificateRepository $userCertificateRepository)
    {
        parent::__construct($request, $repository, $is_with);
        $this->repository['userCertificate'] = $userCertificateRepository;
    }

    /*
     * 用户的证书
     */
    public function userList(Request $request)
    {
        $user_id = $request->get('sub');
        $ids = $this->repository['userCertificate']->getByUser($user_id);
        $certificate = $this->repository['self']->getByUser($ids);
        if ($certificate->count() == 0){
            return ResponseWrapper::fail('未获取到证书列表');
        }
        return ResponseWrapper::success($certificate);
    }
}