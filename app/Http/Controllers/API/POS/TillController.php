<?php

namespace App\Http\Controllers\POS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;
use Config;
use stdClass;
use Cache;

class TillController extends Controller {

    /**
     * return list of cash registers.
     * @return Response
    */

    public function getTill(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'tills?startDate='.$request->startDate.'&endDate='.$request->endDate.'&restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "cash register list",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * create till.
     * @return Response
    */

    public function createTill(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $tokenData = [
                'restaurant_token'=>$restaurant_token,
                'provider_token'=>Config::get('pos.provider_token'),
            ];
            $input=[
                'dateStart'=>@$request->dateStart,
                'dateEnd'=>@$request->dateEnd,
                'cashFund'=>@$request->cashFund,
                'transfers'=>@$request->transfers,
                'lastUpdate'=>@$request->lastUpdate,
                'updateByWaiter'=>@$request->updateByWaiter,
            ];
            $inputData =   array_merge($tokenData,$input);
            $url = Config::get('pos.base_path').'tills';
            $response = Curl::to($url)
                            ->withData($inputData)
                            ->asJson()
                            ->post();
            return customResponse(Config::get("http_status.OK"), true, "till created",$response);
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return list of details of cash register.
     * @param tillId
     * @return Response
    */

    public function getTillDetail(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'tills/'.$request->tillId.'?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "cash register detail",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return current till details 
     * @return Response
    */

    public function getCurrentTillDetail(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'tills/current?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "current till detail",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return till order detail.
     * @param tillId
     * @return Response
    */

    public function updateTill(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $tokenData = [
                'restaurant_token'=>$restaurant_token,
                'provider_token'=>Config::get('pos.provider_token'),
            ];

            $input=[
                'dateStart'=>@$request->dateStart,
                'dateEnd'=>@$request->dateEnd,
                'cashFund'=>@$request->cashFund,
                'transfers'=>@$request->transfers,
                'lastUpdate'=>@$request->lastUpdate,
                'updateByWaiter'=>@$request->updateByWaiter,
            ];

            $inputData =   array_merge($tokenData,$input);
            $url = Config::get('pos.base_path').'tills/'.$request->tillId;
            $response = Curl::to($url)
                            ->withData($inputData)
                            ->asJson()
                            ->patch();
            return customResponse(Config::get("http_status.OK"), true, "till updated sucessfully",$response);
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

     /**
     * return till order detail.
     * @param tillId
     * @return Response
    */

    public function getTillOrderDetail(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'tills/'.$request->tillId.'/orders?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "cash register detail",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return Retrieves the accounting for a period for closed cash registers.
     * @return Response
    */
    public function getTillAccounting(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'accounting?startDate='.$request->startDate.'&endDate='.$request->endDate.'&restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "accounting",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

}
