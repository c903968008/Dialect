<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers;



use App\Repositories\DistrictRepository;
use Illuminate\Http\Request;

class DistrictController extends Controller
{

    public function __construct(Request $request, DistrictRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }

    public function list()
    {
        $district = $this->repository['self']->all()->toArray();
        if (count($district) == 0){
            return ResponseWrapper::fail('获取地区列表失败');
        }
        // 初始化，然后调用分组方法
        $list = Character::groupByInitials($district, 'name');
        return ResponseWrapper::success($list);
    }

}
