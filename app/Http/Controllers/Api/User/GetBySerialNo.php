<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;

class GetBySerialNo extends Controller
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
            $user = User::where('serialNo', $request->serialNo)->first();

            if(!$user) {
                return response([
                    'message'   => 'Sorry! User not found.',
                ], 404);    
            }

            return response([
                'message'   => 'Successful! Getting the user by serial no.',
                'user'      => $user,
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
