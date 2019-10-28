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

    const UNAUDITED = 0;
    const NOPASS = 1;
    const PASS = 2;

    protected $fillable = [
        'user_id', 'district_id', 'audio', 'recognition', 'translation'
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