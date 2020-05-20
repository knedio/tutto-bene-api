<?php

use Illuminate\Database\Seeder;
use App\Model\User;
use App\Model\UserDetail;
use App\Model\UserRole;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'email'     => 'admin@gmail.com',
                'firstName' => 'John',
                'middleName'=> '',
                'lastName'  => 'Joe',
                'address'   => 'CDO',
                'gender'    => 'male',
                'phoneNo'   => '09652354567',
                'role_id'    => 1
            ],[
                'email'     => 'staff@gmail.com',
                'firstName' => 'Chris',
                'middleName'=> '',
                'lastName'  => 'Joe',
                'address'   => 'CDO',
                'gender'    => 'male',
                'phoneNo'   => '09652354567',
                'role_id'    => 2
            ],[
                'email'     => 'user@gmail.com',
                'firstName' => 'Mike',
                'middleName'=> '',
                'lastName'  => 'Joe',
                'address'   => 'CDO',
                'gender'    => 'male',
                'phoneNo'   => '09652354567',
                'role_id'    => 3
            ]
        ];

        foreach ( $users as $row ) {
            $user = User::create([
                'email'         => $row['email'],
                'password'      => bcrypt('12345678'),
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
                'firstName'     => $row['firstName'],
                'middleName'    => $row['middleName'],
                'lastName'      => $row['lastName'],
                'gender'        => $row['gender'],
                'address'       => $row['address'],
                'qrCode'        => $userPath,
                'phoneNo'       => $row['phoneNo'],
            ]);

            $user->update([
                'serialNo'  => $serialNo,
            ]);

            $role = UserRole::create([
                'user_id'   => $user->id,
                'role_id'   => $row['role_id'],
            ]);
        }
    }
}
