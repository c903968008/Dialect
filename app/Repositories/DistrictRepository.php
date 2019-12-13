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
     * 根据汉字拼音首字母排序
     */
    public function getByGbk()
    {
        return District::orderBy(DB::raw('convert(`name` using gbk)'))->get();
    }

}
