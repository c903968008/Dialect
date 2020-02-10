<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\District;
use Illuminate\Support\Facades\DB;

class DistrictRepository extends Repository
{
    protected $model = District::class;

    public function search($search)
    {
        $district= new District();
        if (isset($search['p_id'])) $district = $district->where('p_id', $search['p_id']);
        if (isset($search['name'])) $district = $district->where('name','like', '%'.$search['name'].'%');
        return $district;
    }

    /*
     * 根据name查询
     */
    public function getByName($name)
    {
        return District::where('name',$name)->first();
    }

    /*
     * 根据p_id查询
     */
    public function getByPid($p_id)
    {
        return District::where('p_id',$p_id)->get();
    }

    /*
     * 查询上级地区
     */
    public function getPrevious($p_id)
    {
        return District::find($p_id);
    }

}
