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
    protected $with = 'roles';

    public function search($search)
    {
        $admin = new Admin();
        if (isset($search['name'])) $admin = $admin->where('name','like', '%'.$search['name'].'%');
        return $admin;
    }

    public function delete($id)
    {
        $admin = self::getById($id);
        if (!empty($admin->roles)){
            $admin->roles()->detach();
        }
        return $admin->delete();
    }

    public function insert($data)
    {
        $admin = Admin::create($data);
        if (count($data)){
            $admin->roles()->attach($data['role_ids']);
            return true;
        }
        return false;
    }

    public function update($id, $data, $other=[])
    {
        $admin = Admin::find($id);
        foreach ($data as $key => $value){
            $admin->$key = $value;
        }
        if ($admin->save()){
            $admin->roles()->sync($other);
            return true;
        }
        return false;
    }

}