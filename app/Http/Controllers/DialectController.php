<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers;



use App\Repositories\DialectRepository;
use Illuminate\Http\Request;

class DialectController extends Controller
{

    public function __construct(Request $request, DialectRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }


    /*
     * 根据地区查询方言
     */
    public function getByDistrict(Request $request)
    {
        $validateRules = [
            'district_id' => 'required|integer',
            'search' => 'nullable',
        ];
        $this->validate($request, $validateRules);

        $district_id = $request->get('district_id');
        $search = json_decode($request->get('search'),true);
        $dialect = $this->repository['self']->search($search);

        $dialect = $this->repository['self']->getByDistrict($district_id,$dialect);
        if (count($dialect) == 0){
            return ResponseWrapper::fail('未获取到方言');
        }
        return ResponseWrapper::success($dialect);

    }

}
