<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseWrapper;
use App\Repositories\DialectRepository;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct(Request $request, QuestionRepository $repository, bool $is_with = true, DialectRepository $dialectRepository)
    {
        parent::__construct($request, $repository, $is_with);
        $this->repository['dialect'] = $dialectRepository;
    }

    public function createBlock(Request $request)
    {
        $createRules = [
            'dialect_id' => 'required',
            'district_id' => 'required',
            'wrong' => 'required',
            'difficulty' => 'required'
        ];
        $this->setCreateRules($createRules);

        $createData = [
            'user_id' => 0,
            'district_id' => $request->get('district_id'),
            'dialect_id' => $request->get('dialect_id'),
            'wrong' => $request->get('wrong'),
            'difficulty' => $request->get('difficulty'),
        ];
        $this->setCreateData($createData);
    }

    public function editBlock(Request $request)
    {
        $editRules = [
            'id' => 'required',
            'dialect_id' => 'required',
            'district_id' => 'required',
            'wrong' => 'required',
            'difficulty' => 'required'
        ];
        $this->setEditRules($editRules);

        $editData = [
            'dialect_id' => $request->get('dialect_id'),
            'district_id' => $request->get('district_id'),
            'wrong' => $request->get('wrong'),
            'difficulty' => $request->get('difficulty'),
        ];
        $this->setEditData($editData);
    }

    public function index(Request $request)
    {
        $search = json_decode($request->get('search'),true);
        $dialect = $this->repository['self']->search($search);
        $dialect = $dialect->orderBy('id','DESC');
        $page = getParam($request,'page',1);
        $size = getParam($request,'size',20);
        $dialect = $this->repository['self']->getAll($dialect);
        $count = $dialect->count();
        if ($count == 0){
            return ResponseWrapper::success(['count'=>$count,'reslut'=>$dialect]);
        }
        $dialect = $this->repository['self']->page($dialect,$page,$size);
        return ResponseWrapper::success(['count'=>$count,'reslut'=>$dialect]);
    }

    public function create(Request $request)
    {
        $this->validate($request, $this->createRules);
        $dialect = $this->repository['dialect']->getById($this->createData['dialect_id']);
        $this->createData['audio'] = $dialect->audio;
        $flag = $this->repository['self']->insert($this->createData);
        if(isset($flag)){
            return ResponseWrapper::success();
        }
        return ResponseWrapper::fail();
    }

    public function edit(Request $request)
    {
        $this->validate($request, $this->editRules);
        $id = $request->get('id');
        $dialect = $this->repository['dialect']->getById($this->createData['dialect_id']);
        $this->editData['audio'] = $dialect->audio;
        $flag = $this->repository['self']->update($id,$this->editData);
        if($flag){
            return ResponseWrapper::success($flag);
        }
        return ResponseWrapper::fail();
    }

}
