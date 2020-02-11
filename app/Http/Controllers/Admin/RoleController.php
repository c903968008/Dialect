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

        $createData = [
            'name' => $request->get('name'),
            'permission_ids' => $request->get('permission_ids'),
        ];
        $this->setCreateData($createData);
    }

    public function editBlock(Request $request)
    {
        $editRules = [
            'id' => 'required',
            'name' => 'required',
            'permission_ids' => 'required'
        ];
        $this->setEditRules($editRules);

        $editData = [
            'name' => $request->get('name'),
        ];
        $this->setEditData($editData);
    }

    public function edit(Request $request)
    {
        $this->validate($request, $this->editRules);
        $id = $request->get('id');
        $flag = $this->repository['self']->update($id,$this->editData,$request->get('permission_ids'));
        if($flag){
            return ResponseWrapper::success($flag);
        }
        return ResponseWrapper::fail();
    }

}