<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use GuzzleHttp\Exception\RequestException;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->host = env('API_URL', 'https://dev.api.customerpay.me');
    }

    public function index()
    {
        return view('backend.password.recover');
    }



    public function authenticate(Request $request)
    {

        $validation = Validator::make(request()->all(), [
            'phone_number' => 'required|min:11|max:16',
        ]);

        if ($validation->fails()) {
            $request->session()->flash('message', 'Invalid Phone number');
            $request->session()->flash('alert-class', 'alert-danger');
            return redirect()->route('password');
        }

        try {
            $client =  new \GuzzleHttp\Client();
            $data = [
                "phone_number" => $request->input('phone_number')
            ];
            // return print_r($data);
            $this->headers = ['form_params' => $data];
            $response = $client->post($this->host . '/recover', $this->headers);

            if ($response->getStatusCode() == 200) {

                $response = json_decode($response->getBody());

                if (isset($response->success) && $response->success) {

                    $res_data = $response->data;
                    // store data to cookie

                    Cookie::queue('phone_number', $data['phone_number']);



                    $request->session()->flash('alert-class', 'alert-success');
                    $request->session()->flash('message', $response->message . " " . $res_data->otp);
                    return redirect()->route('reset_password');
                } else {
                    $message = isset($response->Message) ? $response->Message : $response->message;
                    $request->session()->flash('message', $message);
                    return redirect()->route('password');
                }
            }

            $message = isset($response->Message) ? $response->Message : $response->message;
            $request->session()->flash('message', $message);
            return redirect()->route('password');
        } catch (RequestException $e) {

            if ($e->getCode() == 400) {
                $request->session()->flash('message', 'Invalid Phone number or password. Ensure Your phone number uses internations format.e.g +234');
                $request->session()->flash('alert-class', 'alert-danger');
                return redirect()->route('password');
            }
            Log::error("catch error: ForgotPasswordController - " . $e->getMessage());
            $request_res = json_decode($e->getResponse()->getBody());
            $request->session()->flash('message', $request_res->message);
            return redirect()->route('password');
        }
        return redirect()->route('password');
    }
}
