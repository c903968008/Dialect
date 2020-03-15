<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers;



use App\Repositories\DialectRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;

class DistrictController extends Controller
{

    public function __construct(Request $request, DistrictRepository $repository, bool $is_with = true,
                                DialectRepository $dialectRepository,QuestionRepository $questionRepository)
    {
        parent::__construct($request, $repository, $is_with);
        $this->repository['dialect'] = $dialectRepository;
        $this->repository['question'] = $questionRepository;
    }

    /*
     * 获取地区列表（根据拼音首字母分组排序）
     */
    public function list(Request $request)
    {
        $validateRules = [
            'type' => 'required|string'
        ];
        $this->validate($request, $validateRules);

        $type = $request->get('type');
        $district_ids = [];
        if ($type == 'learn') {
            $district_ids = $this->repository['dialect']->getDistrictIds();
        } else if ($type == 'answer') {
            $district_ids = $this->repository['question']->getDistrictIds();
        }

        $search = json_decode($request->get('search'),true);
        $district = $this->repository['self']->search($search);
        $district = $this->repository['self']->getByIds($district_ids,$district)->toArray();
        if (!isset($district)){
            return ResponseWrapper::fail('未获取到地区列表');
        }
        // 拼音首字母分组排序
        $list = Character::groupByInitials($district, 'name');
        return ResponseWrapper::success($list);
    }

    //根据p_id查
    public function listByPid(Request $request)
    {
        $validateRules = [
            'p_id' => 'required|integer'
        ];
        $this->validate($request, $validateRules);

        $p_id = $request->get('p_id');
        $district = $this->repository['self']->getByPid($p_id);
        return ResponseWrapper::success($district);
    }


}
