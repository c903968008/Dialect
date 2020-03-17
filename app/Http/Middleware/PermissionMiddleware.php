<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 8:28
 */

namespace App\Http\Middleware;

use App\Permission;
use App\RoleAdmin;
use App\RolePermission;
use Closure;

class PermissionMiddleware
{

    public function handle($request, Closure $next)
    {
        $admin_id = $request->get('sub');
        $role_ids = RoleAdmin::where('admin_id',$admin_id)->pluck('role_id');
        $permission_ids = RolePermission::whereIn('role_id',$role_ids)->pluck('permission_id');
        $permission = Permission::whereIn('id',$permission_ids)->pluck('path');
        foreach ($permission as $value){
            if (strpos($value,"\n") !== false){ //包含\n
                $array = explode("\n",$value);
                foreach ($array as $arr){
                    if (strpos($arr,"/*") !== false){
                        $arr = rtrim($arr,"/*");
                    }
                    if (strpos($request->url(),$arr) !== false){
                        return $next($request);
                    }
                }
            } else {
                if (strpos($value,"/*") !== false){
                    $value = rtrim($value,"/*");
                }
                if (strpos($request->url(),$value) !== false){
                    return $next($request);
                }
            }
        }
        return response('没有权限', 400);
    }

}