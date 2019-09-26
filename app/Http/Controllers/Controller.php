<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function success($msg = '操作成功' , $data = [])
    {
        return response()->json([
           'code' => 200,
           'msg' => $msg,
           'data' => $data
        ]);
    }

    public function fail($msg = '操作失败', $code = 500)
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg
        ]);
    }

    public function add()
    {
        $admin = new Admin();
        $admin->name = 'admin';
        $admin->password = Hash::make('123456');
        $admin->save();
    }
}
