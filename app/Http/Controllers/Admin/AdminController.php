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

    protected $admin;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->admin = $adminRepository;
    }

    /*
     * 获取管理员列表
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $admin = $this->admin->search($search);
        $page = getParam($request,'page',1);
        $size = getParam($request,'size',10);
        $count = $this->admin->all()->count();
        $admin = $this->admin->page($admin,$page,$size);
        return $this->success(['count'=>$count,'reslut'=>$admin]);

    }

    /*
     * 根据id显示管理员信息
     */
    public function show(Request $request)
    {
        $validateRules = [
            'id' => 'required|integer'
        ];
        $this->validate($request, $validateRules);

        $admin = $this->admin->getById($request->get('id'));
        return $this->success($admin);
    }

    /*
     *
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
        }

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
            'avatar' => $this->upload($request)
        ];
        $flag = $this->adminRepository->create($data);
        if($flag){
            return $this->success();
        }
        return $this->fail();
    }

}