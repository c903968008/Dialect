<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers;

use App\Repositories\ActivityRepository;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function __construct(Request $request, ActivityRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }

    public function index(Request $request)
    {
        $activity = $this->repository['self']->userList();
        return ResponseWrapper::success($activity);
    }

}
