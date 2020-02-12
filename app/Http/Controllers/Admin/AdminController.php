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
use App\Repositories\AdminRepository;
use Cron\FieldFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    protected $name = '';

    public function __construct(Request $request, AdminRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }

    public function createBlock(Request $request)
    {
        $createRules = [
            'name' => 'required',
            'password' => 'required',
            'avatar' => 'nullable',
            'role_ids' => 'required'
        ];
        $this->setCreateRules($createRules);

        $createData = [
            'name' => $request->get('name'),
            'password' => Hash::make($request->get('password')),
            'role_ids' => $request->get('role_ids'),
        ];
        $avatar = $this->upload($request);
        if (!empty($avatar)){
            $createData['avatar'] = $avatar;
        }
        $this->setCreateData($createData);
    }

//    public function editBlock(Request $request)
//    {
//        $editRules = [
//            'id' => 'required',
//            'name' => 'required',
//            'password' => 'nullable',
//            'avatar' => 'nullable',
//            'role_ids' => 'required'
//        ];
//        $this->setEditRules($editRules);
//
//        $editData = [
//            'name' => $request->get('name'),
//        ];
//        $password = $request->get('password');
//        if (isset($password)) {
//            $editData['password'] = Hash::make($password);
//        }
//        $avatar = $this->upload($request);
////        var_dump($avatar);
//        if (!empty($avatar)){
//            $editData['avatar'] = $avatar;
//        }
//        $this->setEditData($editData);
//    }

//    public function edit(Request $request)
//    {
//        $this->validate($request, $this->editRules);
//        $id = $request->get('id');
//        dd($this->editData);
//        $flag = $this->repository['self']->update($id,$this->editData,$request->get('role_ids'));
//        if($flag){
//            return ResponseWrapper::success($flag);
//        }
//        return ResponseWrapper::fail();
//    }

    public function edit(Request $request)
    {
        $editRules = [
            'id' => 'required',
            'name' => 'required',
            'password' => 'nullable',
            'avatar' => 'nullable',
            'role_ids' => 'required'
        ];
        $this->setEditRules($editRules);

        $data = [
            'name' => $request->get('name'),
        ];
        $password = $request->get('password');
        if (isset($password)) {
            $data['password'] = Hash::make($password);
        }
        $id = $request->get('id');

        $avatar = $this->uploadEdit($request);
//        var_dump()
//        var_dump(empty($avatar));
        if (!empty($this->name)){
            $data['avatar'] = $this->name;
        }
//        dd($data);
        $flag = $this->repository['self']->update($id,$data,$request->get('role_ids'));
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
//        dd('1111');
        if(!empty($request->file())){
            $file = $request->file('avatar');
//            dd($file);
//            var_dump($file->isValid());
            if($file->isValid()) {
//                dd('ok');
                $clientName = $file -> getClientOriginalName(); //客户端文件名称..
                $tmpName = $file ->getFileName(); //缓存在tmp文件夹中的文件名例php8933.tmp 这种类型的.
                $realPath = $file -> getRealPath(); //这个表示的是缓存在tmp文件夹下的文件的绝对路径
                $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
                $mimeTye = $file -> getMimeType(); //也就是该资源的媒体类型
                $newName = md5(date('ymdhis').$clientName).".". $entension; //定义上传文件的新名称
                $path = $file -> move('avatars',$newName); //把缓存文件移动到制定文件夹
                return $newName;
            }
            return false;
        }
        return false;
    }

    /*
     * 上传头像
     */
    public function uploadEdit(Request $request)
    {
//        dd('2222');
        if(!empty($request->file())){
            $file = $request->file('avatar');
            if($file -> isValid()) {
                $clientName = $file -> getClientOriginalName(); //客户端文件名称..
                $tmpName = $file ->getFileName(); //缓存在tmp文件夹中的文件名例php8933.tmp 这种类型的.
                $realPath = $file -> getRealPath(); //这个表示的是缓存在tmp文件夹下的文件的绝对路径
                $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
                $mimeTye = $file -> getMimeType(); //也就是该资源的媒体类型
                $newName = md5(date('ymdhis').$clientName).".". $entension; //定义上传文件的新名称
//                $path = $file -> move('avatars',$newName); //把缓存文件移动到制定文件夹
                $file -> move('avatars',$newName); //把缓存文件移动到制定文件夹
                $this->name = $newName;
//                return $newName;
            }
//                return false;
        }
//        return $name;
    }

    public function getInfo(Request $request)
    {
        $id = $request->get('sub');
        $admin = $this->repository['self']->getById($id,true);
        if ($admin){
            $admin->avatar = 'http://127.0.0.1:8089/' . $admin->avatar;
            return ResponseWrapper::success($admin);
        }
        return ResponseWrapper::fail('无该用户信息');
    }

}