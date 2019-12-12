<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 10:59
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Dialect extends Model
{

    const ADMIN = 0;

    const UNAUDITED = 0;    //未审核
    const NOPASS = 1;   //审核未通过
    const PASS = 2; //审核通过

    protected $fillable = [
        'user_id', 'district_id', 'audio', 'recognition', 'translation', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
