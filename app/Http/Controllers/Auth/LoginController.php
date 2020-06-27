<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

// use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected $host;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->host = env('API_URL', 'https://customerpay.me/');
    }

    public function index()
    {
        if (Cookie::get('api_token')) {
            return redirect()->route('dashboard');
        }
        return view('backend.login');
    }

    public function authenticate(Request $request)
    {
        $validation = Validator::make(request()->all(), [
            'phone_number' => 'required|numeric|digits_between:1,16',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            $request->session()->flash('message', 'Invalid Phone number or password');
            $request->session()->flash('alert-class', 'alert-danger');
            return redirect()->route('login');
        }

        try {
            $client =  new \GuzzleHttp\Client();
            $response = $client->post($this->host . '/user', [
                'form_params' => [
                    'phone_number' => $request->input('phone_number'),
                    'password' => $request->input('password')
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                $response = json_decode($response->getBody());

                if (isset($response->Status) || (isset($response->Status) && !$response->status)) {
                    $request->session()->flash('message', $response->Message);
                    $request->session()->flash('alert-class', 'alert-danger');
                    return redirect()->route('login');
                }

                // get data from response
                $api_token = $response->api_token;
                $phone_number = $response->user->phone_number;
                $first_name = $response->user->first_name;
                $last_name = $response->user->last_name;
                $email = $response->user->email;
                $user_id = $response->user->_id;
                $is_active = $response->user->is_active;

                // store data to cookie

                Cookie::queue('api_token', $api_token);
                Cookie::queue('is_actives', $is_active);
                Cookie::queue('phone_number', $phone_number);
                Cookie::queue('user_id', $user_id);
                //check if active
                if ($is_active == false) {
                    return redirect()->route('activate.user');
                }

                // store other data to cookie
                Cookie::queue('first_name', $first_name);
                Cookie::queue('last_name', $last_name);
                Cookie::queue('email', $email);

                return redirect()->route('dashboard');
            }

            if ($response->getStatusCode() == 500) {
                return view('errors.500');
            }
        } catch (\Exception $e) {
            // log $e->getMessage() when error loggin is setup
            Log::error("catch error: LoginController - " . $e->getMessage());
            $request->session()->flash('message', 'something went wrong try again in a few minutes');
            return redirect()->route('login');
        }
        return redirect()->route('login');
    }
}
