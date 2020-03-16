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
use App\Repositories\ActivityRepository;
use App\Repositories\DistrictRepository;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function __construct(Request $request, ActivityRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }

    public function createBlock(Request $request)
    {
        $createRules = [
            'image' => 'nullable',
            'title' => 'required|string',
            'content' => 'required',
            'time' => 'required',
        ];
        $this->setCreateRules($createRules);

        $createData = [
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'time' => $request->get('time'),
        ];
        $this->setCreateData($createData);
    }

    public function editBlock(Request $request)
    {
        $editRules = [
            'id' => 'required',
            'image' => 'nullable',
            'title' => 'required|string',
            'content' => 'required',
            'time' => 'required',
        ];
        $this->setEditRules($editRules);

        $editData = [
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'time' => $request->get('time'),
        ];
        $this->setEditData($editData);
    }

    public function create(Request $request)
    {
        $this->validate($request, $this->createRules);
        $image = $this->upload($request);
        if (!empty($image)){
            $this->createData['image'] = $image;
        }
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
        $image = $this->upload($request);
        if (!empty($image)){
            $this->editData['image'] = $image;
        }
        $flag = $this->repository['self']->update($id,$this->editData);
        if($flag){
            return ResponseWrapper::success($flag);
        }
        return ResponseWrapper::fail();
    }

    /*
     * 上传头像
     */
    public function upload(Request $request)
    {
        if(!empty($request->file())){
            $file = $request->file('image');
            if($file->isValid()) {
                $clientName = $file -> getClientOriginalName(); //客户端文件名称..
                $tmpName = $file ->getFileName(); //缓存在tmp文件夹中的文件名例php8933.tmp 这种类型的.
                $realPath = $file -> getRealPath(); //这个表示的是缓存在tmp文件夹下的文件的绝对路径
                $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
                $mimeTye = $file -> getMimeType(); //也就是该资源的媒体类型
                $newName = md5(date('ymdhis').$clientName).".". $entension; //定义上传文件的新名称
                $path = $file -> move('activity',$newName); //把缓存文件移动到制定文件夹
                return $newName;
            }
            return false;
        }
        return false;
    }
}
