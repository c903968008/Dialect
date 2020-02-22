<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 10:59
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'user_id', 'dialect_id', 'district_id', 'wrong', 'answer_right', 'answer_total', 'difficulty', 'audio', 'like'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dialect()
    {
        return $this->belongsTo(Dialect::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
