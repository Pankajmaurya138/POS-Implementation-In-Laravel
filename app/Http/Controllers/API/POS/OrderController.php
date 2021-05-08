<?php

namespace App\Http\Controllers\POS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;
use App\Http\Requests\POS\OrderRequest;
use Config;
use stdClass;
use Cache;

class OrderController extends Controller {

    public function getOrders(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'orders?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            if($request->query()){
                $query = $request->query();
                foreach($query as $key =>  $q){
                    if($query[$key] != ''){
                        $url = $url.'&'.$key.'='.$q;
                    }
                }
            }
            // $url = Config::get('pos.base_path').'orders?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token').'&dateFrom='.$request->dateFrom.'&dateTo='.$request->dateTo.'&updateFrom='.$request->dateTo.'&status='.$request->status.'&page='.$request->page.'&maxResults='.$request->maxResults.'&currentTill='.$request->currentTill.'&customerInvoice='.$request->customerInvoice;
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "orders list",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    public function getOrderDetails(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'orders/'.$request->id.'?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "orders details",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    public function createOrder(Request $request){
        try {
            $restaurant_token = getRestaurantToken();
            $tokenData = [
                'restaurant_token'=>$restaurant_token,
                'provider_token'=>Config::get('pos.provider_token'),
            ];

            $input=[
                'status'=>$request->status,
                'type'=>$request->type,
                // 'isPrinted'=>$request->isPrinted,
                // 'isBooking'=>$request->isBooking,
                'nbCustomers'=>$request->nbCustomers,
                'comment'=>$request->comment,
                // 'tableId'=>$request->tableId,
                // 'waiterId'=>$request->waiterId,
                // 'name'=>$request->name,
                'openDate'=>$request->openDate,
                'lines'=>$request->lines,
                'payments'=>$request->payments,
                'sendings'=>$request->sendings,
                'customer'=>$request->customer,
            ];

            $inputData =   array_merge($tokenData,$input);
            $url = Config::get('pos.base_path').'orders';
            $response = Curl::to($url)
                            ->withData($inputData)
                            ->asJson()
                            ->post();
            return customResponse(Config::get("http_status.OK"), true, "orders created",$response);
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    public function updateOrder(Request $request){
        try {
            $restaurant_token = getRestaurantToken();
            $tokenData = [
                'restaurant_token'=>$restaurant_token,
                'provider_token'=>Config::get('pos.provider_token'),
            ];

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
            $url = Config::get('pos.base_path').'orders/'.$request->orderId;
            $response = Curl::to($url)
                            ->withData($inputData)
                            ->asJson()
                            ->patch();
            return customResponse(Config::get("http_status.OK"), true, "orders updated",$response);
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    public function getOrderProducts(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'orders/products?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "orders products ",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    public function getCondensedOrders(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'orders/condensed?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "condense orders",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return customer order list.
     * @param $customerId
     * @return Response
    */

    public function getCustomerOrderList(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'orders/'.$request->customerId.'/customer?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "customer orders list",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return customer order list.
     * @param $externalCustomerId
     * @return Response
    */

    public function getExternalCustomerOrderList(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'orders/'.$request->externalCustomerId.'/externalcustomer?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "external customer orders list",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }
    
     /**
     * return table order detail.
     * @param $tableName
     * @return Response
    */
    public function getCustomerTableOrder(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'orders/'.$request->tableName.'/table?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "customer orders list",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    
}
