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
            'district_id' => 'required|integer',
            'difficulty' => 'required|integer',
            'dialect' => 'required|string',
            'wrong' => 'required|string',
        ];
        $this->validate($request, $validateRules);

        $district_id = $request->get('district_id');
        $dialect_name = $request->get('dialect');
        $user_id = $request->get('sub');

        //判断方言
        $dialect = $this->repository['dialect']->getByTraDis($dialect_name,$district_id);
        if (count($dialect)) {  //方言已存在
            $dialect_id = $dialect->id;
        } else {    //方言不存在
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
            'district_id' => $district_id,
            'wrong' => $request->get('wrong'),
            'difficulty' => $request->get('difficulty'),
        ];

        $flag = $this->repository['self']->insert($data);
        if (count($flag)) {
            return ResponseWrapper::success($flag);
        }
        return ResponseWrapper::fail();
    }

    /*
     * 修改题目
     */
    public function edit(Request $request)
    {
        $validateRules = [
            'id' => 'required|integer',
            'district_id' => 'required|integer',
            'difficulty' => 'required|integer',
            'dialect' => 'required|string',
            'wrong' => 'required|string',
        ];
        $this->validate($request, $validateRules);

        $id = $request->get('id');
        $district_id = $request->get('district_id');
        $dialect_name = $request->get('dialect');

        $question = $this->repository['self']->getById($id);
        $dialect = $this->repository['dialect']->getById($question->dialect_id);
        //修改方言
        if ($dialect->district_id != $district_id || $dialect->translation != $dialect_name) {
            $dialectData = [
                'district_id' => $district_id,
                'translation' => $dialect_name,
                'status' => Dialect::UNAUDITED,
            ];
            $flag1 = $this->repository['dialect']->update($dialect->id,$dialectData);
            if (!$flag1){
                return ResponseWrapper::fail('方言修改bug');
            }
        }

        //修改题目
        $data = [
            'district_id' => $district_id,
            'wrong' => $request->get('wrong'),
            'difficulty' => $request->get('difficulty'),
        ];

        $flag = $this->repository['self']->update($id,$data);
        if($flag){
            return ResponseWrapper::success();
        }
        return ResponseWrapper::fail('问题修改bug');
    }

    /*
     * 上传录音（创建）
     */
    public function uploadAudioCreate(Request $request)
    {
        $validateRules = [
            'id' => 'required|integer',
            'dialect_id' => 'required|integer',
            'audio' => 'required'
        ];
        $this->validate($request, $validateRules);

        $id = $request->get('id');
        $dialect_id = $request->get('dialect_id');
        $audio = $this->upload($request);
        if ($audio == false){
            return ResponseWrapper::fail('音频上传失败');
        }
        $flag = $this->repository['self']->update($id,['audio'=>$audio]);
        if ($flag == false){
            return ResponseWrapper::fail();
        }

        $dialect = $this->repository['dialect']->getById($dialect_id);
        if (empty($dialect->audio)){ //方言音频为空
            $flag = $this->repository['dialect']->update($dialect_id,['audio'=>$audio]);
            if ($flag == false){
                return ResponseWrapper::fail();
            }
        }
        return ResponseWrapper::success();

    }

    /*
     * 上传录音（修改）
     */
    public function uploadAudioEdit(Request $request)
    {
        $validateRules = [
            'id' => 'required|integer',
            'dialect_id' => 'required|integer',
            'audio' => 'required'
        ];
        $this->validate($request, $validateRules);

        $id = $request->get('id');
        $dialect_id = $request->get('dialect_id');
        $audio = $this->upload($request);
        if ($audio == false){
            return ResponseWrapper::fail('音频上传失败');
        }
        $flag = $this->repository['self']->update($id,['audio'=>$audio]);
        if ($flag == false){
            return ResponseWrapper::fail();
        }

        $flag = $this->repository['dialect']->update($dialect_id,['audio'=>$audio]);
        if ($flag == false){
            return ResponseWrapper::fail();
        }
        return ResponseWrapper::success();

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

    /*
     * 点赞和取消点赞
     */
    public function good(Request $request)
    {
        $validateRules = [
            'id' => 'required|integer',
            'status' => 'required|boolean'
        ];
        $this->validate($request, $validateRules);

        $id = $request->get('id');
        $status = $request->get('status');
        $flag = $this->repository['self']->updateLike($id,$status);
        if ($flag){
            return ResponseWrapper::success();
        }
        return ResponseWrapper::fail();
    }
}
