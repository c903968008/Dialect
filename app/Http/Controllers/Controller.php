<?php

namespace App\Http\Controllers;

use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * 控制器用到的Repository
     * 下标为self的元素为本身相关的类
     *
     * @var array
     */
    protected $repository = [];

    /**
     * 添加时的规则
     *
     * @var array
     */
    protected $createRules = [];

    /**
     * 修改时的规则
     *
     * @var array
     */
    protected $editRules = [];

    /**
     * 所需添加的数据
     *
     * @var array
     */
    protected $createData = [];

    /**
     * 所需修改的数据
     *
     * @var array
     */
    protected $editData = [];

    /**
     * 是否需要查询关联表的信息
     *
     * @var bool
     */
    protected $is_with;

    /**
     * Controller constructor.
     * @param bool $is_with
     * @param Request $request
     * @param Repository $repository
     */
    public function __construct(Request $request, Repository $repository, bool $is_with = true)
    {
        $this->repository['self'] = $repository;
        $this->createBlock($request);
        $this->editBlock($request);
        $this->setIsWith($is_with);
    }

    /**
     * 设置编辑时的数据$createRules和$createData
     *
     * @param array $editData
     */
    public function setIsWith($is_with)
    {
        $this->is_with = $is_with;
    }

    /**
     * 设置创建时的规则
     *
     * @param array $createRules
     */
    public function setCreateRules($createRules)
    {
        $this->createRules = $createRules;
    }

    /**
     * 设置创建时的数据
     *
     * @param array $createData
     */
    public function setCreateData($createData)
    {
        $this->createData = $createData;
    }

    /**
     * 设置编辑时的规则
     *
     * @param array $editRules
     */
    public function setEditRules($editRules)
    {
        $this->editRules = $editRules;
    }

    /**
     * 设置编辑时的数据$createRules和$createData
     *
     * @param array $editData
     */
    public function setEditData($editData)
    {
        $this->editData = $editData;
    }

    /**
     * 创建时用于设置
     *
     * @param Request $request
     */
    public function createBlock(Request $request){}
    public function editBlock(Request $request){}

    /**
     * 获取列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $model = $this->repository['self']->search($search);
        $page = getParam($request,'page',Model::PAGE);
        $size = getParam($request,'size',Model::SIZE);
        $count = $this->repository['self']->all($this->is_with)->count();
        if ($count == 0){
            return ResponseWrapper::fail('数据不存在');
        }
        $model = $this->repository['self']->page($model,$page,$size,$this->is_with);
        return ResponseWrapper::success(['count'=>$count,'reslut'=>$model]);
    }

    /**
     * 根据id获取信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show(Request $request)
    {
        $validateRules = [
            'id' => 'required|integer'
        ];
        $this->validate($request, $validateRules);
        $id = $request->get('id');
        $model = $this->repository['self']->getById($id,$this->is_with);
        if (count($model) == 0){
            return ResponseWrapper::fail('数据不存在');
        }
        return ResponseWrapper::success($model);
    }

    /**
     * 删除（可批量删除）
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
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
            return ResponseWrapper::success();
        }
        return ResponseWrapper::fail();
    }

    /**
     * 添加
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request, $this->createRules);
        $flag = $this->repository['self']->insert($this->createData);
        if($flag){
            return ResponseWrapper::success();
        }
        return ResponseWrapper::fail();
    }

    /**
     * 修改
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit(Request $request)
    {
        $this->validate($request, $this->editRules);
        $id = $request->get('id');
        $flag = $this->repository['self']->update($id,$this->editData);
        if($flag){
            return ResponseWrapper::success();
        }
        return ResponseWrapper::fail();
    }
}
