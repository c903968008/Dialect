<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers;



use App\Dialect;
use App\Repositories\DialectRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct(Request $request, QuestionRepository $repository, bool $is_with = true,
                                DistrictRepository $districtRepository, DialectRepository $dialectRepository)
    {
        parent::__construct($request, $repository, $is_with);
        $this->repository['district'] = $districtRepository;
        $this->repository['dialect'] = $dialectRepository;
    }

    /*
     * 用户出题
     */
    public function create(Request $request)
    {
        $validateRules = [
            'district' => 'required|string',
            'difficulty' => 'required|integer',
            'dialect' => 'required|string',
            'wrong' => 'required|string',
            'audio' => 'required'
        ];
        $this->validate($request, $validateRules);

        $district_name = $request->get('district');
        $dialect_name = $request->get('dialect');
        $user_id = $request->get('sub');

        //判断地区
        $district = $this->repository['district']->getByName($district_name);
        if (count($district) > 0) {  //地区已存在
            $district_id = $district->id;
        } else {    //地区不存在
            $create_district = $this->repository['district']->create(['name' => $district_name]);
            if (count($create_district) == 0){
                return ResponseWrapper::fail('地区创建失败');
            }
            $district_id = $create_district->id;
        }

        //判断方言
        $dialect = $this->repository['dialect']->getByTranslation($dialect_name);
        if (count($district) > 0) {  //方言已存在
            $dialect_id = $dialect->id;
        } else {    //地区不存在
            $create_dialect = $this->repository['dialect']->create([
                'user_id' => $user_id,
                'district_id' => $district_id,
                'translation' => $dialect_name,
                'status' => Dialect::UNAUDITED,
            ]);
            if (count($create_dialect) == 0){
                return ResponseWrapper::fail('方言创建失败');
            }
            $dialect_id = $create_dialect->id;
        }

        $data = [
            'user_id' => $user_id,
            'dialect_id' => $dialect_id,
            'wrong' => $request->get('wrong'),
            'difficulty' => $request->get('difficulty'),
        ];
        $flag = $this->repository['self']->insert($data);
        if (count($flag)) {
            return ResponseWrapper::success();
        }
        return ResponseWrapper::fail();

    }
}
