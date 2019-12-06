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
            'translation' => 'required',
        ];
        $this->setCreateRules($createRules);

        $createData = [
            'user_id' => Dialect::ADMIN,
            'district_id' => $request->get('district_id'),
            'translation' => $request->get('translation'),
        ];
        $this->setCreateData($createData);
    }

    public function editBlock(Request $request)
    {
        $editRules = [
            'id' => 'required',
            'district_id' => 'required',
            'translation' => 'required',
        ];
        $this->setEditRules($editRules);

        $editData = [
            'user_id' =>  Dialect::ADMIN,
            'district_id' => $request->get('district_id'),
            'translation' => $request->get('translation'),
        ];
        $this->setEditData($editData);
    }

    /*
     * 返回审核通过的某地区的方言
     */
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
     * 审核
     */
    public function audit(Request $request)
    {
        $validateRules = [
            'id' => 'required|integer',
            'status' => 'required'
        ];
        $this->validate($request, $validateRules);

        $id = $request->get('id');
        $status = $request->get('status');
        $updated = $this->repository['self']->status($id,$status);
        if ($updated){
            return ResponseWrapper::success();
        }
        return ResponseWrapper::fail();
    }

}
