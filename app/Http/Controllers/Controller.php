<?php

namespace App\Http\Controllers;

use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $repository = [];
    protected $createRules = [];
    protected $editRules = [];
    protected $createData = [];
    protected $editData = [];

    public function __construct(Repository $repository)
    {
        $this->repository['self'] = $repository;
    }

    public function setCreateRules($createRules)
    {
        $this->createRules = $createRules;
    }

    public function setCreateData($createData)
    {
        $this->createData = $createData;
    }

    public function setEditRules($editRules)
    {
        $this->editRules = $editRules;
    }

    public function setEditData($editData)
    {
        $this->editData = $editData;
    }

    protected function success($data = [])
    {
        return response()->json([
           'code' => 200,
           'msg' => '操作成功',
           'data' => $data
        ]);
    }

    protected function fail($msg = '操作失败', $code = 500)
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg
        ]);
    }

    /*
     * 获取列表
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $model = $this->repository['self']->search($search);
        $page = getParam($request,'page',Model::PAGE);
        $size = getParam($request,'size',Model::SIZE);
        $count = $this->repository['self']->all()->count();
        $model = $this->repository['self']->page($model,$page,$size);
        return $this->success(['count'=>$count,'reslut'=>$model]);

    }

    /*
     * 根据id获取信息
     */
    public function show(Request $request)
    {
        $validateRules = [
            'id' => 'required|integer'
        ];
        $this->validate($request, $validateRules);
        $id = $request->get('id');
        $model = $this->repository['self']->getById($id);
        return $this->success($model);
    }

    /*
     * 删除
     */
    public function delete(Request $request)
    {
        $validateRules = [
            'id' => 'required|integer'
        ];
        $this->validate($request, $validateRules);
        $id = $request->get('id');
        $flag = $this->repository['self']->delete($id);
        if ($flag){
            return $this->success();
        }
        return $this->fail();
    }

    /*
     * 添加
     */
    public function create(Request $request)
    {
        $this->validate($request, $this->createRules);
        $flag = $this->repository['self']->insert($this->createData);
        if($flag){
            return $this->success();
        }
        return $this->fail();
    }

    /*
     * 修改
     */
    public function edit(Request $request)
    {
        $this->validate($request, $this->editRules);
        $id = $request->get('id');
        $flag = $this->repository['self']->update($id,$this->editData);
        if($flag){
            return $this->success();
        }
        return $this->fail();
    }

}
