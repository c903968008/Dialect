<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function success($data = [])
    {
        return response()->json([
           'code' => 200,
           'msg' => '操作成功',
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

}
