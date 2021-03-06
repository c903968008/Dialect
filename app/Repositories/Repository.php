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
     * @param bool $bool
     * @param array $search 搜索信息
     * @return mixed
     */
    public function all(bool $bool = false, array $search = ['is' => false, 'model' => null])
    {
        //搜索
        if ($search['is']){
            if ($bool && !empty($this->with)){
                if (is_array($this->with)){
                    foreach ($this->with as $key => $value){
                        $model = $search['model']->has($value)->with($value);
                    }
                    return $model->get();
                } else{
                    return $search['model']->has($this->with)->with($this->with)->get();
                }
            }
            return $search['model']->get();
        } else {
            if ($bool && !empty($this->with)){
                if (is_array($this->with)){
                    foreach ($this->with as $key => $value){
                        if ($key == 0){
                            $model = $this->model::has($value)->with($value);
                        }
                        $model = $model->has($value)->with($value);
                    }
                    return $model->get();
                } else{
                    return $this->model::has($this->with)->with($this->with)->get();
                }
            }
            return $this->model::get();
        }
    }

    /**
     * 分页操作
     *
     * @param Model $model
     * @param int $page 当前页
     * @param int $size 一页所显示的数据条数
     * @return Model
     */
    public function page($model, $page, $size)
    {
        return $model->forPage($page,$size);
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
            if (is_array($this->with)){
                foreach ($this->with as $key => $value){
                    if ($key == 0){
                        $model = $this->model::has($value)->with($value);
                    }
                    $model = $model->has($value)->with($value);
                }
                return $model->find($id);
            } else{
                return $this->model::has($this->with)->with($this->with)->find($id);
            }
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
        return $this->model::create($data);     //create方法返回一个Model对象
    }

    /**
     * 更新数据（可批量修改）
     *
     * @param int $id
     * @param array $data
     * @param array $orther
     * @return bool
     */
    public function update($id,$data,$orther=[])
    {
        return $this->model::where('id', $id)->update($data);  //返回值true/false
    }

    /**
     * 计数
     *
     * @return mixed
     */
    public function count()
    {
        return $this->model::count();
    }


}
