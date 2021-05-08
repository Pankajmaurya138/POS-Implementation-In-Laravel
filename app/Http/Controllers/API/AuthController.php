<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function generateToken(Request $request){
        $data =[];
        $sumup = new \SumUp\SumUp([
            'app_id'     => 'zzonE1h9fPUq0C7sFwam2ipT0Y3n',
            'app_secret' => 'a6b4817b1fd7386ba49092719001161781a8bb7f84530f8db3814345b43d17ad',
            'grant_type' => 'client_credentials',
            'scopes'      => ['payments']
        ]);
        $accessToken = $sumup->getAccessToken();
        $data['token'] = $accessToken->getValue();
        $data['type'] = $accessToken->getType();
        return response()->json(['data'=>$data]);
    }
}
