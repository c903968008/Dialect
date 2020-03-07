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
    protected $with = ['user','question'];

    public function search($search)
    {
        $feedback = new Feedback();
        if (isset($search['content'])) $feedback = $feedback->where('content','like', '%'.$search['content'].'%');
        if (isset($search['user'])) $feedback = $feedback->whereHas('user', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search['user'] . '%');
        });
        if (isset($search['pre_translation'])) $feedback = $feedback->whereHas('dialect', function ($query) use ($search) {
            $query->where('translation', 'like', '%' . $search['pre_translation'] . '%');
        });
        if (isset($search['checked'])) $feedback = $feedback->where('checked',$search['checked']);
        if (isset($search['accepted'])) $feedback = $feedback->where('accepted',$search['accepted']);
        return $feedback;
    }

    /*
     * 根据question_id,status查询列表
     */
    public function getByQueStatus($question_ids,$status)
    {
        return Feedback::whereIn('question_id',$question_ids)->where('status',$status)
            ->with('user')->with('question')->get();
    }

}