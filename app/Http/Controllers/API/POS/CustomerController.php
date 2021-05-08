<?php

namespace App\Http\Controllers\POS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;
use Config;
use stdClass;
use Cache;

class CustomerController extends Controller {

    /**
     * return list of customers.
     * @return Response
    */

    public function getCustomerList(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'customers?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token').'&syncDate='.$request->syncDate.'&since='.$request->since;
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "customer list",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * add new customer.
     * @return Response
    */

    public function addCustomer(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $tokenData = [
                'restaurant_token'=>$restaurant_token,
                'provider_token'=>Config::get('pos.provider_token'),
            ];
            $input= $request->all();
            $inputData =   array_merge($tokenData,$input);
            $url = Config::get('pos.base_path').'customers';
            $response = Curl::to($url)
                            ->withData($inputData)
                            ->asJson()
                            ->post();
            return customResponse(Config::get("http_status.OK"), true, "",$response);
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * update customer.
     * @param $id
     * @return Response
    */

    public function updateCustomer(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $tokenData = [
                'restaurant_token'=>$restaurant_token,
                'provider_token'=>Config::get('pos.provider_token'),
            ];
            $input= $request->all();
            $inputData =   array_merge($tokenData,$input);
            $url = Config::get('pos.base_path').'customers/'.$request->id;
            $response = Curl::to($url)
                            ->withData($inputData)
                            ->asJson()
                            ->patch();
            return customResponse(Config::get("http_status.OK"), true, "",$response);
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * delete customer.
     * @param $id
     * @return Response
    */
    
    public function deleteCustomer(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $tokenData = [
                'restaurant_token'=>$restaurant_token,
                'provider_token'=>Config::get('pos.provider_token'),
            ];
            $url = Config::get('pos.base_path').'customers/'.$request->id;
            $response = Curl::to($url)
                            ->withData($tokenData)
                            ->asJson()
                            ->delete();
            return customResponse(Config::get("http_status.OK"), true, "",$response);
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

}
