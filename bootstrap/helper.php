<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 13:37
 */

//获取参数
function getParam($request,$name,$param=null){
    if (strlen($request->get($name)) == 0){
        return $param;
    }else{
        return $request->get($name);
    }
}

//读取config.json文件
function getConfig(){
    $file = file_get_contents('../config.json');
    $data = json_decode($file, true);
    return $data;
}