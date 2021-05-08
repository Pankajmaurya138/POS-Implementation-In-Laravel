<?php

namespace App\Http\Controllers\POS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;
use App\Http\Requests\POS\Restaurent;
use Config;
use stdClass;
use Cache;

class InventoryController extends Controller {
    
    /**
     * return inventory list.
     * @return Response
    */
    public function getInventory(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'inventory?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "inventory list",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    /**
     * return inventory list witn price
     * @return Response
    */

    public function getInventoryWithPrice(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $url = Config::get('pos.base_path').'inventary?restaurant_token='.$restaurant_token.'&provider_token='.Config::get('pos.provider_token');
            $response = Curl::to($url)->get();
            return customResponse(Config::get("http_status.OK"), true, "inventory list with price",json_decode($response));
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }


     /**
     * inventory import 
     * @return Response
    */
    public function importInventory(Request $request) {
        try {
            $restaurant_token = getRestaurantToken();
            $tokenData = [
                'restaurant_token'=>$restaurant_token,
                'provider_token'=>Config::get('pos.provider_token'),
                'clear' => $request->clear
            ];
            
            if ($request->hasFile('inventory')) {
                $inventory_file = $request->file('inventory');
                $name = time().'.'.$inventory_file->getClientOriginalExtension();
                $destinationPath = public_path('/inventoryFile/');
                $file_path =  $inventory_file->move($destinationPath, $name);
                $getPath = $file_path->getRealPath();
                // dd($getPath);
            
            $url = Config::get('pos.base_path').'inventory/import';
            $response = Curl::to($url)
            ->withData($tokenData)
            ->withContentType('multipart/form-data')
            ->withFile('inventory', $getPath,'text/csv',$name )
            ->post();
            return customResponse(Config::get("http_status.OK"), true, "",json_decode($response));
            }
        } catch (\Exception $e) {
            return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
        }
    }

    public function importInventory1(Request $request) {
        // dd('fkdh');
         try {
             $restaurant_token = getRestaurantToken();
             $tokenData = [
                 'restaurant_token'=>$restaurant_token,
                 'provider_token'=>Config::get('pos.provider_token'),
                 'clear' => $request->clear
             ];
             // $this->validate($request, [
             //     'inventory' => 'required|mimes:csv|max:2048',
             // ]);
             
             if ($request->hasFile('inventory')) {
                 $inventory_file = $request->file('inventory');
                 $name = time().'.'.$inventory_file->getClientOriginalExtension();
                 $destinationPath = public_path('/inventoryFile/');
                 $file_path =  $inventory_file->move($destinationPath, $name);
                 $getPath = $file_path->getRealPath();
                 // dd($getPath);
             
             $url = Config::get('pos.base_path').'inventory/import';
             $target_url = $url; // Write your URL here
                $dir = $getPath; // full directory of the file

                $cFile = curl_file_create($getPath,"text/csv",$name);
                // dd($cFile);
                //$post = array('file'=> $cFile); // Parameter to be sent
                $post = [
                    'restaurant_token'=>$restaurant_token,
                    'provider_token'=>Config::get('pos.provider_token'),
                    'clear' => $request->clear,
                    'inventory'=> $cFile
                ];
                $headers = array("Content-Type:multipart/form-data");
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $target_url);
                // curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_POST,1);
                // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result=json_decode(curl_exec($ch));              
             return customResponse(Config::get("http_status.OK"), true, "",json_decode($response));
             }
         } catch (\Exception $e) {
             return customResponse(Config::get("http_status.ISE"), false, "ISE", new stdClass());
         }
     }
}
