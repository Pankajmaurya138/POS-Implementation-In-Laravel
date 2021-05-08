<?php

namespace App\Http\Controllers\POS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\POS\AuthRequest;
use Ixudra\Curl\Facades\Curl;
use Config;
use stdClass;
use Cache;
use App\Pos\PosToken;

class AuthController extends Controller {

/**
    * generate restaurent token.
    * @param $login,$password,$provider_token
    * @return Response
*/
    public function generateToken(AuthRequest $request) {
      try {
            $input = $request->all();
            $data  = [
                'login'=>$request->login,
                'password'=>$request->password,
                'provider_token'=>Config::get('pos.provider_token')
            ];
            $url = Config::get('pos.base_path').'auth';
            $response = Curl::to($url)
                            ->withData($data)
                            ->asJson()
                            ->post();
            if(@$response->{'token'}) {
                PosToken::updateOrCreate(['name'=>'restaurant_token'],
                        [
                           'name' => 'restaurant_token',
                           'restaurant_token' => $response->{'token'},
                        ]
                );
                Cache::put("restaurant_token",$response->{'token'});   
            }   
            return customResponse(Config::get("http_status.OK"), true, "pos restaurent token",$response);
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

/**
    * return all restaurant devices.
    * @return Response
*/

    public function getDevices(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'devices?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "device list",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

/**
    * return all restaurant devices.
    * @param $deviceId
    * @return Response
*/

    public function getAuthSessionDevice(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $data  = [
                'restaurant_token'=>$restaurant_token,
                'provider_token'=>Config::get('pos.provider_token')
            ];
            $url = Config::get('pos.base_path').'auth/device/'.$request->deviceId;
            $response = Curl::to($url)
                            ->withData($data)
                            ->asJson()
                            ->post();
            return customResponse(Config::get("http_status.OK"), true, "device list",$response);
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }
}
