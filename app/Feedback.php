<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 10:59
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'user_id', 'question_id', 'content', 'translation', 'checked', 'accepted'
    ];
}
