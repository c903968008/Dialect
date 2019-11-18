<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/10/28
 * Time: 10:40
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseWrapper;
use App\Repositories\DialectRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\Repository;
use App\Repositories\UserDataRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct(Request $request, Repository $repository, bool $is_with = true, UserRepository $userRepository, DialectRepository $dialectRepository, DistrictRepository $districtRepository, QuestionRepository $questionRepository, UserDataRepository $userDataRepository)
    {
        parent::__construct($request, $repository, $is_with);
        $this->repository['dialect'] = $dialectRepository;
        $this->repository['user'] = $userRepository;
        $this->repository['district'] = $districtRepository;
        $this->repository['question'] = $questionRepository;
        $this->repository['userData'] = $userDataRepository;
    }

    /*
     * 计数
     */
    public function count()
    {
        $count = [
            'user' => $this->repository['user']->count(),
            'dialect' => $this->repository['dialect']->count(),
            'district' => $this->repository['district']->count(),
            'question' => $this->repository['question']->count(),
        ];
        return ResponseWrapper::success($count);
    }

    /*
     * 总排名
     */
    public function rank()
    {
        $rank = $this->repository['user']->getOrderByRight();
        return ResponseWrapper::success($rank);
    }

    /*
     * 根据地区分类
     */
    public function rankByDistrict(Request $request)
    {
        $validateRules = [
            'district_id' => 'required|integer'
        ];
        $this->validate($request, $validateRules);

        $district_id = $request->get('district_id');
        $rank = $this->repository['userData']->getOrderByRightAndDistrict($district_id);
        return ResponseWrapper::success($rank);
    }
}