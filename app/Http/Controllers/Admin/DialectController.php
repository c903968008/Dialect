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
            'status' => Dialect::PASS
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
            'district_id' => $request->get('district_id'),
            'translation' => $request->get('translation'),
            'status' => Dialect::PASS
        ];
        $this->setEditData($editData);
    }

    public function create(Request $request)
    {
        $this->validate($request, $this->createRules);
        $file = $this->upload($request);
        if (!empty($file)){
            $this->createData['audio'] = $file;
        }
        $flag = $this->repository['self']->insert($this->createData);
        if(isset($flag)){
            return ResponseWrapper::success();
        }
        return ResponseWrapper::fail();
    }

    public function edit(Request $request)
    {
        $this->validate($request, $this->editRules);
        $id = $request->get('id');
        $file = $this->upload($request);
        if (!empty($file)){
            $this->editData['audio'] = $file;
        }
        $flag = $this->repository['self']->update($id,$this->editData);
        if($flag){
            return ResponseWrapper::success($flag);
        }
        return ResponseWrapper::fail();
    }

    public function index(Request $request)
    {
        $search = json_decode($request->get('search'),true);
        $dialect = $this->repository['self']->search($search);
        $dialect = $dialect->orderBy('id','DESC');
        $page = getParam($request,'page',1);
        $size = getParam($request,'size',20);
        $dialect = $this->repository['self']->getAll($dialect);
        $count = $dialect->count();
        if ($count == 0){
            return ResponseWrapper::success(['count'=>$count]);
        }
        $dialect = $this->repository['self']->page($dialect,$page,$size);
        return ResponseWrapper::success(['count'=>$count,'reslut'=>$dialect]);
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

    /*
     * 自动审核
     */
    public function autoAudit()
    {
        $this->repository['self']->autoAudit();
        return ResponseWrapper::success();
    }
}
