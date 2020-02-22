<?php

namespace App\Http\Controllers;

use App\Repositories\Repository;
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
        $search = json_decode($request->get('search'),true);
        $model = $this->repository['self']->search($search);
        $model = $model->orderBy('id','DESC');
        $page = getParam($request,'page',1);
        $size = getParam($request,'size',20);
        $model = $this->repository['self']->all($this->is_with,['is' => true,'model' => $model]);
        $count = $model->count();
        if ($count == 0){
            return ResponseWrapper::success(['count'=>$count]);
        }
        $model = $this->repository['self']->page($model,$page,$size);
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
        $model = $this->repository['self']->getById($id);
        if (!isset($model)){
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
            'id' => 'required|integer',
        ];
        $this->validate($request, $validateRules);
        $id = $request->get('id');
        $flag = $this->repository['self']->delete($id);
        if (count($flag)){
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
        if(isset($flag)){
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

    /*
     * 上传方言音频文件
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
                $path = $file -> move('dialect',$newName); //把缓存文件移动到制定文件夹
                return $newName;
            }
            return false;
        }
        return false;
    }
}
