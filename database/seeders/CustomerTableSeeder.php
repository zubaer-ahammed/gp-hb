<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $customers = [

            [
                'first_name'  => 'Zubaer',
                'last_name'  => 'Ahammed',
                'email'       => 'contact@zubaer.com',
                'phone'    => '01743214607',
                'registered_at'    => '2020-12-19 16:16:01',
            ],

            [
                'first_name'  => 'John',
                'last_name'  => 'Doe',
                'email'       => 'johndoe@example.com',
                'phone'    => '045465622',
                'registered_at'    => '2020-12-18 16:16:01',
            ]
        ];


        foreach ($customers as $customer) {

            $user = Customer::where(['email'=> $customer['email']])->first();

            if(empty($user)) {

                Customer::firstOrCreate($customer);

            }
        }




    }
}
