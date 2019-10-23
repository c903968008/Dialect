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
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(Request $request, RoleRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }

    public function createBlock(Request $request)
    {
        $createRules = [
            'name' => 'required',
            'permission_ids' => 'required'
        ];
        $this->setCreateRules($createRules);

        $data = [
            'name' => $request->get('name'),
            'permission_ids' => $request->get('permission_ids'),
        ];
        $this->setCreateData($data);
    }

    public function editBlock(Request $request)
    {
        $editRules = [
            'id' => 'required',
            'name' => 'required',
            'permission_ids' => 'required'
        ];
        $this->setCreateRules($editRules);

        $data = [
            'id' => $request->get('id'),
        ];
        $this->setEditData($data);
    }

    public function edit(Request $request)
    {
        $this->validate($request, $this->editRules);
        $id = $request->get('id');
        $flag = $this->repository['self']->update($id,$this->editData,$request->get('permission_ids'));
        if($flag){
            return ResponseWrapper::success();
        }
        return ResponseWrapper::fail();
    }

}