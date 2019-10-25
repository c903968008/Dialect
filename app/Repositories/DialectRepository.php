<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/27
 * Time: 12:22
 */

namespace App\Repositories;


use App\Dialect;
use Illuminate\Http\Request;

class DialectRepository extends Repository
{
    protected $model = Dialect::class;

    public function search($search)
    {
        $dialect = new Dialect();
        if (isset($search['translation'])) $dialect = $dialect->where('translation','like', '%'.$search['translation'].'%');
        if (isset($search['district_id'])) $dialect = $dialect->where('district_id',$search['district_id']);
        return $dialect;
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