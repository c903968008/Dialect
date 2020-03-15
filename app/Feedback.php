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

    const NO_CHECKED = 0;   //未查看
    const NO_ACCEPTED = 1;  //已查看未接受
    const ACCEPTED = 2;     //已查看已接受

    protected $fillable = [
        'user_id', 'question_id', 'content', 'translation', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
//
//    public function dialect()
//    {
//        return $this->belongsTo(Dialect::class);
//    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
