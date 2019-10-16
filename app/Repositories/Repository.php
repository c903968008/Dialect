<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/10/16
 * Time: 10:35
 */

namespace App\Repositories;



class Repository
{
    protected $model;

    public function all()
    {
        return $this->model::all();
    }

    public function page($model, $page, $size)
    {
        return $model->forPage($page,$size)->get();
    }

    public function getById($id)
    {
        return $this->model::findOrFail($id);
    }

    public function delete($id)
    {
        $model = self::getById($id);
        return $model->delete($id);         //返回值true/false
    }

    public function insert($data)
    {
        if(count($this->model::create($data)) > 0){        //create方法返回一个Model对象
            return true;
        }
        return false;
    }

    public function update($id,$data)
    {
        $model = $this->getById($id);
        return $model->update($data);  //返回值true/false
    }
}