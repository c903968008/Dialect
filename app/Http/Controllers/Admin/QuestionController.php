<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseWrapper;
use App\Repositories\DialectRepository;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct(Request $request, QuestionRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
        $this->repository['dialect'] = DialectRepository::class;
    }

    public function createBlock(Request $request)
    {
        $createRules = [
            'dialect_id' => 'required',
            'wrong' => 'required',
            'difficulty' => 'required'
        ];
        $this->setCreateRules($createRules);

        $createData = [
            'dialect_id' => $request->get('dialect_id'),
            'wrong' => $request->get('wrong'),
            'difficulty' => $request->get('difficulty'),
        ];
        $this->setCreateData($createData);
    }

    public function editBlock(Request $request)
    {
        $editRules = [
            'id' => 'required',
            'dialect_id' => 'required',
            'wrong' => 'required',
            'difficulty' => 'required'
        ];
        $this->setEditRules($editRules);

        $editData = [
            'dialect_id' => $request->get('dialect_id'),
            'wrong' => $request->get('wrong'),
            'difficulty' => $request->get('difficulty'),
        ];
        $this->setEditData($editData);
    }

    public function create(Request $request)
    {
        $this->validate($request, $this->createRules);



        $flag = $this->repository['self']->insert($this->createData);
        if($flag){
            return ResponseWrapper::success();
        }
        return ResponseWrapper::fail();
    }
}