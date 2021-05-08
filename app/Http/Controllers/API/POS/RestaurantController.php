<?php

namespace App\Http\Controllers\POS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;
use App\Http\Requests\POS\Restaurant;
use Config;
use stdClass;
use Cache;

class RestaurantController extends Controller {

     /**
     * return restaurant info
     *
     * @param  
     * @return Response
    */
    public function getRestaurant(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'restaurant?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            $response = json_decode($response);
            return customResponse(Config::get("http_status.OK"), true, "restaurant",$response);
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return retaurant list  that associated with tiller system
     *
     * @param  
     * @return Response
    */


    public function getRestaurantList(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url =Config::get('pos.base_path').'restaurant/list?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "restaurant list",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return retaurant all details information
     *
     * @param  $restaurantId
     * @return Response
    */

    public function currentRestaurant(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $data  = [
                'restaurant_token'=>$restaurant_token,
                'restaurantId'=>$request->restaurantId,
                'provider_token'=>Config::get('pos.provider_token')
            ];

            $url =Config::get('pos.base_path').'restaurants/currents';
            $response = Curl::to($url)
                            ->withData($data)
                            ->asJson()
                            ->post();
            return customResponse(Config::get("http_status.OK"), true, "current restaurant",$response);
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return the tax details.
     *
     * @param taxId
     * @return Response
    */

    public function taxDetail(Request $request){
        try {
            $restaurant_token = getRestaurantToken();
            $url =Config::get('pos.base_path').'taxes/'.$request->taxId.'?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "tax details",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return the category details.
     *
     * @param categoryId
     * @return Response
    */

    public function getCategoryDetail(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url =Config::get('pos.base_path').'categories/'.$request->categoryId.'?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "categories details",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    
    public function getMenus(Request $request){
        try {
            $restaurant_token = getRestaurantToken();
            $url =Config::get('pos.base_path').'menus/'.$request->menuId.'?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "menues details",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return the getMenus.
     * @param productId
     * @return Response
    */

    public function getProductInMenu(Request $request){
        try {
            $restaurant_token = getRestaurantToken();
            $url =Config::get('pos.base_path').'productinmenus/'.$request->productId.'?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "menues details",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return the Onboarding recovery.
     * @return Response
    */

    public function getOnboarding(Request $request){
        try {
            $restaurant_token = getRestaurantToken();
            $url =Config::get('pos.base_path').'onboarding?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "onbording detail",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }
}

