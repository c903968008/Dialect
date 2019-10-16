<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\Role;

class RoleRepository extends Repository
{
    protected $model = Role::class;

    public function search($search)
    {
        $role = new Role();
        if (isset($search['name'])) $role = $role->where('name','like', '%'.$search['name'].'%');
        return $role;
    }
}