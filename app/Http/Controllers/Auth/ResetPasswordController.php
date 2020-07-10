<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use GuzzleHttp\Exception\RequestException;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */



    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */



    public function __construct()
    {
        $this->host = env('API_URL', 'https://dev.api.customerpay.me');
    }

    public function reset(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z]).{6,20}$/|confirmed',
            'otp' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            $request->session()->flash('message', 'Invalid input please check and try again');
            $request->session()->flash(
                'alert-class',
                'alert-danger'
            );
            return redirect()->route('reset_password');
        }

        // return print_r($request->input()) ;

        try {
            $client =  new \GuzzleHttp\Client();
            $data = [
                "token" => $request->input('otp'),
                "password"=>$request->input('password')
            ];
            // return print_r($data);
            $this->headers = ['form_params' => $data];
            $response = $client->post($this->host . '/recover', $this->headers);

            if ($response->getStatusCode() == 200) {

                $response = json_decode($response->getBody());

                if (isset($response->success) && $response->success) {

                    $data = $response->data;
                    // store data to cookie
                    Cookie::queue('phone_number', $data->phone_number);
                    Cookie::queue('otp', $data->otp);

                    $request->session()->flash('alert-class', 'alert-success');
                    $request->session()->flash('message', $response->message);

                    //check if active
                    if ($data->is_active == false) {
                        return redirect()->route('activate.index');
                    }
                    return redirect()->route('login');
                } else {
                    $message = isset($response->Message) ? $response->Message : $response->message;
                    $request->session()->flash('message', $message);
                    return redirect()->route('reset_password');
                }
            }

            $message = isset($response->Message) ? $response->Message : $response->message;
            $request->session()->flash('message', $message);
            return redirect()->route('reset_password');
        } catch (RequestException $e) {

            if ($e->getCode() == 400) {
                $request->session()->flash('message', 'Invalid Phone number or password. Ensure Your phone number uses internations format.e.g +234');
                $request->session()->flash('alert-class', 'alert-danger');
                return redirect()->route('reset_password');
            }
            Log::error("catch error: ForgotPasswordController - " . $e->getMessage());
            return $e;
            $request_res = json_decode($e->getResponse()->getBody());
            $request->session()->flash('message', $request_res->message);
            return redirect()->route('reset_password');
        }
        return redirect()->route('reset_password');
    }

    public function index()
    {
        return view('backend.password.reset_password');
    }
}
