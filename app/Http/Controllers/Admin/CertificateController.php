<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Repositories\CertificateRepository;
use Illuminate\Http\Request;

class CertificateController extends Controller
{


    public function __construct(Request $request, CertificateRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }

    public function createBlock(Request $request)
    {
        $createRules = [
            'name' => 'required',
            'rank' => 'required',
            'description' => 'nullable',
            'district_id' => 'required|integer',
            'num' => 'required|integer',
        ];
        $this->setCreateRules($createRules);

        $createData = [
            'name' => $request->get('name'),
            'rank' => $request->get('rank'),
            'description' => $request->get('description'),
            'district_id' => $request->get('district_id'),
            'num' => $request->get('num'),
        ];
        $this->setCreateData($createData);
    }

    public function editBlock(Request $request)
    {
        $editRules = [
            'id' => 'required',
            'name' => 'required',
            'rank' => 'required',
            'district_id' => 'required|integer',
        ];
        $this->setEditRules($editRules);

        $editData = [
            'name' => $request->get('name'),
            'rank' => $request->get('rank'),
            'district_id' => $request->get('district_id'),
        ];
        $this->setEditData($editData);
    }
}