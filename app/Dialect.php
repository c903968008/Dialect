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
    protected $fillable = [
        'user_id', 'district_id', 'audio', 'recognition', 'translation'
    ];
}