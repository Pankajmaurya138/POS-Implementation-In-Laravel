<?php

namespace App\Http\Controllers\POS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;
use Config;
use stdClass;
use Cache;

class BookingController extends Controller {
    
    /**
     * return the Booking or reservation list.
     * @param $dateFrom & $dateTo
     * @return Response
    */
    public function getBookingOrder(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'orders/booking?dateFrom='.$request->dateFrom.'&dateTo='.$request->dateTo.'&restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "booking list",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * create the Booking or reservation.
     * @return Response
    */
    public function createBooking(Request $request){
        try {
            $restaurant_token = getRestaurantToken();
            $tokenData = [
                'restaurant_token'=>$restaurant_token,
                'provider_token'=>Config::get('pos.provider_token'),
            ];

            if(@$request->type==2){
                $input=[
                    'status'=>@$request->status,
                    'type'=>@$request->type,
                    // 'isPrinted'=>$request->isPrinted,
                    // 'isBooking'=>$request->isBooking,
                    'nbCustomers'=>@$request->nbCustomers,
                    'comment'=>@$request->comment,
                    // 'tableId'=>$request->tableId,
                    // 'waiterId'=>$request->waiterId,
                    // 'name'=>$request->name,
                    'openDate'=>@$request->openDate,
                    'lines'=>@$request->lines,
                    'payments'=>@$request->payments,
                    'sendings'=>@$request->sendings,
                    'customer'=>@$request->customer,
                ];
    
                $inputData =   array_merge($tokenData,$input);
                $url = Config::get('pos.base_path').'orders';
                $response = Curl::to($url)
                                ->withData($inputData)
                                ->asJson()
                                ->post();
                return customResponse(Config::get("http_status.OK"), true, "Booking created",$response);
            }else{
                return customResponse(Config::get("http_status.OK"), true, "Order Type must 2 for Booking", new stdClass());
            }    
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return list of details of cash register.
     * @param bookingId
     * @return Response
    */

    public function updateBooking(Request $request) {

        try {
            $restaurant_token = getRestaurantToken();
            $tokenData = [
                'restaurant_token'=>$restaurant_token,
                'provider_token'=>Config::get('pos.provider_token'),
            ];

            if(@$request->type==2){
                $input=[
                    //'status'=>$request->status,
                    'type'=>@$request->type,
                    // 'isPrinted'=>$request->isPrinted,
                    // 'isBooking'=>$request->isBooking,
                    'externalId'=>@$request->externalId,
                    'nbCustomers'=>@$request->nbCustomers,
                    'comment'=>@$request->comment,
                    'tableId'=>@$request->tableId,
                    'waiterId'=>@$request->waiterId,
                    'name'=>@$request->name,
                    'openDate'=>@$request->openDate,
                    'lines'=>@$request->lines,
                    'payments'=>@$request->payments,
                    'sendings'=>@$request->sendings,
                    'customer'=>@$request->customer,
                ];
    
                $inputData =   array_merge($tokenData,$input);
                $url = Config::get('pos.base_path').'orders/'.$request->bookingId;
                $response = Curl::to($url)
                                ->withData($inputData)
                                ->asJson()
                                ->patch();
                return customResponse(Config::get("http_status.OK"), true, "Booking Order updated",$response);
            }else{
                return customResponse(Config::get("http_status.OK"), true, "Order Type must 2 for Booking", new stdClass());
            } 
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }
}
