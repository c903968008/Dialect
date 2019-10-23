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
    /**
     * 类本身所关系的模型
     *
     * @var Model
     */
    protected $model;

    /**
     * 关联
     *
     * @var string
     */
    protected $with = '';

    /**
     * 搜索
     *
     * @param $search
     */
    public function search($search){}

    /**
     * 获取所有数据
     *
     * @param bool $bool 用于判断是否需要获取关联表的相关内容
     * @return Model
     */
    public function all(bool $bool = false)
    {
        if ($bool && !empty($this->with)){
            return $this->model::has($this->with)->with($this->with)->get();
        }
        return $this->model::all();
    }

    /**
     * 分页操作
     *
     * @param Model $model
     * @param int $page 当前页
     * @param int $size 一页所显示的数据条数
     * @param bool $bool 用于判断是否需要获取关联表的相关内容
     * @return Model
     */
    public function page($model, $page, $size, bool $bool = false)
    {
        if ($bool && !empty($this->with)){
            $model = $model->has($this->with)->with($this->with);
        }
        return $model->forPage($page,$size)->get();
    }

    /**
     * 根据id获取表格信息
     *
     * @param int $id
     * @param bool $bool 用于判断是否需要获取关联表的相关内容
     * @return Model
     */
    public function getById($id, bool $bool = false)
    {
        if ($bool && !empty($this->with)){
            return $this->model::has($this->with)->with($this->with)->find($id);
        }
        return $this->model::find($id);
    }

    /**
     * 删除一条或多条数据
     *
     * @param int/array $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->model::destroy($id);
    }

    /**
     * 根据条件删除相关数据
     *
     * @param array $condition
     * @return bool
     */
    public function deleteByCondition($condition)
    {
        return $this->model::where($condition)->delete();
    }

    /**
     * 插入数据
     *
     * @param array $data
     * @return bool
     */
    public function insert($data)
    {
        if(count($this->model::create($data)) > 0){     //create方法返回一个Model对象
            return true;
        }
        return false;
    }

    /**
     * 更新数据（可批量修改）
     *
     * @param int/array $id
     * @param array $data
     * @param array $orther
     * @return bool
     */
    public function update($id,$data,$orther=[])
    {
        $id = is_array($id) ? $id : [$id];
        return $this->model::whereIn('id', $id)->update($data);  //返回值true/false
    }

}