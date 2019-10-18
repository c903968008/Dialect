<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/10/18
 * Time: 13:09
 */

namespace App\Http\Controllers;


class ResponseWrapper
{
    /**
     * 操作成功返回信息
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = [])
    {
        return response()->json([
            'code' => 200,
            'msg' => '操作成功',
            'data' => $data
        ]);
    }

    /**
     * 操作失败返回信息
     *
     * @param string $msg
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function fail($msg = '操作失败', $code = 500)
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg
        ]);
    }

}