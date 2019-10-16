<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\Admin;

class AdminRepository extends Repository
{
    protected $model = Admin::class;

    public function search($search)
    {
        $admin = new Admin();
        if (isset($search['name'])) $admin = $admin->where('name','like', '%'.$search['name'].'%');
        return $admin;
    }

}