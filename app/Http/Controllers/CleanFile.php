<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/12/13
 * Time: 9:46
 */

namespace App\Http\Controllers;


use App\Activity;
use App\Admin;
use App\Dialect;
use App\Question;
use Illuminate\Support\Facades\Storage;

class CleanFile
{
    /*
     * 清理public下的文件
     */
    public function clean()
    {
        //activity
        $activities = Activity::pluck('image')->toArray();
        $activity_name = $this->getFileName($_SERVER['DOCUMENT_ROOT'] . "/activity");
        foreach ($activities as $key1 => $value1){
            foreach ($activity_name as $key2 => $value2){
                if ($value1 == $value2){
                    unset($activity_name[$key2]);
                    break;
                }
            }
        }
        $disk = Storage::disk('uploadActivity');
        $disk->delete($activity_name);

        //avatars
        $admin = Admin::pluck('avatar')->toArray();
        $admin_name = $this->getFileName($_SERVER['DOCUMENT_ROOT'] . "/avatars");
        foreach ($admin as $key1 => $value1){
            foreach ($admin_name as $key2 => $value2){
                if ($value1 == $value2){
                    unset($admin_name[$key2]);
                    break;
                }
            }
        }
        $disk = Storage::disk('uploadAvatar');
        $disk->delete($admin_name);

        //dialect
        $dialects = Dialect::pluck('audio')->toArray();
        $questions = Question::pluck('audio')->toArray();
        $audios = array_unique(array_merge($dialects,$questions));
        $dialect_name = $this->getFileName($_SERVER['DOCUMENT_ROOT'] . "/dialect");
        foreach ($audios as $key1 => $value1){
            foreach ($dialect_name as $key2 => $value2){
                if ($value1 == $value2){
                    unset($dialect_name[$key2]);
                    break;
                }
            }
        }
        $disk = Storage::disk('uploadDialect');
        $disk->delete($dialect_name);
    }

    /*
     * 获取文件夹下的所有文件名称
     */
    public function getFileName($path)
    {
        $arr = array();
        $data = scandir($path);
        foreach ($data as $value){
            if($value != '.' && $value != '..'){
                $arr[] = $value;
            }
        }
        return $arr;
    }
}
