<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\HomeController;


use Setting;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Jobs\SendVerificationEmail;


use GuzzleHttp\Client;

use Mail;
use App\Mail\Signupwelcomemail;

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
    protected $redirectTo = '/home';

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
        /*return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'acceptTerms' => 'required',
        ]);
        */

        $messsages = array(
            'acceptTerms.required'=>'Terms field is required',                            
        );

        $rules = array(
            'name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'mobile' => 'required|regex:/^[0-9 +]+$/u|unique:users',
            'acceptTerms' => 'required',            
            //'txid' => 'required|regex:/^[a-zA-Z0-9]+$/u',
        );

        $nicname = array(
                'acceptTerms'=>'Terms & Conditions ',
        );

        $validator = Validator::make($data, $rules,$messsages,$nicname);

        return $validator;
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $email=strtolower($data['email']);

        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = ["method" => "personal_newAccount", "params" => [$email], "id" => 1];

        $url ="http://localhost:8545";


        $res = $client->post($url, [
            'headers' => $headers,
            'body' => json_encode($body),
        ]);

        $eth_address = json_decode($res->getBody(),true);


        $email_token=base64_encode($email);

        $param=[$email];

        $body = [
            'params' => $param,
            'method' => 'getaccountaddress',
        ];
        // $curldata = (new HomeController)->bitcoin_npmcurl($body);
        // $curldata1 = (new HomeController)->bitcoincash_npmcurl($body);
        // $curldata2 = (new HomeController)->litecoin_npmcurl($body);
       

        $userdata=[
            'name' => $data['name'],
            'email' => $email,
            'address' => $eth_address['result'],
            // 'btc_address' => $curldata['result'],
            // 'bch_address' => $curldata1['result'],
            // 'ltc_address' => $curldata2['result'],
            'eth_address' => $eth_address['result'],
            'network' => 'ETH',
            'password' => bcrypt($data['password']),
            'mobile' => $data['mobile'],
            'email_token' =>$email_token ,
            'ip' => $ip,

        ];

        $userdata_curl=[
            'name' => $data['name'],
            'email' => $email,
            'password' => $data['password'],
            'email_token' => $email_token,
        ];

    

        Mail::to($email)->send(new Signupwelcomemail($userdata_curl));
        
        return User::create($userdata);
    }


        /**
        * Handle a registration request for the application.
        *
        * @param \Illuminate\Http\Request $request
        * @return \Illuminate\Http\Response
        */

        public function register(Request $request)
        {

            $this->validator($request->all())->validate();
            event(new Registered($user = $this->create($request->all())));

            //dispatch(new SendVerificationEmail($user));
            //return view('verification');

            
            \Session::flash('flash_success','Account created successfully, verify your account by your welcome mail from your mail account...');
            return redirect('/login');
        }


        /**
        * Handle a registration request for the application.
        *
        * @param $token
        * @return \Illuminate\Http\Response
        */

        public function verify($token)
        {

            $user = User::where('email_token',$token)->first();
            $user->verified = 1;
            $user->save();

            return view('emailconfirm');

            
        }
}
