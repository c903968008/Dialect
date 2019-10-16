<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\Permission;

class PermissionRepository extends Repository
{
    protected $model = Permission::class;

    public function search($search)
    {
        $permission = new Permission();
        if (isset($search['name'])) $permission = $permission->where('name','like', '%'.$search['name'].'%');
        return $permission;
    }
}