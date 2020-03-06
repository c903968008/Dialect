<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\Certificate;

class CertificateRepository extends Repository
{
    protected $model = Certificate::class;
    protected $with = 'district';

    public function search($search)
    {
        $certificate = new Certificate();
        if (isset($search['name'])) $certificate = $certificate->where('name','like', '%'.$search['name'].'%');
        if (isset($search['rank'])) $certificate = $certificate->where('rank',$search['rank']);
        if (isset($search['district_id'])) $certificate = $certificate->where('district_id',$search['district_id']);
        return $certificate;
    }

    /*
     * 根据ids查询证书列表
     */
    public function getByUser($ids)
    {
        return Certificate::whereIn('id',$ids)->with('district')->orderBy('id','DESC')->get();
    }
}