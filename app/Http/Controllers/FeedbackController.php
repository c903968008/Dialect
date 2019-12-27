<?php
/**
 * Created by PhpStorm.
 * User: CQJ
 * Date: 2019/9/26
 * Time: 13:29
 */

namespace App\Http\Controllers;


use App\Repositories\FeedbackRepository;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function __construct(Request $request, FeedbackRepository $repository, bool $is_with = true)
    {
        parent::__construct($request, $repository, $is_with);
    }

    public function createBlock(Request $request)
    {
        $createRules = [
            'question_id' => 'required|integer',
            'content' => 'required|string',
            'translation' => 'required|string',
        ];
        $this->setCreateRules($createRules);

        $createData = [
//            'user_id' => $request->get('sub'),
            'user_id' => 6,
            'question_id' => $request->get('question_id'),
            'content' => $request->get('content'),
            'translation' => $request->get('translation'),
        ];
        $this->setCreateData($createData);
    }
}
