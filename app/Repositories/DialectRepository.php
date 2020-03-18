<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\Dialect;
use App\Feedback;
use App\Http\Controllers\ResponseWrapper;
use App\Question;

class DialectRepository extends Repository
{
    protected $model = Dialect::class;
    protected $with = ['user','district'];

    public function search($search)
    {
        $dialect = new Dialect();
        if (isset($search['status'])) $dialect = $dialect->where('status', $search['status']);
        if (isset($search['translation'])) $dialect = $dialect->where('translation', 'like', '%' . $search['translation'] . '%');
        if (isset($search['district'])) $dialect = $dialect->whereHas('district', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search['district'] . '%');
        });
        if (isset($search['user']) && !empty($search['user'])) {
            if ($search['user'] == '管理员') {
                $dialect = $dialect->where('user_id', 0);
            } else {
                $dialect = $dialect->whereHas('user', function ($query) use ($search) {
                    $query->where('nickName', 'like', '%' . $search['user'] . '%');
                });
            }
        }
        return $dialect;
    }

    public function getAll($dialect)
    {
        $dialect = $dialect->has('district')->with('district');
        $dialect = $dialect->with('user');
        return $dialect->get();
    }

    /*
     * 获取审核通过的某地区的方言
     */
    public function list($district_id)
    {
        return Dialect::where([
            'district_id' => $district_id,
            'status' => Dialect::PASS,
        ])->get();
    }

    /*
     * 修改状态
     */
    public function status($id,$status)
    {
        return Dialect::where('id',$id)->update(['status'=>$status]);
    }

    /*
     * 根据translation,district查询
     */
    public function getByTraDis($translation,$district_id)
    {
        return Dialect::where('translation', $translation)->where('district_id',$district_id)->first();
    }

    /*
     * 根据translation查询
     */
    public function getByTranslation($translation)
    {
        return Dialect::where('translation', $translation)->first();
    }

    /*
     * 根据audio,user_id查询
     */
    public function getByAudioUser($audio,$user_id)
    {
        return Dialect::where([
            'audio' => $audio,
            'user_id' => $user_id
        ])->first();
    }

    /*
     * 根据地区查询方言
     */
    public function getByDistrict($district_id, $model = null)
    {
        if (isset($model)){
            return $model->where(['district_id' => $district_id, 'status' => Dialect::PASS])->get(['id','audio','recognition','translation']);
        }
        return Dialect::where(['district_id' => $district_id, 'status' => Dialect::PASS])->get(['id','audio','recognition','translation']);
    }

    /*
     * 修改
     */
    public function update($id,$data,$orther=[])
    {
        $flag =  Dialect::where('id', $id)->update($data);
        if ($flag){
            return Dialect::find($id);
        }
        return false;
    }

    /*
     * 获取所有district_ids
     */
    public function getDistrictIds()
    {
        return array_unique(Dialect::pluck('district_id')->toArray());
    }

    /*
     * 自动审核
     */
    public function autoAudit()
    {
        //找dialect对应的question(dialect_id/district_id/audio相同)
            //存在
                //如果符合要求，则通过
                //如果不符合要求
                    //1.没有其他对应的question(dialect_id/district_id相同/audio不相同)，则不通过
                    //2.有其他对应的question，则dialect->audio = question->audio，并且通过  （如有多个，则选择第一个；等待下次自动审核，再更新）
            //不存在
                //1.没有其他对应的question(dialect_id/district_id相同/audio不相同)，则不通过
                //2.有其他对应的question，则dialect->audio = question->audio，并且通过

        $dialects = Dialect::where('user_id','!=',0)->get();
        foreach ($dialects as $dialect){
//            var_dump($dialect->toArray());
            $question1 = Question::where(['dialect_id'=>$dialect->id,'district_id'=>$dialect->district_id,'audio'=>$dialect->audio])->first();
//            dd(isset($question1));
            if (isset($question1)){      //存在
                $feedback_count = Feedback::where('question_id',$question1->id)->count();
                if ($this->audioConditions($question1,$feedback_count)){        //1符合要求，通过
                    $dialect->status = Dialect::PASS;
                    $dialect->save();
                } else {        //1不符合条件
                    $questions2 = Question::where(['dialect_id'=>$dialect->id,'district_id'=>$dialect->district_id])->get();
//                    dd(empty($questions2));
                    if (empty($questions2)){        //2不存在，不通过
                        $dialect->status = Dialect::NOPASS;
                        $dialect->save();
                    } else {    //2存在
                        foreach ($questions2 as $question2){
                            $feedback_count = Feedback::where('question_id',$question2->id)->count();
                            if ($this->audioConditions($question2,$feedback_count)){
                                $dialect->audio = $question2->audio;
                                $dialect->status = Dialect::PASS;
                                $dialect->save();
                            }
                        }
                    }
                }
            } else {        //不存在
                $questions2 = Question::where(['dialect_id'=>$dialect->id,'district_id'=>$dialect->district_id])->get();
                if (empty($questions2)){        //2不存在，不通过
                    $dialect->status = Dialect::NOPASS;
                    $dialect->save();
                } else {    //2存在
                    foreach ($questions2 as $question2){
                        $feedback_count = Feedback::where('question_id',$question2->id)->count();
                        if ($this->audioConditions($question2,$feedback_count)){
                            $dialect->audio = $question2->audio;
                            $dialect->status = Dialect::PASS;
                            $dialect->save();
                        }
                    }
                }
            }
        }
    }

    /*
     * 审核通过的条件
     */
    public function audioConditions($question, $feedback_count)
    {
        $config = getConfig();
        if ($question->answer_total == 0){
            $accuracy = 0;
        } else{
            $accuracy = ($question->answer_right / $question->answer_total) * 100;
        }
//        var_dump($question->answer_total >= $config['dialect_audit_total']);
//        var_dump($accuracy >= $config['dialect_audit_accuracy']);
//        var_dump($feedback_count < $config['feedback_count']);
        if ($question->answer_total >= $config['dialect_audit_total'] &&
            $accuracy >= $config['dialect_audit_accuracy'] &&
            $feedback_count < $config['feedback_count'])
        {
            return true;
        }
        return false;
    }
}
