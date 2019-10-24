<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\Feedback;

class FeedbackRepository extends Repository
{
    protected $model = Feedback::class;

    public function search($search)
    {
        $feedback = new Feedback();
        if (isset($search['content'])) $feedback = $feedback->where('content','like', '%'.$search['content'].'%');
        if (isset($search['translation'])) $feedback = $feedback->where('translation','like', '%'.$search['translation'].'%');
        if (isset($search['checked'])) $feedback = $feedback->where('checked',$search['checked']);
        if (isset($search['accepted'])) $feedback = $feedback->where('accepted',$search['accepted']);
        return $feedback;
    }
}