<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 8:28
 */

namespace App\Http\Middleware;

use Closure;

class CrossRequestMiddleware
{

    public function handle($request, Closure $next)
    {
        $response = $next($request);
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With');
        return $response;
    }

}