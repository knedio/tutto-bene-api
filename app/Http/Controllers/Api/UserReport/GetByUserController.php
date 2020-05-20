<?php

namespace App\Http\Controllers\Api\UserReport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserReport;

class GetByUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            $reports = UserReport::where('user_id', $request->user_id)
            ->get();
                
            return response([
                'message'   => 'Successful! Getting all the user reports.',
                'reports'     => $reports,
            ]);
        } catch (Exception $e) {
            $status = 400;

            if ($this->isHttpException($e)) $status = $e->getStatusCode();

            return response([
                'message'   => 'Something went wrong.'
            ], $status); 
        }
    }
}
