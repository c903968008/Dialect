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

    public function list()
    {
        $district = $this->repository['self']->all();
        return ResponseWrapper::success($district);
    }

    public function ttt()
    {
        for ($i=1; $i <=401 ; $i++) { 
            $d = District::find(i);
            $d->created_at = "2020-02-08 14:55:54";
            $d->updated_at = "2020-02-08 14:55:54";
            $d->save();
        }
    }


}
