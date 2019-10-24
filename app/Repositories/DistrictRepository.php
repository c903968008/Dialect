<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\District;

class DistrictRepository extends Repository
{
    protected $model = District::class;

    public function search($search)
    {
        $district= new District();
        if (isset($search['name'])) $district = $district->where('name','like', '%'.$search['name'].'%');
        return $district;
    }

}