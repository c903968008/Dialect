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