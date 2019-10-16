<?php

namespace App\Http\Controllers;

use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
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
        $model = $this->repository->search($search);
        $page = getParam($request,'page',Model::PAGE);
        $size = getParam($request,'size',Model::SIZE);
        $count = $this->repository->all()->count();
        $model = $this->repository->page($model,$page,$size);
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
        $model = $this->repository->getById($id);
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
        $flag = $this->repository->delete($id);
        if ($flag){
            return $this->success();
        }
        return $this->fail();
    }

}
