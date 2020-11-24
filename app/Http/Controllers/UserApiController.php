<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MobileApiController;
use Illuminate\Support\Facades\Crypt;
use App\Mail\Changepasswordalert;
use App\Mail\Receivetranscationmail;
use App\Mail\Sendtranscationmail;
use App\Notifications\AccountResetPassword;
use App\Notifications\FeeWalletRecharge;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\CoinType;
use App\Currency;
use App\Transaction;
use App\User;
use App\Gasprice;
use App\EcpaySend;
use App\News;
use Auth;
use DB;
use Exception;
use Hash;
use Log;
use Mail;
use Notification;
use Setting;

class UserApiController extends Controller
{
    public function signincheck(Request $request)
    {
        try {
            $response = [];
            $user = User::where('email', $request->email)->first();
            if (is_object($user)) {
                $response = ['status' => 1, 'msg' => "success"];
            } else {
                $response = ['status' => 0, 'msg' => "Invalid credential"];
            }
            return $response;
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function signin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'phrase_word' => 'required',
            'client_id' => 'required',
            'client_secret' => 'required',
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'device_id' => 'required',
        ]);

        try {
            $User = User::where('email', $request->email)->where('phrase_word', $request->phrase_word)->first();

            if (is_object($User)) {
                $User->device_id = $request->device_id;
                $User->device_token = $request->device_token;
                $User->device_type = $request->device_type;
                $User->save();

                $access = DB::table('oauth_access_tokens')->where('user_id', $User->id)->delete();

                $token = $User->createToken($User->phrase_word)->accessToken;
                $User->access_token = $token;
                return $User;
            } else {
                return response()->json(['error' => 'Invalid credentials, Please check and try again...'], 500);
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function signupcheck(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255',
            'email' => 'required|email|max:255|unique:users',
        ]);

        try {
            $phrase_limit = 12;

            $phrase = (new MobileApiController)->recovery_phrase($phrase_limit);

            return $phrase;
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function signup(Request $request)
    {
        $this->validate($request, [
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'device_id' => 'required',
            'name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255',
            'email' => 'required|email|max:255|unique:users',
            'dob' => 'date_format:d-m-Y',
            'phrase_word' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|unique:users',
        ]);

        //try{

        $email = strtolower($request['email']);

        //---------------- LIO ------------
        $param = [$email];

        $body = [
            'params' => $param,
            'method' => 'getaccountaddress',
        ];
        $curldata1 = $this->npmcurl($body);

        $address = $curldata1['result'];
        //---------------- LIO ------------

        //---------------- BTC ------------
        $curldata = $this->bitcoin_npmcurl($body);
        //---------------- BTC ------------

        //---------------- ETH ------------
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = ["method" => "personal_newAccount", "params" => [$email], "id" => 1];

        $url = "http://localhost:8545";
        $res = $client->post($url, [
            'headers' => $headers,
            'body' => json_encode($body),
        ]);
        $eth_address = json_decode($res->getBody(), true);

        //---------------- ETH ------------

        //---------------- XRP ------------
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = [];

        $url = "http://localhost:1337/getPrivatekey";

        //---------------- XRP ------------

        $User = $request->all();
        $User['dob'] = $request->dob;
        $User['address'] = $address;
        $User['btc_address'] = ""; //$curldata['result'];
        $User['eth_address'] = ""; //$eth_address['result'];
        if (isset($result['publickey']) && isset($result['privatekey'])) {
            $User['xrp_address'] = $result['publickey'];
            $key = $this->simple_crypt($result['privatekey'], 'encrypt');
            $User['x_remember_flag_port'] = substr($key, 0, 10);
            $User['x_remember_flag_star'] = substr($key, 10);
        }
        $User['network'] = 'LIO';
        $User['phrase_word'] = $request->phrase_word;
        $User['password'] = bcrypt($request->name);

        $cointype = CoinType::where('status', '1')->get()->toArray();

        foreach ($cointype as $key => $value) {
            if ($value['symbol'] == "LIO") {
                $coin_selected = $value['id'];
            }
        }

        $User['coin_types'] = $coin_selected;
        $User['fiat_currency'] = "USD";

        $e_token = base64_encode($email);
        $User['email_token'] = $e_token;
        $User = User::create($User);

        $token = $User->createToken($User->recovery)->accessToken;
        $User->access_token = $token;

        $userdata1 = [
            'name' => $User['name'],
            'email' => $email,
            'phrase_word' => $User['phrase_word'],
            'email_token' => $e_token,
            'ip' => $User['ip'],
        ];

        return $User;

        /*} catch (Exception $e) {
    return response()->json(['error' => trans('api.something_went_wrong')], 500);
    }*/
    }

    public function logout(Request $request)
    {
        try {
            User::where('id', $request->id)->update(['device_id' => '', 'device_token' => '']);
            return response()->json(['message' => trans('api.logout_success')]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    //network

    public function network(Request $request)
    {
        try {
            if ($request->network == 'BTC') {
                User::where('id', Auth::user()->id)->update(['address' => Auth::user()->btc_address, 'network' => $request->network]);
            }
            if ($request->network == 'ETH') {
                User::where('id', Auth::user()->id)->update(['address' => Auth::user()->eth_address, 'network' => $request->network]);
            }

            return response()->json(['message' => 'Network Changed']);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function change_password(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
            'old_password' => 'required',
        ]);

        $User = Auth::user();

        if (Hash::check($request->old_password, $User->password)) {
            $User->password = bcrypt($request->password);
            $User->save();

            $user_email = Auth::user()->email;
            Mail::to($user_email)->send(new Changepasswordalert($user_email));

            Auth::logout();
            \Session::flash('flash_success', trans('user.profiles.pass_updated'));

            if ($request->ajax()) {
                return response()->json(['message' => trans('api.user.password_updated')]);
            } else {
                return back()->with('flash_success', 'Password Updated');
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.user.change_password')], 500);
            } else {
                return back()->with('flash_error', trans('api.user.change_password'));
            }
        }
    }

    public function index(Request $request)
    {
        try {
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json',
            ];

            $contract = "";
            $decimal = '';
            $erc = 0;
            $coin = 0;

            $userone = Auth::user();
            $user = User::findOrFail($userone->id);
            $coin_selected = explode(',', $user->coin_types);

            if (!isset($request['network'])) {
                $request->network = $user->network;
            } else {
                $user->network = $request->network;
                $user->save();
            }

            $name = $user->email;

            if ($user->address == null) {
                //getaddressbyaccount
                $param = [$name];
                $body = [
                    'params' => $param,
                    'method' => 'getaccountaddress',
                ];
                $curldata = $this->npmcurl($body);
                $user->address = $curldata['result'];
                $user->save();
            }

            if ($user->btc_address == null) {
                //getaddressbyaccount
                $param = [$name];
                $body = [
                    'params' => $param,
                    'method' => 'getaccountaddress',
                ];
                $curldata = $this->bitcoin_npmcurl($body);
                $user->btc_address = $curldata['result'];
                $user->save();
            }

            if ($user->eth_address == null) {
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                ];
                $body = ["method" => "personal_newAccount", "params" => [$name], "id" => 1];

                $url = "http://localhost:8545";

                $res = $client->post($url, [
                    'headers' => $headers,
                    'body' => json_encode($body),
                ]);
                $eth_address = json_decode($res->getBody(), true);
                $user->eth_address = $eth_address['result'];
                $user->save();
            }

            if ($user->xrp_address == null) {
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                ];
                $body = [];

                $url = "http://localhost:1337/getPrivatekey";

                $res = $client->post($url, [
                    'headers' => $headers,
                    'body' => json_encode($body),
                ]);

                $result = json_decode($res->getBody(), true);

                if (isset($result['publickey']) && isset($result['privatekey'])) {
                    $user->xrp_address = $result['publickey'];

                    $key = $this->simple_crypt($result['privatekey'], 'encrypt');
                    $user->x_remember_flag_port = substr($key, 0, 10);
                    $user->x_remember_flag_star = substr($key, 10);
                    $user->save();
                }
            }

            if ($request->network == 'BTC' || $request->network == 'ETH' || $request->network == 'ECpay') { // -------- BTC and ETH coin

                if ($user->network == 'BTC') {
                    $curldata_address['result'] = $user->btc_address;
                }

                if ($user->network == 'ETH') {
                    $curldata_address['result'] = $user->eth_address;
                }

                if ($user->network == 'ECpay') {
                    $curldata_address['result'] = $user->eth_address;
                }

                $address = $curldata_address['result'];   /// What is the reason ?

                if ($user->network == 'BTC') {
                    //$curldata = $this->bitcoin_npmcurl($body);
                    // unspent key block starts to get real balance in the account
                    $param = [1, 9999999,[$user->btc_address]];
                    $body = [
                        'params' => $param,
                        'method' => 'listunspent',
                    ];
                    $res = $this->bitcoin_npmcurl($body);

                    $utxos = $res['result'];
                    /// unspent key block ends
                    if (!empty($utxos)) {
                        $curldata['result'] = doubleval(self::txbalance($utxos)) ;
                    } else {
                        $curldata['result'] = 0;
                    }
                    $coin = $curldata['result'];
                    $avb_amt = $coin;
                    $fee = 0.000374;

                    $calc_amt = \DB::select('select ' . $avb_amt . '-' . $fee);

                    $arr = json_decode(json_encode($calc_amt[0]), true);
                    $tranfee_temp = current($arr);

                    if ($tranfee_temp <= 0) {
                        $tranfee = '0';
                    } else {
                        $tranfee = $tranfee_temp;
                    }
                }
                if ($user->network == 'ETH') {
                    $client = new Client;
                    $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=balance&address=' . $address.'&apikey=SRHNYU6D81WRIC2BJGQFVZKF2A67WMFQHJ');
                    $coindetails = json_decode($coindetails->getBody(), true);
                    $amount = $coindetails['result'] / 1000000000000000000;
                    $curldata['result'] = $amount;
                    $coin = $amount;
                    $avb_amt = $coin;
                    $gas = $this->getGas();
                    $fee = $gas['gasfee'];
                    $fee = (double)$fee * (double) 21000 / (double)1000000000000000000;
                    //$fee = 0.000374;

                    $calc_amt = \DB::select('select ' . $avb_amt . '-' . $fee);
                    $arr = json_decode(json_encode($calc_amt[0]), true);
                    $tranfee_temp = current($arr);

                    if ($tranfee_temp <= 0) {
                        $tranfee = '0';
                    } else {
                        $tranfee = $tranfee_temp;
                    }
                }
                if ($user->network == 'ECpay') {
                    $client = new Client;
                    $coindetails = $client->get("https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=0x3b0D6B5F04C1A70a661F9EF32992f9e2C670ae7A&address=" . $address.'&apikey=SRHNYU6D81WRIC2BJGQFVZKF2A67WMFQHJ');
                    $coindetails = json_decode($coindetails->getBody(), true);
                    $amount = (isset($coindetails['result']) && $coindetails['result']!='') ? $coindetails['result'] / 1000000000000:0.0;
                    $curldata['result'] = $amount; // number_format($amount, 8, '.', '');
                    if (isset($curldata['result'])) {
                        $coin = $curldata['result'];
                    }
                    /// check eth available for sending ECpay //
                    // $client = new Client;
                    // $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=balance&address=' . $address.'&apikey=SRHNYU6D81WRIC2BJGQFVZKF2A67WMFQHJ');
                    // $coindetails = json_decode($coindetails->getBody(), true);
                    // $amount1 = $coindetails['result'] / 1000000000000000000;
                    // /// If eth balance is less than the gas limit ECpay not allowed to send so we set transferable amount to zero.
                    // if(isset($curldata['result']) && $amount1 > 0.00492)
                    //     $tranfee = $amount;
                    // else
                    $tranfee = $amount;
                }
                $coin_type = array();
                $client = new Client;
                //----------------------- BTC ----------------
                if ($user->fiat_currency == "USD") {
                    $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/btcusd/');
                } elseif ($user->fiat_currency == "EUR") {
                    $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/btceur/');
                }
                $bitstampdetails = json_decode($bitstamp->getBody(), true);
                $coin_type['BTC'] = $bitstampdetails['last'];
                $BTC = $coin_type['BTC'];
                //----------------------- End BTC ----------------

                //----------------------- ETH ----------------
                if ($user->fiat_currency == "USD") {
                    $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/ethusd/');
                } elseif ($user->fiat_currency == "EUR") {
                    $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/etheur/');
                }
                $bitstampdetails = json_decode($bitstamp->getBody(), true);
                $coin_type['ETH'] = $bitstampdetails['last'];
                $ETH = $coin_type['ETH'];
                //----------------------- END ETH  ----------------

                if ($user->network == 'BTC') {
                    $currency = "BTC";
                    $currency_value = $BTC;
                    $erc = 0;
                }
                if ($user->network == 'ETH') {
                    $currency = "ETH";
                    $currency_value = $ETH;
                    $erc = 0;
                    $decimal = 18;
                }

                if ($user->network == 'ECpay') { // --------------- EcPay Token coin
                    $currency = "ECpay";
                    if ($user->fiat_currency == "USD") {
                        // $bitstamp = $client->get('https://www.bitstamp.net/api/eur_usd/');
                        $bitstamp = $client->get('https://api-pub.bitfinex.com/v2/ticker/tEURUSD');
                        $bitstampdetails = json_decode($bitstamp->getBody(), true);
                        $ecpay = $bitstampdetails[0];
                    } elseif ($user->fiat_currency == "EUR") {
                        $ecpay = 1; //$curldata['result'];
                    }

                    $currency_value = $ecpay;
                    $decimal = 12;
                    $erc = 0;
                    // $tranfee = 0;   /// Because fee taken from ETH
                }
            } elseif ($request->network == 'XRP') { // ------------------------- XRP coin
                $currency = "XRP";
                if ($user->fiat_currency == "USD") {
                    $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/xrpusd/');
                } elseif ($user->fiat_currency == "EUR") {
                    $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/xrpeur/');
                }
                $bitstampdetails = json_decode($bitstamp->getBody(), true);
                $XRP = $bitstampdetails['last'];

                $currency_value = $XRP;
                $erc = 0;
                $address = $user->xrp_address;

                $xrp_bal_url = 'http://s2.ripple.com:51234';
                $headers = [
                    'Content-Type' => 'application/json',
                ];
                $param = ["account" => $address, "strict" => true, "ledger_index" => "validated"];
                $body = ["method" => "account_info", "params" => [$param]];

                $res = $client->post($xrp_bal_url, [
                    'headers' => $headers,
                    'body' => json_encode($body),
                ]);

                $xrp_tmp_balance = json_decode($res->getBody(), true);

                if (isset($xrp_tmp_balance['result']['account_data'])) {
                    $xrp_balance = $xrp_tmp_balance['result']['account_data']['Balance'];

                    $coin = $xrp_balance / 1000000;
                }

                $avb_amt = $coin;
                $fee = 0.000374;

                $calc_amt = \DB::select('select ' . $avb_amt . '-' . $fee);

                $arr = json_decode(json_encode($calc_amt[0]), true);
                $tranfee_temp = current($arr);

                if ($tranfee_temp <= 0) {
                    $tranfee = '0';
                } else {
                    $tranfee = $tranfee_temp;
                }
            } elseif ($request->network == 'LIO') { // ------------------ LIO coin

                $fiat_currency = $user->fiat_currency;

                $currencies = Currency::where('currency', $fiat_currency)->first();
                $currency = $fiat_currency;

                $client = new Client;
                $lio_usd = $client->get('https://www.euro-btc.exchange/api/v2/tickers/lioecpay');

                $liveprice_lio = json_decode($lio_usd->getBody(), true);

                if ($currency == 'USD') {
                    $url = 'https://api.exchangeratesapi.io/latest?symbols=USD';
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $headers = array();
                    $headers[] = "Accept: application/json, text/plain";
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    if (curl_errno($ch)) {
                        echo $result = 'Error:' . curl_error($ch);
                    } else {
                        $result = curl_exec($ch);
                    }
                    curl_close($ch);
                    $result = json_decode($result);
                    $usd_liveprice = $result->rates->USD;
                    $currency_value = $usd_liveprice * $liveprice_lio['ticker']['last'];
                } else {
                    $currency_value = $liveprice_lio['ticker']['last'];
                }
                $address = $user->address;

                $add = Auth::user()->name;
                $param = [strtolower($name),1];
                //$param = [1, 9999999,[$user->address]];
                $body = [
                    'params' => $param,
                    'method' => 'getbalance',  // getaddressesbyaccount
                ];

                $curldata = $this->npmcurl($body);
                // $utxos = $curldata['result'];
                /// unspent key block ends
                // if(!empty($utxos)){
                //     $curldata['result'] = doubleval(self::txbalance($utxos)) ;
                // } else {
                //     $curldata['result'] = 0;
                // }

                if (isset($curldata['result'])) {
                    $coin = $curldata['result'];
                }

                $avb_amt = $coin;
                $fee = 0.000374;

                $calc_amt = \DB::select('select ' . $avb_amt . '-' . $fee);

                $arr = json_decode(json_encode($calc_amt[0]), true);
                $tranfee_temp = current($arr);

                if ($tranfee_temp <= 0) {
                    $tranfee = '1';
                } else {
                    $tranfee = $tranfee_temp;
                }
            }

            $user->ecpay_address = $user->eth_address;

            return response()->json(['coin_selected' => $coin_selected, 'address' => $address, 'coin' => $coin, 'user' => $user, 'coin_value' => $currency_value, 'currency' => $currency, 'available_final_amount' => $tranfee, 'contract' => $contract, 'erc' => $erc, 'decimal' => $decimal], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function privatekey()
    {
        try {
            $btc_key = $eth_key = $xrp_key = $lio_key = '';
            $user = Auth::user();
            $email = $user->email;

            $client = new Client;

            $btc_address = $user->btc_address;
            $eth_address = $user->eth_address;
            $lio_address = $user->address;

            //------------- Bitcoin ---------------

            $param = [$btc_address];

            $body = [
                'params' => $param,
                'method' => 'dumpprivkey',
            ];

            $curldata = $this->bitcoin_npmcurl($body);

            $btc_key = $curldata['result'];
            //------------- Bitcoin ---------------

            //------------- Liocoin ---------------

            $param_lio = [$lio_address];

            $body_lio = [
                'params' => $param_lio,
                'method' => 'dumpprivkey',
            ];

            $curldata_lio = $this->npmcurl($body_lio);

            $lio_key = $curldata_lio['result'];

            //------------- Liocoin ---------------

            //------------- Ethereum ---------------
            $privatekey = $client->get('http://localhost:8083/getPrivatekey?address=' . $eth_address . '&pwd=' . $email);
            //$privatekey = $client->get('http://localhost:8545/getPrivatekey?pwdchk='.$email.'~'.$address);
            $privatekey = json_decode($privatekey->getBody(), true);
            $eth_key = $privatekey['privatekey'];
            //------------- Ethereum ---------------

            $xrp_temp_key = $user->x_remember_flag_port . $user->x_remember_flag_star;
            $xrp_key = $this->simple_crypt($xrp_temp_key, 'decrypt');

            return response()->json(['btc_key' => $btc_key, 'eth_key' => $eth_key, 'xrp_key' => $xrp_key, 'lio_key' => $lio_key], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function sendcoin(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric',
            'to_address' => 'required|regex:/^[a-zA-Z0-9]+$/u',
        ]);
        try {
            $user = Auth::user();
            if ($user->block_status == 0) {
                return response()->json(['status'=>'Failed','message' => "Transaction Failed, Please try again later."], 200);
            }
            $name = $user['email'];
            if ($user->network == 'LIO') {
                $param = [$name, $request->to_address, (float) $request->amount, 6];
                $body = [
                    'params' => $param,
                    'method' => 'sendfrom',
                ];
                $res = $this->npmcurl($body);
                if (isset($res['result'])) {
                    $curldata['result'] = $res['result'];
                } else {
                    return response()->json(['status'=>'Failed','message' => $res], 500);
                }
            } elseif ($user->network == 'BTC') {
                $name = $user['email'];
                $from_address = $user['btc_address'];
                $param = [$name, $from_address, $request->to_address, $request->amount];
                $body = [
                    'params' => $param,
                    'method' => 'sendfrom',   /// This method to be changed to raw transaction based send method.
                ];
                $res = $this->sendbtc($body);
                if (isset($res['result'])) {
                    $curldata['result'] = $res['result'];
                } else {
                    return response()->json(['status'=>'Failed','message' => "Transaction Failed"], 200);
                }
                //$curldata = $this->bitcoin_npmcurl($body); /// previous method used
            } elseif ($user->network == 'ETH') {
                $name = $user['email'];
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ];
                $body = ["jsonrpc" => "2.0", "method" => "getPrivateKey", "params" => array("method" => "getPrivateKey", "address" => $user->eth_address, "password" => $user->email)];
                $url = "http://localhost:8084/getKey";

                $res = $client->post($url, [
                    'headers' => $headers,
                    'body' => json_encode($body),
                ]);
                $details = json_decode($res->getBody(), true);
                if (isset($details['privateKey'])) {
                    $gasprice = $this->getGas();
                    $price_num = \DB::select('select (' . $request->amount . '-0.000084) * 1000000000000000000');
                    $arr = json_decode(json_encode($price_num[0]), true);
                    $final_num = current($arr);

                    $price = \DB::select('select CONV(' . $final_num . ',10,16)');
                    $arr = json_decode(json_encode($price[0]), true);
                    $price = '0x' . current($arr);
                    $headers = [];
                    $ch = curl_init();
                    $params = array(
                        "method" => "create_rawtx",
                        "formaddr" => $user->eth_address,
                        "pvk" => $details['privateKey'],
                        "toddr" => $request->to_address,
                        "amount" => $request->amount,
                        "gasPrice" => $gasprice['gasfee'],
                        "url" => "https://mainnet.infura.io/v3/9a362ef8feb14299943089c1f563077e",
                    );
                    curl_setopt($ch, CURLOPT_URL, "http://localhost:3005/sendEther");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                    curl_close($ch);
                    $result = json_decode($result);
                    if (isset($result->error)) {
                        return response()->json(['status'=>'Failed','message' => "Transaction Failed"], 200);
                    } else {
                        $curldata['result'] = $result->txid;
                    }
                } else {
                    return response()->json(['status'=>'Failed','message' => "Transaction Failed"], 200);
                }
            } elseif ($user->network == 'ECpay') {
                
                // Get user eth balance ///
                $client = new Client();
                $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=balance&address=' . $user->eth_address.'&apikey=SRHNYU6D81WRIC2BJGQFVZKF2A67WMFQHJ');
                $coindetails = json_decode($coindetails->getBody(), true);
                $amount = $coindetails['result'] / 1000000000000000000;  //User's ETH balance
                // Send Fee (ETH) to user account.
                $data = $this->sendFees($user, $request->to_address, $amount, $request->amount);
                if ($data['hasbal']) {  //User has eth balance so straight away we can send ECpay token //
                    //sleep((int)($data['waittime']*60) + 10);
                    $client = new Client();
                    $headers = [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ];
                    $body = ["jsonrpc" => "2.0", "method" => "getPrivateKey", "params" => array("method" => "getPrivateKey", "address" => $user->eth_address, "password" => $user->email)];
                    $url = "http://localhost:8084/getKey";
                    $res = $client->post($url, [
                        'headers' => $headers,
                        'body' => json_encode($body),
                    ]);

                    $details = json_decode($res->getBody(), true);
                    if (isset($details['privateKey'])) {
                        $ch = curl_init();
                        $params = array(
                            "method" => "create_rawecpaytoken",
                            "formaddr" => $user->eth_address,
                            "pvk" => $details['privateKey'],
                            "toddr" => $request->to_address,
                            "amount" => $request->amount * 1000000000000,
                            "url" => "https://mainnet.infura.io/v3/9a362ef8feb14299943089c1f563077e",
                            "gasprice"=> $data['gasPrice'],
                        );
                        curl_setopt($ch, CURLOPT_URL, "http://localhost:8110");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                        $headers = array();
                        $headers[] = "Content-Type : application/json";
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        $result = curl_exec($ch);
                        if (curl_errno($ch)) {
                            echo 'Error:' . curl_error($ch);
                        }
                        curl_close($ch);
                        $result = json_decode($result);
                        if (isset($result->error)) {
                            return response()->json(['status'=>'Failed','message' => "ECpay Transaction Failed."], 200);
                        } else {
                            $curldata['result'] = $result->txid;
                        }
                    } else {  // private key not taken
                        return response()->json(['status'=>'Failed','message' => "Transaction Failed."], 200);
                    }
                } elseif ($data['feesent']) {
                    return response()->json(['status'=>'Failed','message' => "ECpay send in progress..., will be completed within 5 mins."], 200);
                } elseif (isset($data['lowfee']) && $data['lowfee']) {    // Required fee not available
                    return response()->json(['status'=>'Failed','message' => "Transaction Failed. Try after some time."], 200);
                } else {
                    return response()->json(['status'=>'Failed','message' => "Transaction Failed."], 200);
                }
            } elseif ($user->network == 'XRP') {
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                ];
                $dest_tag = mt_rand(100000000, 999999999);
                $price = $request->amount;
                $xrp_temp_key = $user->x_remember_flag_port . $user->x_remember_flag_star;
                $xrp_key = $this->simple_crypt($xrp_temp_key, 'decrypt');
                $xrp_data = $price . ":" . $user->xrp_address . ":" . $request->to_address . ":" . $dest_tag . ":" . $xrp_key;
                $body = $xrp_data;
                $send_temp_xrp = $client->get('http://localhost:8081/passparams?key=' . $body);
                $send_xrp = json_decode($send_temp_xrp->getBody(), true);
                if (isset($send_xrp['id'])) {
                    $curldata['result'] = $send_xrp['id'];
                } else {
                    return response()->json(['error' => "Transaction Failed"], 500);
                }
            }
            if (isset($curldata['result'])) {
                $txn_hash = $curldata['result'];
                $maildata = [
                    'type' => $user->network,
                    'sender' => $user->address,
                    'receiver' => $request->to_address,
                    'amount' => $request->amount,
                    'coin_symbol' => Setting::set('currency_symbol'),
                ];
                $receiver = User::where('address', $request->to_address)->first();
                $txn = new Transaction();
                $txn->type = $user->network;
                $txn->sender = $user->id;
                if ($receiver) {
                    $txn->receiver = $receiver->id;
                    $receiver_email = $receiver->email;
                    Mail::to($receiver_email)->send(new Receivetranscationmail($maildata));
                } else {
                    $txn->receiver = 0;
                }
                $txn->txn_hash = $txn_hash;
                $txn->sender_address = $user->address;
                $txn->receiver_address = $request->to_address;
                $txn->amount = $request->amount;
                $txn->fiat_currency = $user->fiat_currency;
                $txn->save();
                $sender_email = $user->email;
                $receiver_email = $receiver;
                Mail::to($sender_email)->send(new Sendtranscationmail($maildata));
                if ($user->push_note_status == 1) {
                    //(new SendPushNotification)->SendTransaction($user->id,$request->amount);
                    //(new SendPushNotification)->ReceiveTransaction($receiver->id,$request->amount);
                }
                return response()->json(['status' => "success",'message' => "Coin Sent Successfully !"], 200);
            } else {
                return response()->json(['status' => "failed",'message' => "Transaction Failed"], 200);
            }
        } catch (Exception $e) {
            //return back()->with('flash_error', 'Send coin exception !');
            return response()->json(['status' => "Send coin exception !"], 200);
        }
    }

    public function history(Request $request)
    {
        try {
            $details = array();
            $history = array();
            $history_tmp = array();
            $list_transaction = [];

            $user = Auth::user();
            $ownAddress = $user->btc_address;
            $name = $user->email;

            $param = [strtolower($name), 50, 0];
            $body = [
                'params' => $param,
                'method' => 'listtransactions',
            ];

            if ($user->network == 'LIO') {
                $curldata = $this->npmcurl($body);

                if (isset($curldata['result'])) {
                    $details = $curldata['result'];
                    $time  = array_column($details, 'time');
                    array_multisort($time, SORT_DESC, $details);
                    foreach ($details as $value) {
                        $history_tmp = [
                            'category' => $value['category'],
                            'amount' => $value['amount'],
                            'txid' => $value['txid'],
                            'time' => $value['time'],
                            'network' => 'LIO',
                        ];
                        array_push($history, $history_tmp);
                    }
                }

                $curldata['result'] = $history;
            } elseif ($user->network == 'BTC') {
                $history = $this->btctransactions($user->btc_address);
                $curldata['result'] = $history;
            } elseif ($user->network == 'ETH') {
                $ethaddress = $user->eth_address;

                $client = new Client;
                $coindetails = $client->get('http://api.etherscan.io/api?module=account&action=txlist&address=' . $ethaddress . '&startblock=0&endblock=99999999&sort=desc&apikey=SRHNYU6D81WRIC2BJGQFVZKF2A67WMFQHJ');
                $result = json_decode($coindetails->getBody(), true);
                if (isset($result['result'])) {
                    $result = $result['result'];
                    foreach ($result as $index => $results) {
                        $category = "receive";

                        if ($results['from'] == $user->eth_address) {
                            $category = "sent";
                        }
                        $history_tmp = [
                        'txid' => $results['hash'],
                        'amount' => $results['value'] / 1000000000000000000,
                        'category' => $category,
                        'time' => $results['timeStamp'],
                        'network' => 'ETH',
                        ];

                        array_push($history, $history_tmp);
                    }
                }
                $curldata['result'] = $history;
            } elseif ($user->network == 'ECpay') {
                $ethaddress = $user->eth_address;
                $contract_address = '0x3b0D6B5F04C1A70a661F9EF32992f9e2C670ae7A';

                $client = new Client;

                $coindetails = $client->get('https://api.etherscan.io/api?module=account&sort=desc&action=tokentx&contractaddress=' . $contract_address . '&address=' . $ethaddress.'&apikey=SRHNYU6D81WRIC2BJGQFVZKF2A67WMFQHJ');

                $result = json_decode($coindetails->getBody(), true);
                if (isset($result['result'])) {
                    $result = $result['result'];

                    foreach ($result as $index => $results) {
                        $category = "receive";

                        if ($results['from'] == $user->eth_address) {
                            $category = "sent";
                        }

                        $history_tmp = [
                        'txid' => $results['hash'],
                        'amount' => number_format($results['value'] / 1000000000000, 8, '.', ''), //bcmul(1,$results['value'] / 1000000000000,8),
                        'category' => $category,
                        'time' => $results['timeStamp'],
                        'network' => 'ECpay',
                        ];
                        array_push($history, $history_tmp);
                    }
                }
                $curldata['result'] = $history;
            } elseif ($user->network == 'XRP') {
                $xrp_address = $user->xrp_address;

                $xrp_url = 'http://s2.ripple.com:51234';
                $headers = [
                    'Content-Type' => 'application/json',
                ];
                $param = ["account" => $xrp_address, "ledger_index_min" => -1, "ledger_index_max" => -1, "binary" => false, "limit" => 2, "forward" => false];

                $body = ["method" => "account_tx", "params" => [$param]];
                $client = new Client;
                $res = $client->post($xrp_url, [
                    'headers' => $headers,
                    'body' => json_encode($body),
                ]);
                $xrp_trans = json_decode($res->getBody(), true);
                if (isset($xrp_trans['result'])) {
                    $details = $xrp_trans['result'];
                    foreach ($details['transactions'] as $valuetx) {
                        $value = $valuetx['tx'];
                        $category = "receive";
                        if ($value['Account'] == $user->xrp_address) {
                            $category = "sent";
                        }
                        $history_tmp = [
                            'category' => $category,
                            'amount' => $value['Amount'] / 1000000,
                            'txid' => $value['hash'],
                            'time' => $value['date'] + 946684800,
                            'network' => 'XRP',
                        ];
                        array_push($history, $history_tmp);
                    }
                }
                $time  = array_column($history, 'time');
                array_multisort($time, SORT_DESC, $history);
                $curldata['result'] = $history;
            }

            if (isset($curldata['result'])) {
                $details = $curldata['result'];
            }

            return response()->json(['details' => $details], 200);
        } catch (Exception $e) {
            echo "Message : ". $e->getMessage();
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function receive(Request $request)
    {
        try {
            $address = Auth::user()->address;
            if ($request->ajax()) {
                return response()->json(['address' => $address], 200);
            } else {
                return view('receive', compact('address'));
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function signtest()
    {
        $email = "demobhoopathi@gmail.com";
        $param = [$email];

        $body = [
            'params' => $param,
            'method' => 'getaccountaddress',
        ];
        return $curldata1 = $this->npmcurl($body);
    }

    public function npmcurl($body)
    {

       // try {
        $id = 0;
        $status = null;
        $error = null;
        $raw_response = null;
        $response = null;

        $proto = 'http';

        /*$username = 'liorpc';
        $password = 'E8mnS4yo97Ue3Jazb5VPh6HpM2sWhRgK3iyfn3HimTZv';
        $host = '127.0.0.1';
        $port = '8668';*/

        $username = 'liorpc';
        $password = 'DyMu9mCfq7vkiZP4HTTNoZDadohx1TyhmnPxrzt2Mpuf';
        $host = '66.228.54.158';
        $port = '8668';

        $url = '';
        $CACertificate = null;
        $method = $body['method'];
        // If no parameters are passed, this will be an empty array
        $params = $body['params'];
        $params = array_values($params);
        // The ID should be unique for each call
        $id++;
        // Build the request, it's ok that params might have any empty array
        $request = json_encode(array(
                'method' => $method,
                'params' => $params,
                'id' => $id,
            ));

        //$curl    = curl_init("{$proto}://{$host}:{$port}/{$url}");
        $curl = curl_init("{$proto}://{$host}:{$port}/");
        $options = array(
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => $username . ':' . $password,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_HTTPHEADER => array('Content-type: application/json'),
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $request,
            );
        // This prevents users from getting the following warning when open_basedir is set:
        // Warning: curl_setopt() [function.curl-setopt]:
        //   CURLOPT_FOLLOWLOCATION cannot be activated when in safe_mode or an open_basedir is set
        if (ini_get('open_basedir')) {
            unset($options[CURLOPT_FOLLOWLOCATION]);
        }

        if ($proto == 'https') {
            // If the CA Certificate was specified we change CURL to look for it
            if (!empty($CACertificate)) {
                $options[CURLOPT_CAINFO] = $CACertificate;
                $options[CURLOPT_CAPATH] = DIRNAME($CACertificate);
            } else {
                // If not we need to assume the SSL cannot be verified
                // so we set this flag to FALSE to allow the connection
                $options[CURLOPT_SSL_VERIFYPEER] = false;
            }
        }
        curl_setopt_array($curl, $options);
        // Execute the request and decode to an array
        $raw_response = curl_exec($curl);
        $response = json_decode($raw_response, true);
        // If the status is not 200, something is wrong
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // If there was no error, this will be an empty string
        $curl_error = curl_error($curl);
        curl_close($curl);
        if (!empty($curl_error)) {
            $error = $curl_error;
        }
        if ($response['error']) {
            // If EINR returned an error, put that in $error
            $error = $response['error']['message'];
        } elseif ($status != 200) {
            // If EINR didn't return a nice error message, we need to make our own
            switch ($status) {
                    case 400:
                        $error = 'HTTP_BAD_REQUEST';
                        break;
                    case 401:
                        $error = 'HTTP_UNAUTHORIZED';
                        break;
                    case 403:
                        $error = 'HTTP_FORBIDDEN';
                        break;
                    case 404:
                        $error = 'HTTP_NOT_FOUND';
                        break;
                }
        }

        if ($error!='') {
            return $error;
            //return false;
        }
        return $response;
        //return $response['result'];
        // } catch (Exception $e) {
        // }
    }

    //curl to hit bitcoin server

    public function bitcoin_npmcurl($body)
    {
        try {
            $id = 0;
            $status = null;
            $error = null;
            $raw_response = null;
            $response = null;

            $proto = "http";
            $username = "liowaluser";
            $password = "YqMhMRxoLh93";
            $host = "127.0.0.1";
            $port = "8332"; //"8222";
            $url = '';
            $CACertificate = null;
            $method = $body['method'];
            // If no parameters are passed, this will be an empty array
            $params = $body['params'];
            $params = array_values($params);
            // The ID should be unique for each call
            $id++;
            // Build the request, it's ok that params might have any empty array
            $request = json_encode(array(
                'jsonrpc' => "1.0",
                'method' => $method,
                'params' => $params,
                'id' => "curltest",
            ));
            //$curl    = curl_init("{$proto}://{$host}:{$port}/{$url}");
            $curl = curl_init("{$proto}://{$host}:{$port}/");
            $options = array(
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => $username . ':' . $password,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_HTTPHEADER => array('Content-type: application/json'),
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $request,
            );
            // This prevents users from getting the following warning when open_basedir is set:
            // Warning: curl_setopt() [function.curl-setopt]:
            //   CURLOPT_FOLLOWLOCATION cannot be activated when in safe_mode or an open_basedir is set
            if (ini_get('open_basedir')) {
                unset($options[CURLOPT_FOLLOWLOCATION]);
            }

            if ($proto == 'https') {
                // If the CA Certificate was specified we change CURL to look for it
                if (!empty($CACertificate)) {
                    $options[CURLOPT_CAINFO] = $CACertificate;
                    $options[CURLOPT_CAPATH] = DIRNAME($CACertificate);
                } else {
                    // If not we need to assume the SSL cannot be verified
                    // so we set this flag to FALSE to allow the connection
                    $options[CURLOPT_SSL_VERIFYPEER] = false;
                }
            }
            curl_setopt_array($curl, $options);
            // Execute the request and decode to an array
            $raw_response = curl_exec($curl);
            $response = json_decode($raw_response, true);
            // If the status is not 200, something is wrong
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // If there was no error, this will be an empty string
            $curl_error = curl_error($curl);
            curl_close($curl);
            if (!empty($curl_error)) {
                $error = $curl_error;
            }
            if ($response['error']) {
                // If EINR returned an error, put that in $error
                $error = $response['error']['message'];
            } elseif ($status != 200) {
                // If EINR didn't return a nice error message, we need to make our own
                switch ($status) {
                    case 400:
                        $error = 'HTTP_BAD_REQUEST';
                        break;
                    case 401:
                        $error = 'HTTP_UNAUTHORIZED';
                        break;
                    case 403:
                        $error = 'HTTP_FORBIDDEN';
                        break;
                    case 404:
                        $error = 'HTTP_NOT_FOUND';
                        break;
                }
            }
            if ($error) {
                print_r($error);
                return $error;
            }
            return $response;
        } catch (Exception $e) {
            echo $e->getMessage();
            return  $e;
        }
    }

    public function litecoin_npmcurl($body)
    {
        try {
            $id = 0;
            $status = null;
            $error = null;
            $raw_response = null;
            $response = null;

            //curl --user bvfK73aaKEMVSrPUmTeqDjS:UZpK7sjSc78E6ggj5RdZS4D --data-binary '{"jsonrpc": "1.0", "id":"curltest", "method": "getnewaddress", "params": [] }' -H 'content-type: text/plain;' http://142.93.194.154:19344/
            //{"result":"MH9diy2sRhSd8WGfHuN6mw5pGcru2G7d1Z","error":null,"id":"curltest"}

            $proto = "http";
            $username = "TthlwaXDOvtybYbt";
            $password = "bitdlwdwmsdlwdw";
            $host = "18.191.223.5";
            $port = "19344";
            $url = '';
            $CACertificate = null;
            $method = $body['method'];
            // If no parameters are passed, this will be an empty array
            $params = $body['params'];
            $params = array_values($params);
            // The ID should be unique for each call
            $id++;
            //$id = "curltest";
            // Build the request, it's ok that params might have any empty array
            $request = json_encode(array(
                'jsonrpc' => "1.0",
                'method' => $method,
                'params' => $params,
                'id' => "curltest",
            ));
            //$curl    = curl_init("{$proto}://{$host}:{$port}/{$url}");
            $curl = curl_init("{$proto}://{$host}:{$port}/");
            //dd($curl);
            $options = array(
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => $username . ':' . $password,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_HTTPHEADER => array('Content-type: application/json'),
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $request,
            );

            //dd($options);
            // This prevents users from getting the following warning when open_basedir is set:
            // Warning: curl_setopt() [function.curl-setopt]:
            //   CURLOPT_FOLLOWLOCATION cannot be activated when in safe_mode or an open_basedir is set
            if (ini_get('open_basedir')) {
                unset($options[CURLOPT_FOLLOWLOCATION]);
            }

            if ($proto == 'https') {
                // If the CA Certificate was specified we change CURL to look for it
                if (!empty($CACertificate)) {
                    $options[CURLOPT_CAINFO] = $CACertificate;
                    $options[CURLOPT_CAPATH] = DIRNAME($CACertificate);
                } else {
                    // If not we need to assume the SSL cannot be verified
                    // so we set this flag to FALSE to allow the connection
                    $options[CURLOPT_SSL_VERIFYPEER] = false;
                }
            }
            curl_setopt_array($curl, $options);
            // Execute the request and decode to an array
            $raw_response = curl_exec($curl);

            //dd($raw_response);
            $response = json_decode($raw_response, true);

            // If the status is not 200, something is wrong
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // If there was no error, this will be an empty string
            $curl_error = curl_error($curl);
            curl_close($curl);
            if (!empty($curl_error)) {
                $error = $curl_error;
            }
            if ($response['error']) {
                // If EINR returned an error, put that in $error
                $error = $response['error']['message'];
            } elseif ($status != 200) {
                // If EINR didn't return a nice error message, we need to make our own
                switch ($status) {
                    case 400:
                        $error = 'HTTP_BAD_REQUEST';
                        break;
                    case 401:
                        $error = 'HTTP_UNAUTHORIZED';
                        break;
                    case 403:
                        $error = 'HTTP_FORBIDDEN';
                        break;
                    case 404:
                        $error = 'HTTP_NOT_FOUND';
                        break;
                }
            }
            if ($error) {
                return $error;
            }
            // dd($response);
            return $response;
        } catch (Exception $e) {
        }
    }

    /** bitcoincash_npmcurl  **/
    public function bitcoincash_npmcurl($body)
    {
        try {
            $id = 0;
            $status = null;
            $error = null;
            $raw_response = null;
            $response = null;

            //curl --user user123456:pwd123456 --data-binary '{"jsonrpc": "1.0", "id":"curltest", "method": "getnewaddress", "params": [] }' -H 'content-type: text/plain;' http://204.48.18.111:19992/

            $proto = "http";
            $username = "user123456";
            $password = "pwd123456";
            $host = "204.48.18.111";
            $port = "19992";
            $url = '';
            $CACertificate = null;
            $method = $body['method'];
            // If no parameters are passed, this will be an empty array
            $params = $body['params'];
            $params = array_values($params);
            // The ID should be unique for each call
            $id++;
            //$id = "curltest";
            // Build the request, it's ok that params might have any empty array
            $request = json_encode(array(
                'jsonrpc' => "1.0",
                'method' => $method,
                'params' => $params,
                'id' => "curltest",
            ));
            //$curl    = curl_init("{$proto}://{$host}:{$port}/{$url}");
            $curl = curl_init("{$proto}://{$host}:{$port}/");
            //dd($curl);
            $options = array(
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => $username . ':' . $password,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_HTTPHEADER => array('Content-type: application/json'),
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $request,
            );

            //dd($options);
            // This prevents users from getting the following warning when open_basedir is set:
            // Warning: curl_setopt() [function.curl-setopt]:
            //   CURLOPT_FOLLOWLOCATION cannot be activated when in safe_mode or an open_basedir is set
            if (ini_get('open_basedir')) {
                unset($options[CURLOPT_FOLLOWLOCATION]);
            }

            if ($proto == 'https') {
                // If the CA Certificate was specified we change CURL to look for it
                if (!empty($CACertificate)) {
                    $options[CURLOPT_CAINFO] = $CACertificate;
                    $options[CURLOPT_CAPATH] = DIRNAME($CACertificate);
                } else {
                    // If not we need to assume the SSL cannot be verified
                    // so we set this flag to FALSE to allow the connection
                    $options[CURLOPT_SSL_VERIFYPEER] = false;
                }
            }
            curl_setopt_array($curl, $options);
            // Execute the request and decode to an array
            $raw_response = curl_exec($curl);

            //dd($raw_response);
            $response = json_decode($raw_response, true);

            // If the status is not 200, something is wrong
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // If there was no error, this will be an empty string
            $curl_error = curl_error($curl);
            curl_close($curl);
            if (!empty($curl_error)) {
                $error = $curl_error;
            }
            if ($response['error']) {
                // If EINR returned an error, put that in $error
                $error = $response['error']['message'];
            } elseif ($status != 200) {
                // If EINR didn't return a nice error message, we need to make our own
                switch ($status) {
                    case 400:
                        $error = 'HTTP_BAD_REQUEST';
                        break;
                    case 401:
                        $error = 'HTTP_UNAUTHORIZED';
                        break;
                    case 403:
                        $error = 'HTTP_FORBIDDEN';
                        break;
                    case 404:
                        $error = 'HTTP_NOT_FOUND';
                        break;
                }
            }
            if ($error) {
                return $error;
            }
            // dd($response);
            return $response;
        } catch (Exception $e) {
        }
    }

    public function validateG2fa(Request $request)
    {
        $user = $request->user();
        // // //encrypt and then save secret
        $user->google2fa_secret = $request->secret;
        $user->save();
        (new LoginController)->postValidateToken($request);
    }

    public function forgot_password(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
        ]);
        try {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                //$otp = mt_rand(100000, 999999);
                //$user->otp = $otp;
                //Notification::send($user, new ResetPasswordOTP($otp));

                $email_token = app('auth.password.broker')->createToken($user);
                $user->email_token = $email_token;
                $user->save();

                Notification::send($user, new AccountResetPassword($email_token));

                return response()->json([
                    'message' => 'Reset link has sent to your email!',
                    'user' => $user,
                ]);
            } else {
                return response()->json(['error' => "Invalid mail address, Please Check at once.."], 500);
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function support()
    {
        try {
            $contact_number = Setting::get('contact_no');
            $contact_mail = Setting::get('contact_email');
            $website = Setting::get('contact_website');

            return response()->json(['contact_number' => $contact_number, 'contact_mail' => $contact_mail, 'website' => $website], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function appuserpin(Request $request)
    {
        try {
            $id = Auth::user()->id;

            $user = User::findOrFail($id);

            if ($request->app_pin_status == 1) { //Pin Enable
                $user->app_pin = $request->app_pin;
                $user->app_pin_status = $request->app_pin_status;
                $msg = "Pin Enabled Successfully";
            } else { //Pin Disable
                $user->app_pin = 0;
                $user->app_pin_status = 0;
                $msg = "Pin Disabled Successfully";
            }

            $user->save();

            return response()->json(['message' => $msg], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function apppushnote(Request $request)
    {
        try {
            $id = Auth::user()->id;

            $user = User::findOrFail($id);

            if ($request->push_note_status == 1) { //Pin Enable
                $user->push_note_status = $request->push_note_status;
                $msg = "Push Notification Enabled Successfully";
            } else { //Pin Disable
                $user->push_note_status = 0;
                $msg = "Push Notification Disabled Successfully";
            }

            $user->save();

            return response()->json(['status' => $user->push_note_status, 'message' => $msg], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function help_details(Request $request)
    {
        try {
            if ($request->ajax()) {
                return response()->json([
                    'contact_number' => Setting::get('contact_number', ''),
                    'contact_mail' => Setting::get('contact_mail', ''),
                    'website' => Setting::get('website', ''),
                ]);
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    public function currencylist()
    {
        try {
            $currency = Currency::get()->toArray();
            return response()->json(['currency' => $currency], 200);
        } catch (Exception $e) {
            //echo 'Message: ' .$e->getMessage();
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function selectcurrency($fiat)
    {
        try {
            $fiat = strtoupper($fiat);

            $user = Auth::user();
            $user->fiat_currency = $fiat;
            $user->save();

            return response()->json(['status' => "success", 'message' => "Your choice of currency updated successfully"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function selectlanguage(Request $request)
    {
        try {
            $user = Auth::user();

            $user->language = $request->language;

            $user->save();

            return response()->json(['status' => "success", 'message' => "Your choice of language updated successfully"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function selectcoin(Request $request)
    {
        try {
            $user = Auth::user();
            $n = explode(',', $user->coin_types);
            $temp = [];

            if ($request->selected == 1) {
                array_push($n, $request->id);
            } else {
                if (($key = array_search($request->id, $n)) !== false) {
                    unset($n[$key]);
                }
            }

            if (count($n) > 0) {
                $coin_id = implode(',', $n);
                $user->coin_types = $coin_id;
            } else {
                $user->coin_types = 4;
            }
            $user->save();

            return response()->json(['status' => "success", 'message' => "Your choice of coins updated successfully"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function cryptocurrencylist()
    {
        try {
            $cointype = CoinType::where('status', '1')->get()->toArray();

            $user = Auth::user();

            $coin_selected = explode(',', $user->coin_types);

            return response()->json(['cointype' => $cointype, 'coin_selected' => $coin_selected], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function keygen()
    {
        $user = Auth::user();
        $xrp_temp_key = $user->x_remember_flag_port . $user->x_remember_flag_star;
        $xrp_key = $this->simple_crypt($xrp_temp_key, 'encrypt');
        $user->x_remember_flag_port = substr($xrp_key, 0, 10);
        $user->x_remember_flag_star = substr($xrp_key, 10);
        $user->save();
    }

    public function simple_crypt($string, $action = 'encrypt')
    {
        $key = "YMoEtIgr#W&Ab7uu3mlZeanIMr";

        $res = '';
        if ($action !== 'encrypt') {
            $string = base64_decode($string);
        }
        for ($i = 0; $i < strlen($string); $i++) {
            $c = ord(substr($string, $i));
            if ($action == 'encrypt') {
                $c += ord(substr($key, (($i + 1) % strlen($key))));
                $res .= chr($c & 0xFF);
            } else {
                $c -= ord(substr($key, (($i + 1) % strlen($key))));
                $res .= chr(abs($c) & 0xFF);
            }
        }
        if ($action == 'encrypt') {
            $res = base64_encode($res);
        }
        return $res;
    }

    public function sendbtc($bodyp)
    {
        // bitcoin_npmcurl
        //$param = [$name, $from_address, $request->to_address, $request->amount];

        // private key block starts
        $param = ['from_address' => $bodyp['params'][1]];
        $body = [
            'params' => $param,
            'method' => 'dumpprivkey',
        ];
        $res = $this->bitcoin_npmcurl($body);
        $private_key = $res['result'];

        /// private key block ends

        // unspent key block starts
        $param = [1, 9999999,[$bodyp['params'][1]]];
        $body = [
            'params' => $param,
            'method' => 'listunspent',
        ];
        $res = $this->bitcoin_npmcurl($body);
        $utxos = $res['result'];
        /// unspent key block ends
        if (!empty($utxos)) {
            $balance = self::txbalance($utxos);
            $total_send = bcadd($bodyp['params'][3], 0.0001, 8);
            if ($this->balance >= $total_send) {
                $newbalance = (float)bcsub($balance, $total_send, 8);
                if ($newbalance >0) {
                    $tx = array(
                        $bodyp['params'][2] => (float)$bodyp['params'][3],
                        $bodyp['params'][1] => $newbalance
                    );
                } else {
                    $tx = array(
                        $bodyp['params'][2] => (float)$bodyp['params'][3]
                    );
                }

                $rawtx = self::createrawtransaction($utxos, $tx);
                if ($rawtx) {
                    $hex = self::signrawtransaction($rawtx, $utxos, [$private_key]);
                    if ($hex) {
                        $tx = self::sendrawtransaction($hex);
                        if ($tx) {
                            return $tx;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        } else {
            return "No balance in your account";
        }
    }

    private function txbalance($utxos)
    {
        $this->balance = 0;
        if (!empty($utxos)) {
            foreach ($utxos as $utxo) {
                $this->balance = bcadd($this->balance, $utxo['amount'], 8);
            }
        }
        return $this->balance;
    }

    private function createrawtransaction($utxo, $tx)
    {
        if (!empty($utxo) && !empty($tx)) {

            // raw transaction block starts
            $param = [$utxo, $tx];
            $body = [
                'params' => $param,
                'method' => 'createrawtransaction',
            ];
            $res = $this->bitcoin_npmcurl($body);
            $rawtx = $res['result'];
            if (!empty($rawtx)) {
                return $rawtx;
            } else {
                return false;
            }
            /// raw transaction block ends
        } else {
            return false;
        }
    }

    private function signrawtransaction($rawtx, $utxos, $pvkey)
    {
        if (!empty($rawtx) && !empty($utxos) && !empty($pvkey)) {
            // signrawtransaction block starts
            $param = [$rawtx, $utxos, $pvkey];
            $body = [
                'params' => $param,
                'method' => 'signrawtransaction',
            ];
            $res = $this->bitcoin_npmcurl($body);
            $hex = $res['result'];
            if ($hex['hex']) {
                return $hex['hex'];
            } else {
                return false;
            }
        }
        // signrawtransaction block ends
    }

    private function sendrawtransaction($hex)
    {
        if (!empty($hex)) {
            $param = [$hex];
            $body = [
                'params' => $param,
                'method' => 'sendrawtransaction',
            ];
            $res = $this->bitcoin_npmcurl($body);
            $txid = $res['result'];
            if (!empty($txid)) {
                return $res;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function btctransactions($address)
    {
        $url = "https://insight.bitpay.com/api/txs/?address=$address";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $headers = array();
        $headers[] = "Accept: application/json, text/plain";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if (curl_errno($ch)) {
            echo $result = 'Error:' . curl_error($ch);
        } else {
            $result = curl_exec($ch);
        }
        curl_close($ch);
        $result= json_decode($result);
        $fresult = [];
        if ($result->pagesTotal > 1) {
            for ($i=0; $i < $result->pagesTotal;$i++) {
                $url = "https://insight.bitpay.com/api/txs/?address=$address&pageNum=$i";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $headers = array();
                $headers[] = "Accept: application/json, text/plain";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                if (curl_errno($ch)) {
                    echo $result = 'Error:' . curl_error($ch);
                } else {
                    $result = curl_exec($ch);
                }
                curl_close($ch);
                $result = json_decode($result);

                if (isset($result->txs) && count($result->txs) > 0) {
                    foreach ($result->txs as $transaction) {
                        $temp= [];
                        $temp['txid']    = $transaction->txid;
                        $temp['sender']     = $transaction->vin[0]->addr;
                        // $temp['confirm']    = $transaction->confirmations;
                        // $temp['fees']       = $transaction->fees;
                        $temp['time']       = $transaction->time;
                        $temp['network'] = 'BTC';
                        foreach ($transaction->vin as $vin) {
                            if (isset($vin->addr) && $vin->addr === $address) {
                                break;
                            }
                        }
                        foreach ($transaction->vout as $vout) {
                            if (isset($vin->addr) && $vin->addr === $address) {
                                if (isset($vout->scriptPubKey->addresses) && !in_array($address, $vout->scriptPubKey->addresses)) {
                                    $temp['receiver'] = $vout->scriptPubKey->addresses[0];
                                    $temp['amount'] = number_format($vout->value, 8, '.', ''); //bcmul(-1,$vout->value,8);
                                    $temp['category'] = "send";
                                    break;
                                }
                            } else {
                                if (isset($vout->scriptPubKey->addresses) && in_array($address, $vout->scriptPubKey->addresses)) {
                                    $temp['receiver'] = $address;
                                    $temp['amount'] = number_format($vout->value, 8, '.', ''); //bcmul(1,$vout->value,8);
                                    $temp['category'] = "receive";
                                    break;
                                }
                            }
                        }
                        unset($temp['receiver']);
                        unset($temp['sender']);
                        $fresult[] = $temp;
                    }
                }
            }
        } else {
            if (isset($result->txs) && count($result->txs) > 0) {
                foreach ($result->txs as $transaction) {
                    $temp= [];
                    $temp['txid']    = $transaction->txid;
                    $temp['sender']     = $transaction->vin[0]->addr;
                    // $temp['confirm']    = $transaction->confirmations;
                    // $temp['fees']       = $transaction->fees;
                    $temp['time']       = $transaction->time;
                    $temp['network'] = 'BTC';
                    foreach ($transaction->vin as $vin) {
                        if (isset($vin->addr) && $vin->addr === $address) {
                            break;
                        }
                    }
                    foreach ($transaction->vout as $vout) {
                        if (isset($vin->addr) && $vin->addr === $address) {
                            if (isset($vout->scriptPubKey->addresses) && !in_array($address, $vout->scriptPubKey->addresses)) {
                                $temp['receiver'] = $vout->scriptPubKey->addresses[0];
                                $temp['amount'] = number_format($vout->value, 8, '.', ''); //bcmul(-1,$vout->value,8);
                                $temp['category'] = "send";
                                break;
                            }
                        } else {
                            if (isset($vout->scriptPubKey->addresses) && in_array($address, $vout->scriptPubKey->addresses)) {
                                $temp['receiver'] = $address;
                                $temp['amount'] = number_format($vout->value, 8, '.', ''); //bcmul(1,$vout->value,8);
                                $temp['category'] = "receive";
                                break;
                            }
                        }
                    }
                    unset($temp['receiver']);
                    unset($temp['sender']);
                    $fresult[] = $temp;
                }
            }
        }
        return $fresult;
    }

    public function exec_cUrls($url, $postfilds=null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if (!is_null($postfilds)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfilds);
        }
        if (strpos($url, '?') !== false) {
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        $headers = array('Content-Length: 0');
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if (curl_errno($ch)) {
            $result = 'Error:' . curl_error($ch);
        } else {
            $result = curl_exec($ch);
        }
        curl_close($ch);
        return json_decode($result, true);
    }

    /// News section Update ///
    public function newslist()
    {
        $news= News::where('status', 1)->orderBy('id', 'DESC')->get();
        return response()->json(['news' => $news], 200);
    }

    public function newsitem($id)
    {
        $news=News::findOrFail($id);
        return response()->json(['news' => $news], 200);
    }

    /**
      * send fee from admin account.
      * @param [object] user
      * @param  [string] address
      * @param  [float] fee
      * @return boolean
    */
    //$user,$request->to_address,$amount,$request->amount
    public function sendFees($user, $address, $userbal, $amt)
    {
        $setting = Setting::where('key','muthal_paguthi')->where('key','pir_paguthi')->get();
        $kadavusol = Crypt::decrypt($setting[0]['key']).Crypt::decrypt($setting[1]['key']);
        /// Get dynamic live gas price and calculate fee and send to customer account ///
        $gasprice = $this->getLivePrice();
        $gasfee = $gasprice['gasfee'];
        $fee = (double)$gasfee * (double) 36325 / (double)1000000000000000000;
    
        if ($userbal > $fee) {
            $data['fee'] = $fee;
            $data['gasPrice'] = $gasfee;
            $data['waittime'] = '';
            $data['feesent'] = 0;
            $data['hasbal'] = 1;
            return $data;
        }
        /// check eth available in fee wallet //
        $client = new Client;
        $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=balance&address=0x57C4716a38A3e711b9501f1Cf684ac2F45EdBE46&apikey=SRHNYU6D81WRIC2BJGQFVZKF2A67WMFQHJ');
        $coindetails = json_decode($coindetails->getBody(), true);
        $amount1 = $coindetails['result'] / 1000000000000000000;
    
        /// If eth balance is less than the fee required ECpay not allowed to send so we set transferable amount to zero.
        if ($amount1 < $fee) {
            $feeavilablle = $amount1;
            // Send mail to admin
            $html = "<h3>Hello Admin,</h3> <br /> <p>Fee wallet not having enough balance. Balance available is ".number_format($feeavilablle, 8, '.', '')." ETH, but you need ".$fee." ETH. Pleae fill so that user can send ECpay.</p> <br/ > Lio Admin";
            Mail::raw('Hello,', function ($message) use ($html) {
                $message
            // ->to('support@lio-coin.com')
            ->to('arunmozhi.pixel@gmail.com')
            ->subject('Fill ECpay Fee Wallet')
            ->setBody($html, 'text/html');
            });
            $data['fee'] = $fee;
            $data['gasPrice'] = 0;
            $data['waittime'] = '';
            $data['feesent'] = 0;
            $data['hasbal'] = 0;
            $data['lowfee'] = 1;
            return $data;
        } /// Send fee (ETH) to user's address ///
        /// before send fee check previous trans pending ///
        $sendecpay = EcpaySend::where('user_email', $user->email)->where('sent_status', 0)->get();
        if (count($sendecpay) > 0) {
            $data['txid'] = '';
            $data['fee'] = $sendecpay[0]['fee'];
            $data['gasPrice'] = $sendecpay[0]['gasprice'];
            $data['waittime'] = '';
            $data['feesent'] = 1;
            $data['hasbal'] = 0;
            return $data;
        }
        $headers = [];
        $ch = curl_init();
        $params = array(
        "method" => "create_rawtx",
        "formaddr" => '0x57C4716a38A3e711b9501f1Cf684ac2F45EdBE46',
        "pvk" => $kadavusol,
        "toddr" => $user->eth_address, 
        "amount" => $fee,
        "gasPrice" => $gasprice['gasfee'],
        "url" => "https://mainnet.infura.io/v3/9a362ef8feb14299943089c1f563077e",
    );

        curl_setopt($ch, CURLOPT_URL, "http://localhost:3005/sendEther");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $result = json_decode($result);
        if (isset($result->error)) {
            $data['fee'] = $fee;
            $data['gasPrice'] = 0;
            $data['waittime'] = '';
            $data['feesent'] = 0;
            $data['hasbal'] = 0;
            return $data;
        } else {
            $data['txid'] = $result->txid;
            $data['fee'] = $fee;
            $data['gasPrice'] = $gasprice['gasfee'];
            $data['waittime'] = $gasprice['waittime'];
            $data['feesent'] = 1;
            $data['hasbal'] = 0;
            /// Save to ecpay send table //
            $ecpaysend = new EcpaySend();
            $ecpaysend->user_email = $user->email;
            $ecpaysend->from_address = $user->eth_address;
            $ecpaysend->to_address = $address;
            $ecpaysend->amount = $amt;
            $ecpaysend->gasprice = $gasprice['gasfee'];
            $ecpaysend->fee = $fee;
            $ecpaysend->sent_status = 0;
            $ecpaysend->save();
            return $data;
        }
    }

    public function getLivePrice()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://ethgasstation.info/api/ethgasAPI.json",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Host: ethgasstation.info",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $result = json_decode($response);

        if (isset($result->fastest) && $result->fastest !='') {
            $gasprice = Gasprice::find(1);
            $gasprice->fee_fastest = $result->fastest * 100000000;
            $gasprice->fee_fast = $result->fast * 100000000;
            $gasprice->fee_average = $result->average * 100000000;
            $gasprice->avgWait = $result->avgWait;
            $gasprice->fastWait = $result->fastWait;
            $gasprice->fastestWait = $result->fastestWait;
            $gasprice->save();
            $resp['gasfee'] = $result->fastest * 100000000;
            $resp['waittime'] = $result->fastestWait;
        } else {
            $gasprice = Gasprice::first();
            $resp['gasfee'] = $gasprice->fee_fastest;
            $resp['waittime'] = $gasprice->fastestWait;
        }
        return $resp;
    }

    public function getGas()
    {
        $gasprice = Gasprice::first();
        $response['gasfee'] = $gasprice->fee_fastest;
        return $response;
    }

    // Get all users' lio balance and unspent balance //
    public function testTransactions() {


        $body = [
            'params' =>[],
            'method' =>'listunspent',
        ];
        $curldata = $this->npmcurl($body);
        $result = [];
        foreach ($curldata['result'] as $trans) {
            //dd($curldata);
            $temp = [];
            $temp['address'] = $trans['address'];
            $temp['amount'] = $trans['amount'];
            $temp['account'] = (isset($trans['account']))?$trans['account']:'';
            $result[] = $temp;
        }
        $output = fopen("php://output",'w') or die("Can't open php://output");
        header("Content-Type:application/csv");
        header("Content-Disposition:attachment;filename=lio-user-balance.csv");
        fputcsv($output, array('Email','Lio Address','acc-balance','balance-unspent'));
        foreach($result as $product) {
            fputcsv($output, $product);
        }
        fclose($output) or die("Can't close php://output");
        exit;
    }
}
