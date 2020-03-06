<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers;


use App\Repositories\CertificateRepository;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function __construct(Request $request, CertificateRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }

    /*
     * 用户的证书
     */
    public function userList(Request $request)
    {
        $user_id = $request->get('sub');
        $questions = $this->repository['self']->getByUser($user_id);
        if ($questions->count() == 0){
            return ResponseWrapper::fail('未获取到题目列表');
        }
        return ResponseWrapper::success($questions);
    }
}