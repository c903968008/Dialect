<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\Dialect;

class DialectRepository extends Repository
{
    protected $model = Dialect::class;
    protected $with = ['user','district'];

    public function search($search)
    {
        $dialect = new Dialect();
        $dialect = $dialect->where('status', $search['status']);
        if (isset($search['translation'])) $dialect = $dialect->where('translation', 'like', '%' . $search['translation'] . '%');
        if (isset($search['district'])) $dialect = $dialect->whereHas('district', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search['district'] . '%');
        });
        if (isset($search['user']) && !empty($search['user'])) {
            if ($search['user'] == '管理员') {
                $dialect = $dialect->where('user_id', 0);
            } else {
                $dialect = $dialect->whereHas('user', function ($query) use ($search) {
                    $query->where('nickName', 'like', '%' . $search['user'] . '%');
                });
            }
        }
        return $dialect;
    }

    public function getAll($dialect)
    {
        $dialect = $dialect->with('district');
        $dialect = $dialect->with('user');
        return $dialect;
    }

    /*
     * 获取审核通过的某地区的方言
     */
    public function list($district_id)
    {
        return Dialect::where([
            'district_id' => $district_id,
            'status' => Dialect::PASS,
        ])->get();
    }

    /*
     * 修改状态
     */
    public function status($id,$status)
    {
        return Dialect::where('id',$id)->update(['status'=>$status]);
    }


    /*
     * 根据translation查询
     */
    public function getByTranslation($translation)
    {
        return Dialect::where('translation', $translation)->first();
    }

    /*
     * 根据地区查询方言
     */
    public function getByDistrict($district_id, $model = null)
    {
        if (isset($model)){
            return $model->where(['district_id' => $district_id, 'status' => Dialect::PASS])->get(['id','audio','recognition','translation']);
        }
        return Dialect::where(['district_id' => $district_id, 'status' => Dialect::PASS])->get(['id','audio','recognition','translation']);
    }

    /*
     * 修改
     */
    public function update($id,$data,$orther=[])
    {
        $flag =  Dialect::where('id', $id)->update($data);
        if ($flag){
            return Dialect::find($id);
        }
        return false;
    }
}
