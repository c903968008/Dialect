<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers;



use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(Request $request, UserRepository $repository, bool $is_with = false)
    {
        parent::__construct($request, $repository, $is_with);
    }

}
