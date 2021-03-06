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
    protected $with = ['user','dialect','district'];

    public function search($search)
    {
        $question = new Question();
        if (isset($search['difficulty'])) $question = $question->where('difficulty',$search['difficulty']);
        if (isset($search['district'])) $question = $question->whereHas('district', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search['district'] . '%');
        });
        if (isset($search['dialect'])) $question = $question->whereHas('dialect', function ($query) use ($search) {
            $query->where('translation', 'like', '%' . $search['dialect'] . '%');
        });
        if (isset($search['user']) && !empty($search['user'])) {
            if (strpos('管理员',$search['user']) !== false) {
                $question = $question->where('user_id', 0);
            } else {
                $question = $question->whereHas('user', function ($query) use ($search) {
                    $query->where('nickName', 'like', '%' . $search['user'] . '%');
                });
            }
        }
        return $question;
    }

    public function getAll($dialect)
    {
        $dialect = $dialect->has('dialect')->with('dialect');
        $dialect = $dialect->with('user');
        $dialect = $dialect->has('district')->with('district');
        return $dialect->get();
    }

    public function getById($id, bool $bool = false)
    {
        $question = Question::with('user');
        $question = $question->with('dialect');
        $question = $question->with('district');
        return $question->find($id);
    }

    /*
     * 根据用户id查询题目数
     */
    public function countByUser($user_id)
    {
        return Question::where('user_id',$user_id)->count();
    }

    /*
     * 根据地区id查询
     */
    public function getByDistrict($district_id,$num)
    {
        return Question::has('dialect')->with('dialect')
                ->has('district')->with('district')
                ->where('district_id',$district_id)
                ->orderByRaw('RAND()')->take($num)->get();
    }

    /*
     * 根据多个方言id查询题目列表
     */
    public function getByDialects($dialect_ids)
    {
        return Question::whereIn('dialect_id',$dialect_ids)->get();
    }

    /*
     * 判断题目是否答错
     */
    public function isWrong($id,$answer)
    {
        $question = Question::findOrFail($id);
        $wrong = explode(',',$question->wrong);
        if(in_array($answer,$wrong)){
            return true; //答错
        }
        return false; //未答错，需要继续从方言表中判断
    }

    /*
     * 根据用户id查询题目列表
     */
    public function getByUser($user_id)
    {
        return Question::where('user_id',$user_id)->has('dialect')->with('dialect')
                        ->has('district')->with('district')->orderBy('id','DESC')->get();
    }

    /*
     * 根据用户id查询题目ids
     */
    public function getIdsByUser($user_id)
    {
        return Question::where('user_id',$user_id)->pluck('id');
    }

    /*
     * 根据用户id查询题目ids,非管理员
     */

    /*
     * 点赞及取消点赞
     */
    public function updateLike($id,$status)
    {
        $question = Question::findOrFail($id);
        if ($status){
            $question->like++;
        } else {
            $question->like--;
        }
        return $question->save();
    }

    /*
     * 修改
     */
    public function update($id,$data,$orther=[])
    {
        $flag =  Question::where('id', $id)->update($data);
        if ($flag){
            return Question::find($id);
        }
        return false;
    }

    /*
     * 修改多个right/total
     */
    public function updateRightTotal($right_ids,$total_ids)
    {
        $flag1 =  Question::whereIn('id', $right_ids)->increment('answer_right');
        $flag2 =  Question::whereIn('id', $total_ids)->increment('answer_total');
        return true;
    }

    /*
     * 获取所有district_ids
     */
    public function getDistrictIds()
    {
        return array_unique(Question::pluck('district_id')->toArray());
    }
}
