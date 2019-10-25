<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers\Admin;


use App\Dialect;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseWrapper;
use App\Repositories\DialectRepository;
use Illuminate\Http\Request;

class DialectController extends Controller
{
    public function __construct(Request $request, DialectRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }


    public function createBlock(Request $request)
    {
        $createRules = [
            'district_id' => 'required',
//            'audio' => 'required',
            'translation' => 'required',
        ];
        $this->setCreateRules($createRules);

        $createData = [
            'user_id' => Dialect::ADMIN,
            'district_id' => $request->get('district_id'),
            'translation' => $request->get('translation'),
        ];
        $audio = $this->upload($request);
        if (!empty($audio)){
            $createData['audio'] = $audio;
        }
        $this->setCreateData($createData);
    }

    public function editBlock(Request $request)
    {
        $editRules = [
            'id' => 'required',
            'district_id' => 'required',
            'audio' => 'nullable',
            'translation' => 'required',
        ];
        $this->setEditRules($editRules);

        $editData = [
            'user_id' =>  Dialect::ADMIN,
            'district_id' => $request->get('district_id'),
            'translation' => $request->get('translation'),
        ];
        $audio = $this->upload($request);
        if (!empty($audio)){
            $editData['audio'] = $audio;
        }
        $this->setEditData($editData);
    }

    public function list(Request $request)
    {
        $validateRules = [
            'district_id' => 'required|integer'
        ];
        $this->validate($request, $validateRules);

        $district_id = $request->get('district_id');
        $dialect = $this->repository['self']->list($district_id);
        return ResponseWrapper::success($dialect);
    }

    /*
     * 上传方言音频文件
     */
    public function upload(Request $request)
    {
        if(!empty($request->file())){

            $file = $request->file('audio');
            if($file -> isValid()) {
                $clientName = $file -> getClientOriginalName(); //客户端文件名称..
                $tmpName = $file ->getFileName(); //缓存在tmp文件夹中的文件名例php8933.tmp 这种类型的.
                $realPath = $file -> getRealPath(); //这个表示的是缓存在tmp文件夹下的文件的绝对路径
                $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
                $mimeTye = $file -> getMimeType(); //也就是该资源的媒体类型
                $newName = $newName = md5(date('ymdhis').$clientName).".". $entension; //定义上传文件的新名称
                $path = $file -> move('dialect',$newName); //把缓存文件移动到制定文件夹
                return $newName;
            }
            return false;
        }
        return false;
    }
}