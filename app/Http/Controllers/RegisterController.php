<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cookie;

class RegisterController extends Controller
{
    // Controller action to register a new user.
    public function register(Request $request) {
        try{
            // check if all fields are available
            if($request->all()) {
                // make an api call to register the user
                $client = new Client();
                $response = $client->post(env('API_URL') . '/register/user', [
                    'form_params' => [
                        'first_name' => $request->input('first_name'),
                        'last_name' => $request->input('last_name'),
                        'email' => $request->input('email'),
                        'phone_number' => $request->input('phone_number'),
                        'password' => $request->input('password')
                    ]
                ]);

                if ($response->getStatusCode() == 201) {
                    $res = json_decode($response->getBody());
                    // get the api_token and phone_number from the response
                    $api_token = $res->User->api_token;
                    $phone_number = $res->User->phone_number;

                    // set api_token and phone number cookie
                    Cookie::queue('api_token', $api_token);
                    Cookie::queue('phone_number', $phone_number);
                    return redirect('/backend/activate');
                }

                if ($response->getStatusCode() == 500) {
                    return view('errors.500');
                }
            } else {
                return redirect('/backend/register');
            }
        } catch(\Exception $e) {
            return view('errors.500');
        }
    }
}
