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
        if(count(Admin::create($data)) > 0){        //create方法返回一个Admin对象
            return true;
        }
        return false;
    }

    public function update($id,$data)
    {
        $admin = self::getById($id);
        return $admin->update($data);  //返回值true/false
    }

    public function delete($id)
    {

    }

}