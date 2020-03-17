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
            'p_id' => 'required'
        ];
        $this->setCreateRules($createRules);

        $createData = [
            'name' => $request->get('name'),
            'p_id' => $request->get('p_id'),
        ];
        $this->setCreateData($createData);
    }

    public function editBlock(Request $request)
    {
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

    //根据p_id查
    public function list(Request $request)
    {
        $validateRules = [
            'p_id' => 'required|integer'
        ];
        $this->validate($request, $validateRules);

        $p_id = $request->get('p_id');
        $district = $this->repository['self']->getByPid($p_id);
        return ResponseWrapper::success($district);
    }

    //根据p_id查上级地区
    public function getPrevious(Request $request)
    {
        $validateRules = [
            'p_id' => 'required|integer'
        ];
        $this->validate($request, $validateRules);

        $p_id = $request->get('p_id');
        $district = $this->repository['self']->getPrevious($p_id);
        return ResponseWrapper::success($district);
    }

    public function getAll()
    {
        $district = $this->repository['self']->getAll();
        return ResponseWrapper::success($district);
    }

}
