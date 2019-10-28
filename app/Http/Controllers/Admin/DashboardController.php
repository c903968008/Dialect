<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/10/28
 * Time: 10:40
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct(Request $request, Repository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }

}