<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(Request $request, RoleRepository $roleRepository)
    {
        $this->repository['self'] = $roleRepository;

        $createRules = [
            'name' => 'required',
        ];
        $this->setCreateRules($createRules);

        $createData = [
            'name' => $request->get('name'),
        ];
        $this->setCreateData($createData);

        $editRules = [
            'id' => 'required',
            'name' => 'required',
        ];
        $this->setEditRules($editRules);

        $editData = [
            'name' => $request->get('name'),
        ];
        $this->setEditData($editData);
    }

}