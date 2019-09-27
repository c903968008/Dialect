<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 12:37
 */

namespace App\Http\Controllers\Admin;


use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{

    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    /*
     * 登录
     */
    public function login(Request $request)
    {
        $validateRules = [
            'name' => 'required',
            'password' => 'required'
        ];
        $this->validate($request, $validateRules);

        $admin = Admin::where('name', $request->get('name'))->first();

        if(!empty($user) && Hash::check($request->get('password'),$user->password)){
            return $this->fail('账号或密码错误');
        }

        $token = Auth::login($admin);
        if(!$token){
            return $this->fail('系统错误，无法生成token');
        }
        return $this->success('登录成功',['token' => $token]);
    }

}