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
    protected $with = 'permissions';

    public function search($search)
    {
        $role = new Role();
        if (isset($search['name'])) $role = $role->where('name','like', '%'.$search['name'].'%');
        if (isset($search['permission_id'])) $role = $role->whereHas('permissions', function ($query) use ($search){
            $query->where('permission_id', $search['permission_id']);
        });
        return $role;
    }

    public function delete($id)
    {
        $role = self::getById($id);
        if (!empty($role->permissions)){
            $role->permissions()->detach();
        }
        return $role->delete();
    }

    public function insert($data)
    {
        $role = Role::create($data);
        if (count($data)){
            $role->permissions()->attach($data['permission_ids']);
            return true;
        }
        return false;
    }

    public function update($id, $data, $other=[])
    {
        $role = Role::find($id);
        foreach ($data as $key => $value){
            $role->$key = $value;
        }
        if ($role->save()){
            $role->permissions()->sync($other);
            return $role->with('permissions')->find($id);
        }
        return false;
    }
}