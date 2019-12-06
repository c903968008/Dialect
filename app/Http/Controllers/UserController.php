<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers;



use App\Repositories\QuestionRepository;
use App\Repositories\UserCertificateRepository;
use App\Repositories\UserDataRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(Request $request, UserRepository $repository, bool $is_with = false,
                                UserDataRepository $userDataRepository, UserCertificateRepository $userCertificateRepository,
                                QuestionRepository $questionRepository)
    {
        parent::__construct($request, $repository, $is_with);
        $this->repository['userData'] = $userDataRepository;
        $this->repository['userCertificate'] = $userCertificateRepository;
        $this->repository['question'] = $questionRepository;
    }

    /*
     * 用户答题总数、出题总数、证书总数
     */
    public function count(Request $request)
    {
        $user_id = $request->get('sub');
        $user = $this->repository['self']->getById($user_id);
        //出题总量
        $question_count = $this->repository['question']->countWithUser($user_id);
        //证书总数
        $certificate_count = $this->repository['userCertificate']->countWithUser($user_id);
        $count = [
          'answer' => $user->total,
          'questin' => $question_count,
          'certificate' => $certificate_count,
        ];
        return ResponseWrapper::success($count);
    }
}
