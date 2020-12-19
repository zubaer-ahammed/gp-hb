<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rooms = [

            [
                "room_number" => "201",
                "price" => "2000",
                "max_persons" => 2,
                "room_type" => "normal"
            ],
            [
                "room_number" => "202",
                "price" => "3000",
                "max_persons" => 3,
                "room_type" => "deluxe"
            ],

        ];


        foreach ($rooms as $room) {

            $user = Room::where(['room_number'=> $room['room_number']])->first();

            if(empty($user)) {

                Room::firstOrCreate($room);

            }
        }
    }
}
