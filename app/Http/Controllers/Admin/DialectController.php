<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Repositories\DialectRepository;
use Illuminate\Http\Request;

class DialectController extends Controller
{
    public function __construct(Request $request, DialectRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }
}