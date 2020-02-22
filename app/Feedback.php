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
        'user_id', 'question_id','dialect_id', 'content', 'translation', 'checked', 'accepted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dialect()
    {
        return $this->belongsTo(Dialect::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
