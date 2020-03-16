<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 10:59
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'image', 'title', 'content', 'time'
    ];
}
