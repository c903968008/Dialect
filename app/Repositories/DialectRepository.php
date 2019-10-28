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

    public function search($search)
    {
        $dialect = new Dialect();
        if (isset($search['translation'])) $dialect = $dialect->where('translation','like', '%'.$search['translation'].'%');
        if (isset($search['district_id'])) $dialect = $dialect->where('district_id',$search['district_id']);
        return $dialect;
    }

    public function list($district_id)
    {
        return Dialect::where('district_id',$district_id)->get();
    }

    public function status($id,$status)
    {
        return Dialect::where('id',$id)->update(['status'=>$status]);
    }

}