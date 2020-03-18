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
        if (isset($search['pre_translation'])) $feedback = $feedback->whereHas('question', function ($query) use ($search) {
            $query->whereHas('dialect', function ($q) use ($search){
                $q->where('translation', 'like', '%' . $search['pre_translation'] . '%');
            });
        });
        if (isset($search['status']) && $search['status'] != "") $feedback = $feedback->where('status',$search['status']);
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

    public function all(bool $bool = false, array $search = ['is' => false, 'model' => null])
    {
        //搜索
        if ($search['is']){
            if ($bool && !empty($this->with)){
                return $search['model']->whereHas('question',function ($query){
                    $query->has('dialect');
                })->with(['question' => function($query){
                    $query->with('dialect');
                }])->with('user')->get();
            }
            return $search['model']->get();
        } else {
            if ($bool && !empty($this->with)){
                return $search['model']->whereHas('question',function ($query){
                    $query->has('dialect');
                })->with(['question' => function($query){
                    $query->with('dialect');
                }])->with('user')->get();
            }
            return $this->model::get();
        }
    }

}