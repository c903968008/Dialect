<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\Activity;

class ActivityRepository extends Repository
{
    protected $model = Activity::class;

    public function search($search)
    {
        $activity= new Activity();
        if (isset($search['title'])) $activity = $activity->where('p_id', $search['p_id']);
        return $activity;
    }

    public function userList()
    {
//        dd(date("Y-m-d h:i:s"));
        return Activity::where('time', '<=' ,date("Y-m-d h:i:s"))->orderBy('time','DESC')->get();
    }
}
