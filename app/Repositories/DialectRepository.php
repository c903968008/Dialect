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
        if (isset($search['translation'])) $dialect = $dialect->where('translation','like', '%'.$search['translation'].'%');
        if (isset($search['district_id'])) $dialect = $dialect->where('district_id',$search['district_id']);
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
     * 判断方言的情况
     */
//    public function


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
}
