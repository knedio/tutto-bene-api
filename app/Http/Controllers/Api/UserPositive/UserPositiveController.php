<?php

namespace App\Http\Controllers\Api\UserPositive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\UserPositive;
use Auth;

class UserPositiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $positives = UserPositive::get();
                
            return response([
                'message'   => 'Successful! Getting all the user positives.',
                'positives'     => $positives,
            ]);
        } catch (Exception $e) {
            $status = 400;

            if ($this->isHttpException($e)) $status = $e->getStatusCode();

            return response([
                'message'   => 'Something went wrong.'
            ], $status); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $positive = UserPositive::create([
                'user_id'       => $request->user_id,
                'created_by'    => Auth::user()->id,
            ]);
                
            return response([
                'message'   => 'Successful! Marking user as postive in COVID',
                'positive'     => UserPositive::find($positive->id),
            ]);
        } catch (Exception $e) {
            $status = 400;

            if ($this->isHttpException($e)) $status = $e->getStatusCode();

            return response([
                'message'   => 'Something went wrong.'
            ], $status); 
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
