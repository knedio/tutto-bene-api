<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Model\User;
use App\Model\UserDetail;
use App\Model\UserRole;
use App\Model\UserReport;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $users = User::get();
                
            return response([
                'message'   => 'Successful! Getting all the users.',
                'users'     => $users,
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
        try {
            $user = User::show($id);

            if(!$user) {
                return response([
                    'message'   => 'Sorry! User not found.',
                ], 404);    
            }

            return response([
                'message'   => 'Successful! Getting the user information.',
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            $user = User::create([
                'email'         => $request->email,
                'password'      => bcrypt($request->password),
                'remember_token'    => Str::random(10),
            ]);
                
            //  Generate QR Code for the user
            $fileName = 'qrcode-user-'. $user->id.'.png';
            $path = 'users\\user-' . $user->id;
            Storage::makeDirectory('public\\' . $path);
            $userPath = $path . '\\' . $fileName;
            \QrCode::format('png')
                ->size(500)
                ->generate('userId-' . $user->id, Storage::disk('public')->path($userPath));
            
            // Generate Serial No. for the user
            $serialNo = date('Y-').str_pad($user->id, 4, '0', STR_PAD_LEFT);

            $detail = UserDetail::create([
                'user_id'       => $user->id,
                'firstName'     => $request->firstName,
                'middleName'    => $request->middleName,
                'lastName'      => $request->lastName,
                'gender'        => $request->gender,
                'address'       => $request->address,
                'qrCode'        => $userPath,
                'phoneNo'       => $request->phoneNo,
            ]);

            $user->update([
                'serialNo'  => $serialNo,
            ]);

            $user = User::show($user->id);
                
            $token = $user->createToken('Tokenizer')->accessToken;
            
            return response([
                'user'      => $user,
                'token'     => $token,
                'message'   => 'Successful! Creating the user.',
            ], 200);
            
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
    public function update(UpdateRequest $request, User $user) 
    {
        try {
            $user->update($request->only([
                'email',
            ]));

            $detail = UserDetail::where('user_id', $user->id)->first();

            $detail->update($request->only([
                'firstName',
                'middleName',
                'lastName',
                'address',
                'gender',
            ]));

            $role = UserRole::create([
                'user_id'   => $user->id,
                'role_id'   => $row['role_id'],
            ]);

            return response([
                'user'      => User::get($user->id),
                'message'   => 'Successful! Updating a user.',
            ], 200);
        } catch (Exception $e) {
            $status = 400;

            if ($this->isHttpException($e)) $status = $e->getStatusCode();

            return response([
                'message'   => 'Something went wrong.'
            ], $status); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) 
    {
        try {
            $user->delete();

            return response([ 
                'message' => 'Successful! Deleting a user.' 
            ], 200);

        } catch (Exception $e) {
            $status = 400;

            if ($this->isHttpException($e)) $status = $e->getStatusCode();
            
            return response([
                'message'   => 'Something went wrong.'
            ], $status); 
        }
    }
}
