<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function index()
    {
        return view('backend.register.signup');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    // Controller action to register a new user.
    public function register(Request $request)
    {
        try {
            // check if all fields are available
            if ($request->all()) {
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
                    $user_id = $res->User->_id;

                    // set api_token and phone number cookie
                    Cookie::queue('api_token', $api_token);
                    Cookie::queue('phone_number', $phone_number);
                    Cookie::queue('phone_number', $user_id);
                    return redirect('/admin/activate');
                }

                if ($response->getStatusCode() == 500) {
                    return view('errors.500');
                }
            } else {
                return redirect('/admin/register');
            }
        } catch (\Exception $e) {
            Log::error('Catch error: RegisterController - ' . $e->getMessage());
            return view('errors.500');
        }
    }
}
