<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers;


use App\Dialect;
use App\Feedback;
use App\Repositories\DialectRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function __construct(Request $request, FeedbackRepository $repository, bool $is_with = true,
                                QuestionRepository $questionRepository, DialectRepository $dialectRepository)
    {
        parent::__construct($request, $repository, $is_with);
        $this->repository['question'] = $questionRepository;
        $this->repository['dialect'] = $dialectRepository;
    }

    public function createBlock(Request $request)
    {
        $createRules = [
            'question_id' => 'required|integer',
//            'dialect_id' => 'required|integer',
            'content' => 'required|string',
            'translation' => 'required|string',
        ];
        $this->setCreateRules($createRules);

        $createData = [
            'user_id' => $request->get('sub'),
            'question_id' => $request->get('question_id'),
//            'dialect_id' => $request->get('dialect_id'),
            'content' => $request->get('content'),
            'translation' => $request->get('translation'),
        ];
        $this->setCreateData($createData);
    }

    /*
     * 用户需要查看的反馈信息（0未查看/1未接受/2已接受）
     */
    public function index(Request $request)
    {
        $validateRules = [
            'status' => 'required|integer',
        ];
        $this->validate($request, $validateRules);

        $status = $request->get('status');
        $user_id = $request->get('sub');
        $question_ids = $this->repository['question']->getIdsByUser($user_id);
        $feedback = $this->repository['self']->getByQueStatus($question_ids,$status);
        if($feedback->count() != 0){
            return ResponseWrapper::success($feedback);
        }
        return ResponseWrapper::fail('数据不存在');
    }

    /*
     * 修改状态
     */
    public function status(Request $request)
    {
        $validateRules = [
            'id' => 'required|integer',
            'status' => 'required|integer',
        ];
        $this->validate($request, $validateRules);

        $status = $request->get('status');
        $id = $request->get('id');
        $flag = $this->repository['self']->update($id,['status' => $status]);
        if ($flag) {
            return ResponseWrapper::success();
        }
        return ResponseWrapper::fail();
    }

    /*
     * 接受反馈信息
     */
    public function accept(Request $request)
    {
        $validateRules = [
            'id' => 'required|integer',
            'dialect_id' => 'required|integer',
            'translation' => 'required|string',
        ];
        $this->validate($request, $validateRules);

        $id = $request->get('id');
        $dialect_id = $request->get('dialect_id');

        $dialect = $this->repository['dialect']->getById($dialect_id);
        if ($dialect->user_id == 0){
            $this->repository['self']->delete($id);
            return ResponseWrapper::success();
        }
        $dflag = $this->repository['dialect']->update($dialect_id,[
            'status' => Dialect::UNAUDITED,
            'translation' => $request->get('translation'),
        ]);
        if (!$dflag) {
            return ResponseWrapper::fail('方言bug');
        }
        $fflag = $this->repository['self']->update($id,['status' => Feedback::ACCEPTED]);
        if (!$fflag) {
            return ResponseWrapper::fail('反馈bug');
        }
        return ResponseWrapper::success();
    }

}
