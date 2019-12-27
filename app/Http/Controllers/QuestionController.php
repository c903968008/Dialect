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
     * 上传音频
     */
    public function upload(Request $request)
    {
        if(!empty($request->file())){
            $file = $request->file('audio');
            if($file -> isValid()) {
                $clientName = $file -> getClientOriginalName(); //客户端文件名称..
                $tmpName = $file ->getFileName(); //缓存在tmp文件夹中的文件名例php8933.tmp 这种类型的.
                $realPath = $file -> getRealPath(); //这个表示的是缓存在tmp文件夹下的文件的绝对路径
                $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
                $mimeTye = $file -> getMimeType(); //也就是该资源的媒体类型
                $newName = $newName = md5(date('ymdhis').$clientName).".". $entension; //定义上传文件的新名称
                $path = $file -> move('audio/',$newName); //把缓存文件移动到制定文件夹
                return $newName;
            }
            return false;
        }
        return false;
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

        $audio = $this->upload($request);
        if (!$audio){
            return ResponseWrapper::fail('音频上传失败');
        }

        //判断地区
        $district = $this->repository['district']->getByName($district_name);
        if (isset($district)) {  //地区已存在
            $district_id = $district->id;
        } else {    //地区不存在
            $create_district = $this->repository['district']->insert(['name' => $district_name]);
            if (!isset($create_district)){
                return ResponseWrapper::fail('地区创建失败');
            }
            $district_id = $create_district->id;
        }

        //判断方言
        $dialect = $this->repository['dialect']->getByTranslation($dialect_name);
        if (isset($district)) {  //方言已存在
            $dialect_id = $dialect->id;
        } else {    //地区不存在
            $create_dialect = $this->repository['dialect']->insert([
                'user_id' => $user_id,
                'district_id' => $district_id,
                'translation' => $dialect_name,
                'status' => Dialect::UNAUDITED,
            ]);
            if (!isset($create_dialect)){
                return ResponseWrapper::fail('方言创建失败');
            }
            $dialect_id = $create_dialect->id;
        }

        $data = [
            'user_id' => $user_id,
            'dialect_id' => $dialect_id,
            'wrong' => $request->get('wrong'),
            'difficulty' => $request->get('difficulty'),
            'audio' => $audio,
        ];

        $flag = $this->repository['self']->insert($data);
        if (count($flag)) {
            return ResponseWrapper::success();
        }
        return ResponseWrapper::fail();
    }

    /*
     * 答题列表
     */
    public function answerList(Request $request)
    {
        $validateRules = [
            'district_id' => 'required|integer',
        ];
        $this->validate($request, $validateRules);

        $district_id = $request->get('district_id');
        $dialect = $this->repository['dialect']->getByDistrict($district_id);
        $dialect_ids = $dialect->pluck('id');
        $questions = $this->repository['self']->getByDialects($dialect_ids);
        if ($questions->count() == 0){
            return ResponseWrapper::fail('未获取到题目');
        }
        return ResponseWrapper::success($questions);
    }

    /*
     * 答题
     */
    public function answer(Request $request)
    {
        $validateRules = [
            'id' => 'required|integer',
            'answer' => 'required|string'
        ];
        $this->validate($request, $validateRules);

        $id = $request->get('id');
        $answer = $request->get('answer');
        if($this->repository['self']->isWrong($id,$answer)){
            return ResponseWrapper::fail('回答错误');
        }
        $dialect = $this->repository['dialect']->getByTranslation($answer);
        if (!isset($dialect)){
            return ResponseWrapper::fail('回答错误');
        }
        return ResponseWrapper::success();
    }

    /*
     * 用户的题目列表
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
