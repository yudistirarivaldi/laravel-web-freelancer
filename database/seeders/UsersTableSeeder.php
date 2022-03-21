<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
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
                'name'                  => 'Yudistira',
                'email'                 => 'Yudis@gmail.com',
                'password'              =>  Hash::make('yudis123'),
                'remember_token'        => NULL,
                'created_at'            => date('Y-m-d h:i:s'),
                'updated_at'            => date('Y-m-d h:i:s'),
            ],
            [
                'name'                  => 'Rivaldi',
                'email'                 => 'Rivaldi@gmail.com',
                'password'              =>  Hash::make('rivaldi123'),
                'remember_token'        => NULL,
                'created_at'            => date('Y-m-d h:i:s'),
                'updated_at'            => date('Y-m-d h:i:s'),
            ]
        ];

        User::insert($users);

    }
}
