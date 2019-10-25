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
use App\Repositories\DistrictRepository;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class DistrictController extends Controller
{
    public function __construct(Request $request, DistrictRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }

    public function createBlock(Request $request)
    {
        $createRules = [
            'name' => 'required',
        ];
        $this->setCreateRules($createRules);

        $createData = [
            'name' => $request->get('name'),
        ];
        $this->setCreateData($createData);
    }

    public function editBlock(Request $request)
    {
        $editRules = [
            'id' => 'required',
            'name' => 'required',
        ];
        $this->setCreateRules($editRules);

        $data = [
            'name' => $request->get('name'),
        ];
        $this->setEditData($data);
    }

    public function list()
    {
        $district = $this->repository['self']->all();
        return ResponseWrapper::success($district);
    }


}