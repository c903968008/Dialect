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
            'password' => Hash::make($request->get('password'))
        ];
        $flag = $this->adminRepository->create($data);
        if($flag){
            return $this->success();
        }
        return $this->fail();
    }

}