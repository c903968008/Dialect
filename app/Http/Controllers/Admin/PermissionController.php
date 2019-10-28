<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    public function __construct(Request $request, PermissionRepository $repository, bool $is_with = false)
    {
        parent::__construct($request, $repository, $is_with);
    }

    public function createBlock(Request $request)
    {
        $createRules = [
            'name' => 'required',
            'path' => 'required',
        ];
        $this->setCreateRules($createRules);

        $createData = [
            'name' => $request->get('name'),
            'path' => $request->get('path'),
        ];
        $this->setCreateData($createData);
    }

    public function editBlock(Request $request)
    {
        $editRules = [
            'id' => 'required',
            'name' => 'required',
            'path' => 'required',
        ];
        $this->setEditRules($editRules);

        $editData = [
            'name' => $request->get('name'),
            'path' => $request->get('path'),
        ];
        $this->setEditData($editData);
    }
}