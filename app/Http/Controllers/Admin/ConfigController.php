<?php
/**
 * Created by PhpStorm.
 * User: 新用户
 * Date: 2020/3/17
 * Time: 9:42
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseWrapper;
use Illuminate\Http\Request;

class ConfigController extends Controller
{

    public function show(Request $request)
    {
        return ResponseWrapper::success(getConfig());
    }

    public function edit(Request $request)
    {
        $validateRules = [
            'answer_count' => 'required|integer',
            'dialect_audit_total' => 'required|integer',
            'dialect_audit_accuracy' => 'required|integer',
            'feedback_count' => 'required|integer',
        ];
        $this->validate($request, $validateRules);

        $data = [
            'answer_count' => $request->get('answer_count'),
            'dialect_audit_total' => $request->get('dialect_audit_total'),
            'dialect_audit_accuracy' => $request->get('dialect_audit_accuracy'),
            'feedback_count' => $request->get('feedback_count'),
        ];
        $flag = editConfig($data);
        if ($flag == false){
            return ResponseWrapper::fail();
        }
        return ResponseWrapper::success();
    }

}