<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ApiController extends Controller
{
    public function accessToken(Request $request) {

        $validate = $this->validations($request,"login");

        if($validate["error"]){

            return $this->prepareResult(false, [], $validate['errors'],"Error while validating user");

        }

        $user = User::where("email",$request->email)->first();

        if($user){

            //dump($request->password);
            //dump($user->password);

            if (Hash::check($request->password, $user->password)) {

                return $this->prepareResult(true, ["accessToken" => $user->createToken('Hotel Booking App')->accessToken], [],"User Verified");

            }else{

                return $this->prepareResult(false, [], ["password" => "Wrong passowrd"],"Password not matched");

            }

        }else{

            return $this->prepareResult(false, [], ["email" => "Unable to find user"],"User not found");

        }



    }

   /**

    * Get a validator for an incoming Hotel Booking requests.

    *

    * @param  \Illuminate\Http\Request  $request

    * @param  $type

    * @return \Illuminate\Contracts\Validation\Validator

    */

   public function validations($request,$type) {

       $errors = [ ];

       $error = false;

       if($type == "login") {

           $validator = Validator::make($request->all(),[

               'email' => 'required|email|max:255',

               'password' => 'required',

           ]);

           if($validator->fails()){

               $error = true;

               $errors = $validator->errors();

           }

       } elseif($type == "create room") {

           $validator = Validator::make($request->all(),[

               'room_number' => 'required',
               'price' => 'required',
               'max_persons' => 'required',
               'room_type' => 'required'

           ]);

           if($validator->fails()){

               $error = true;

               $errors = $validator->errors();

           }

       } elseif($type == "create customer") {

           $validator = Validator::make($request->all(),[

               'first_name' => 'required',
               'last_name' => 'required',
               'email' => 'required',
               'phone' => 'required'

           ]);

           if($validator->fails()){

               $error = true;

               $errors = $validator->errors();

           }

       } elseif($type == "create booking") {

           $validator = Validator::make($request->all(),[

               'room_number' => 'required',
               'arrival' => 'required',
               'checkout' => 'required',
               'book_type' => 'required',
               'book_time' => 'required',
               'customer_id' => 'required'

           ]);

           if($validator->fails()){

               $error = true;

               $errors = $validator->errors();

           }

       } elseif($type == "create payment") {

           $validator = Validator::make($request->all(),[

               'amount' => 'required',
               'date' => 'required',
               'customer_id' => 'required',
               'booking_id' => 'required'

           ]);

           if($validator->fails()){

               $error = true;

               $errors = $validator->errors();

           }

       }

       return ["error" => $error, "errors"=>$errors];

   }


    /**
     * Display a listing of the resource.
     *
     * @param $status
     * @param $data
     * @param $errors
     * @param $msg
     * @return array
     */

   private function prepareResult($status, $data, $errors,$msg)
   {

       return ['status' => $status,'data'=> $data,'message' => $msg,'errors' => $errors];

   }



   /**

    * Display a listing of the resource.

    *

    * @param  \Illuminate\Http\Request  $request

    * @return \Illuminate\Http\Response

    */

   public function getRooms(Request $request)
   {

       $rooms = Room::all();

       return $this->prepareResult(true, $rooms, [],"All rooms");

   }


    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Room $room
     * @return \Illuminate\Http\Response
     */

   public function showRoom(Request $request, Room $room) {

       return $this->prepareResult(true, $room, [],"All results fetched");


   }



   /**

    * Store a newly created resource in storage.

    *

    * @param  \Illuminate\Http\Request  $request

    * @return \Illuminate\Http\Response

    */

   public function storeRoom(Request $request) {

       $error = $this->validations($request,"create room");

       if ($error['error']) {

           return $this->prepareResult(false, [], $error['errors'],"Error in creating room");

       } else {

           $room = Room::create($request->all());

           return $this->prepareResult(true, $room, $error['errors'],"Room created");

       }

   }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Room $room
     * @return \Illuminate\Http\Response
     */

   public function updateRoom(Request $request, Room $room) {

       $error = $this->validations($request,"update room");

       if ($error['error']) {

           return $this->prepareResult(false, [], $error['errors'],"error in updating data");

       } else {

           $room = $room->fill($request->all())->save();

           return $this->prepareResult(true, $room, $error['errors'],"updating data");

       }


   }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Room $room
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */

   public function destroyRoom(Request $request, Room $room) {

       if ($room->delete()) {

           return $this->prepareResult(true, [], [],"Room deleted");

       }


   }


    /**

     * Display a listing of the resource.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function getCustomers(Request $request) {

        $email = $request->get('email');

        if(!empty($email)) {

            $customers = Customer::where(['email' => $email])->first();

        } else {

            $customers = Customer::all();

        }


        return $this->prepareResult(true, $customers, [],"All Customers");

    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param $customer_id
     * @return array
     */

    public function showCustomer(Request $request, $customer_id) {

        $customer = Customer::with('bookings')->find($customer_id)->toArray();

        return $this->prepareResult(true, $customer, [],"Customer fetched");


    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function storeCustomer(Request $request) {

        $error = $this->validations($request,"create customer");

        if ($error['error']) {

            return $this->prepareResult(false, [], $error['errors'],"Error in creating customer");

        } else {

            $customer = Customer::create($request->all());

            return $this->prepareResult(true, $customer, $error['errors'],"Customer created");

        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Customer $customer
     * @return \Illuminate\Http\Response
     */

    public function updateCustomer(Request $request, Customer $customer) {

        $error = $this->validations($request,"update customer");

        if ($error['error']) {

            return $this->prepareResult(false, [], $error['errors'],"error in updating data");

        } else {

            $customer = $customer->fill($request->all())->save();

            return $this->prepareResult(true, $customer, $error['errors'],"updating data");

        }


    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Customer $customer
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */

    public function destroyCustomer(Request $request, Customer $customer) {

        if ($customer->delete()) {

            return $this->prepareResult(true, [], [],"Customer deleted");

        }


    }


    /**

     * Display a listing of the resource.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function getBookings(Request $request) {


        $bookings = Booking::all();

        return $this->prepareResult(true, $bookings, [],"All Bookings");

    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Booking $booking
     * @return \Illuminate\Http\Response
     */

    public function showBooking(Request $request, $booking_id) {

        //$booking = Booking::with('payments')->find($booking_id)->get();

        $booking = Booking::with('payments')->where(['id' => $booking_id])->get();


        return $this->prepareResult(true, $booking, [],"All results fetched");


    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function storeBooking(Request $request) {

        $error = $this->validations($request,"create booking");

        if ($error['error']) {

            return $this->prepareResult(false, [], $error['errors'],"Error in creating booking");

        } else {

            $booking = Booking::create($request->all());

            return $this->prepareResult(true, $booking, $error['errors'],"Booking created");

        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Booking $booking
     * @return \Illuminate\Http\Response
     */

    public function updateBooking(Request $request, Booking $booking) {

        $error = $this->validations($request,"update booking");

        if ($error['error']) {

            return $this->prepareResult(false, [], $error['errors'],"error in updating data");

        } else {

            $booking = $booking->fill($request->all())->save();

            return $this->prepareResult(true, $booking, $error['errors'],"updating data");

        }


    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Booking $booking
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */

    public function destroyBooking(Request $request, Booking $booking) {

        if ($booking->delete()) {

            return $this->prepareResult(true, [], [],"Booking deleted");

        }


    }


    /**

     * Display a listing of the resource.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function getPayments(Request $request) {

        $payments = Payment::all();

        return $this->prepareResult(true, $payments, [],"All Payments");

    }


    public function getDuePayment(Request $request, $customer_id) {

        $bookings = Booking::with('payments')->where(['customer_id' => $customer_id, 'book_type' => 'partial'])->get();


        $all_dues = [];

        foreach ($bookings as $booking) {

            $amounts = [];
            $due = [];


            $payments = $booking->payments;

            foreach ($payments as $payment) {
                $amounts[] = floatval( $payment->amount );
            }


            $room = Room::where(['room_number' => $booking->room_number])->first();


            $room_price = $room->price;

            $paid_amount = array_sum($amounts);

            $total_due = floatval($room_price) - $paid_amount;

            $due['booking_id'] = $booking->id;
            $due['paid_amount'] = array_sum($amounts);
            $due['total_payable'] = $room_price;
            $due['total_due'] = $total_due;

            $all_dues[] = $due;

        }


        return $this->prepareResult(true, $all_dues, [],"All Payments");

    }



    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param $payment_id
     * @return array
     */

    public function showPayment(Request $request, $payment_id) {

        $payment = Payment::find($payment_id)->first();

        return $this->prepareResult(true, $payment, [],"All results fetched");


    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function storePayment(Request $request) {

        $error = $this->validations($request,"create payment");

        if ($error['error']) {

            return $this->prepareResult(false, [], $error['errors'],"Error in creating payment");

        } else {

            $payment =   Payment::create($request->all());

            return $this->prepareResult(true, $payment, $error['errors'],"Payment created");

        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Payment $payment
     * @return \Illuminate\Http\Response
     */

    public function updatePayment(Request $request, Payment $payment) {

        $error = $this->validations($request,"update payment");

        if ($error['error']) {

            return $this->prepareResult(false, [], $error['errors'],"error in updating data");

        } else {

            $payment = $payment->fill($request->all())->save();

            return $this->prepareResult(true, $payment, $error['errors'],"updating data");

        }


    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Payment $payment
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */

    public function destroyPayment(Request $request, Payment $payment) {

        if ($payment->delete()) {

            return $this->prepareResult(true, [], [],"Payment deleted");

        }


    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Payment $payment
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */

    public function checkout(Request $request, $customer_id) {

        Booking::where(['customer_id' => $customer_id])->update(['book_type' => 'complete', 'checkout' => date('Y-m-d H:i:s')]);

        $bookings = Booking::where(['customer_id' => $customer_id])->get();

        return $this->prepareResult(true, $bookings, [],"Checkout Complete");


    }



}
