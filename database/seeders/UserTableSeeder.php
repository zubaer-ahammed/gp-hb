<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = User::where(['email'=> 'admin@example.com'])->first();

        if(empty($admin)) {

            User::firstOrCreate([
                'name'        => 'admin',
                'email'       => 'admin@example.com',
                'password'    => Hash::make('admin')
            ]);

        }

    }
}
