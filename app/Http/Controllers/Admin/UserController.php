<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(Request $request, UserRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }

    public function createBlock(Request $request)
    {
        $createRules = [
            'nickName' => 'required',
            'password' => 'required',
            'avatarUrl' => 'nullable',
        ];
        $this->setCreateRules($createRules);

        $createData = [
            'nickName' => $request->get('name'),
            'password' => Hash::make($request->get('password')),
        ];
        $avatar = $this->upload($request);
        if (!empty($avatar)){
            $createData['avatar'] = $avatar;
        }
        $this->setCreateData($createData);
    }

    public function editBlock(Request $request)
    {
        $editRules = [
            'id' => 'required',
            'nickName' => 'required',
            'password' => 'nullable',
            'avatarUrl' => 'nullable',
        ];
        $this->setCreateRules($editRules);

        $editData = [
            'nickName' => $request->get('name'),
        ];
        $password = $request->get('password');
        if (isset($password)) {
            $editData['password'] = Hash::make($password);
        }
        $avatar = $this->upload($request);
        if (!empty($avatar)){
            $editData['avatarUrl'] = $avatar;
        }
        $this->setEditData($editData);
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
                $path = $file -> move('avatars/user',$newName); //把缓存文件移动到制定文件夹
                return $newName;
            }
            return false;
        }
        return false;
    }
}
