<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function __construct(AdminRepository $adminRepository)
    {
        $this->repository = $adminRepository;
    }

    /*
     * 添加管理员
     */
    public function create(Request $request)
    {
        $validateRules = [
            'name' => 'required',
            'password' => 'required',
            'avatar' => 'nullable'
        ];
        $this->validate($request, $validateRules);

        $data = [
            'name' => $request->get('name'),
            'password' => Hash::make($request->get('password')),
        ];

        $avatar = $this->upload($request);
        if (!empty($avatar)){
            $data['avatar'] = $avatar;
        }
        $flag = $this->repository->create($data);
        if($flag){
            return $this->success();
        }
        return $this->fail();
    }

    /*
     * 修改管理员
     */
    public function edit(Request $request)
    {
        $validateRules = [
            'id' => 'required',
            'name' => 'required',
            'password' => 'nullable',
            'avatar' => 'nullable'
        ];
        $this->validate($request, $validateRules);

        $id = $request->get('id');
        $password = $request->get('password');

        $data = [
            'name' => $request->get('name'),
        ];

        if (isset($password)) {
            $data['password'] = Hash::make($request->get('password'));
        }

        $avatar = $this->upload($request);
        if (!empty($avatar)){
            $data['avatar'] = $avatar;
        }

        $flag = $this->repository->update($id,$data);
        if($flag){
            return $this->success();
        }
        return $this->fail();
    }


    /*
     * 上传头像
     */
    public function upload(Request $request)
    {
        if(!empty($request->file())){
            $file = $request->file('avatar');
            if($file -> isValid()) {
                $clientName = $file -> getClientOriginalName(); //客户端文件名称..
                $tmpName = $file ->getFileName(); //缓存在tmp文件夹中的文件名例php8933.tmp 这种类型的.
                $realPath = $file -> getRealPath(); //这个表示的是缓存在tmp文件夹下的文件的绝对路径
                $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
                $mimeTye = $file -> getMimeType(); //也就是该资源的媒体类型
                $newName = $newName = md5(date('ymdhis').$clientName).".". $entension; //定义上传文件的新名称
                $path = $file -> move('avatars',$newName); //把缓存文件移动到制定文件夹
                return $newName;
            }
            return false;
        }
        return false;
    }

}