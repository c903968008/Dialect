<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;



use App\Question;

class QuestionRepository extends Repository
{
    protected $model = Question::class;

    public function search($search)
    {
        $question = new Question();
        if (isset($search['difficulty'])) $question = $question->where('difficulty',$search['difficulty']);
        if (isset($search['district_id'])) $question = $question->where('district_id',$search['district_id']);
        return $question;
    }
}