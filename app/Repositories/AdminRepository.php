<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\Admin;

class AdminRepository
{

    public function all()
    {
        return Admin::all();
    }

    public function search($search)
    {
        $admin = new Admin();
        if (isset($search['name'])) $admin = $admin->where('name','like', '%'.$search['name'].'%');
        return $admin;
    }

    public function page($admin, $page, $size)
    {
        return $admin->forPage($page,$size)->get();
    }

    public function getById($id)
    {
        return Admin::findOrFail($id);
    }

    public function create($data)
    {
        if(count(Admin::create($data)) > 0){
            return true;
        }
        return false;
    }

}