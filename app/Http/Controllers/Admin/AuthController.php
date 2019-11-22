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
use App\Http\Controllers\ResponseWrapper;
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
            'username' => 'required',
            'password' => 'required'
        ];
        $this->validate($request, $validateRules);

        $admin = Admin::where('name', $request->get('username'))->first();
        if(!empty($admin) && Hash::check($request->get('password'),$admin->password) == false){
            return ResponseWrapper::fail('账号或密码错误');
        }

        $token = Auth::login($admin);
        if(!$token){
            return ResponseWrapper::fail('系统错误，无法生成token');
        }
        return ResponseWrapper::success(['token' => $token]);
    }

    public function logout()
    {
        return ResponseWrapper::success();
    }

}