<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 12:37
 */

namespace App\Http\Controllers;


use App\Repositories\Repository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
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
        $code = $request->get('code');
        $rawData = $request->get('rawData');
        $signature = $request->get('signature');
        $iv = $request->get('iv');
        $encryptedData = $request->get('encryptedData');
        $appId = env('WEI_APP_ID');
        $secret = env('WEI_APP_SECRET');

        //调取微信小程序的登录接口
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appId.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        // 设置是否输出header
        curl_setopt($curl, CURLOPT_HEADER, false);
        // 设置是否输出结果
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 设置是否检查服务器端的证书
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // 使用curl_exec()将CURL返回的结果转换成正常数据并保存到一个变量
        $data = curl_exec($curl);
        // 使用 curl_close() 关闭CURL会话
        curl_close($curl);

        $data = json_decode($data);
        $session_key = $data->session_key;
        $openid = $data->openid;

        //数据签名校验
        $signature2 = sha1($rawData . $session_key);
        if ($signature != $signature2) {
            return ResponseWrapper::fail('数据签名验证失败');
        }

        $rawData = json_decode($rawData);
        $userData = [
            'nickName' => $rawData->nickName,
            'avatarUrl' => $rawData->avatarUrl,
            'openid' => $openid,
        ];
        $user = User::updateOrCreate(['openid' => $userData['openid']], ['nickName' => $userData['nickName'], 'avatarUrl' => $userData['avatarUrl'], 'openid' => $userData['openid']]);;
        if (count($user) == 0){
            return ResponseWrapper::fail('登录失败');
        }
        $user->accuracy = $user->right / $user->total * 100;

        $token = Auth::login($user);
        if(!$token){
            return ResponseWrapper::fail('系统错误，无法生成token');
        }
        return ResponseWrapper::success(['token' => $token, 'userInfo' => $user]);
    }

}
