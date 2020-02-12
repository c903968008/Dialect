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
        if (isset($search['role_id'])) $admin = $admin->whereHas('roles', function ($query) use ($search){
            $query->where('role_id', $search['role_id']);
        });
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
        $role_ids = explode(',',$data['role_ids']);
        $admin = Admin::create($data);
        if (count($data)){
            $admin->roles()->attach($role_ids);
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
            $role_ids = explode(',',$other);
            $admin->roles()->sync($role_ids);
            return $admin->with('roles')->find($id);
        }
        return false;
    }

}