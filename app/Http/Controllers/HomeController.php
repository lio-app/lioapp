<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use DB;
use Log;
use Auth;
use Hash;
use Storage;
use Setting;
use Exception;
use Notification;
use App\User;
use App\Transaction;
use App\Currency;
use GuzzleHttp\Client;

use Mail;
use App\Mail\Changepasswordalert;
use App\Mail\Sendtranscationmail;
use App\Mail\Receivetranscationmail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    //Index page details
    public function networkpage(){
        try {
            return view('network');
        } catch (Exception $e) {
            return back()->with('flash_error', 'something went wrong !');
        }
    }


    public function wallet(){

                $name = Auth::user()->email;
                $param=[$name,1];
                $body = [
                    'params' => $param,
                    'method' => 'getbalance',
                ];
                $address = Auth::user()->eth_address;
           
                $client = new Client;
                $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=balance&address='.$address);
                $coindetails = json_decode($coindetails->getBody(),true);
                $amount = $coindetails['result']/1000000000000000000;
                $curldata['ETH'] = $amount;
                      
                $curldata['BTC']['result']=$this->bitcoin_npmcurl($body);

                $curldata['LTC']['result']=$this->litecoin_npmcurl($body);


                    $coin_type = array();
                    $client = new Client;
                    
                    $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/btcusd/');
                    $bitstampdetails = json_decode($bitstamp->getBody(),true);
                    $coin_type['BTC'] = $bitstampdetails['last'];
                    $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/bchusd/');
                    $bitstampdetails = json_decode($bitstamp->getBody(),true);
                    $coin_type['BCH'] = $bitstampdetails['last'];
                    $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/ethusd/');
                    $bitstampdetails = json_decode($bitstamp->getBody(),true);
                    $coin_type['ETH'] = $bitstampdetails['last'];
                    $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/ltcusd/');
                    $bitstampdetails = json_decode($bitstamp->getBody(),true);
                    $coin_type['LTC'] = $bitstampdetails['last'];
                    $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/xrpusd/');
                    $bitstampdetails = json_decode($bitstamp->getBody(),true);
                    $coin_type['XRP'] = $bitstampdetails['last'];


                    $bitstamp = $client->get('https://api.coinmarketcap.com/v2/ticker/?convert=EUR');
                    $bitstampdetails = json_decode($bitstamp->getBody(),true);
                    $loop = $bitstampdetails['data'];

                    foreach($loop as $data){

                        if($data['symbol'] == 'BNB'){
                            $coin_type['BNB'] = $data['quotes']['USD']['price'];                    
                        }

                        if($data['symbol'] == 'OMG'){
                            $coin_type['OMG']  = $data['quotes']['USD']['price'];
                        }

                        if($data['symbol'] == 'VET'){
                            $coin_type['VEN']  = $data['quotes']['USD']['price'];
                        }
                    }

                    $eth_address = Auth::user()->eth_address;

                    $client = new Client;
                    $coindetails = $client->get('https://api.tokenbalance.com/token/0xB8c77482e45F1F44dE1745F52C74426C631bDD52/'.$eth_address);
                    $coindetails = json_decode($coindetails->getBody(),true);
                    $curldata['result'] = $coindetails['balance'];
                    $curldata['BNB'] = $curldata['result'];

                    $client = new Client;
                    $coindetails = $client->get('https://api.tokenbalance.com/token/0xd850942ef8811f2a866692a623011bde52a462c1/'.$eth_address);
                    $coindetails = json_decode($coindetails->getBody(),true);
                    $curldata['result'] = $coindetails['balance'];
                    $curldata['VEN'] = $curldata['result'];

                    $client = new Client;
                    $coindetails = $client->get('https://api.tokenbalance.com/token/0xd26114cd6EE289AccF82350c8d8487fedB8A0C07/'.$eth_address);
                    $coindetails = json_decode($coindetails->getBody(),true);
                    $curldata['result'] = $coindetails['balance'];
                    $curldata['OMG'] = $curldata['result'];

                
                    $BTC = $coin_type['BTC'];
                    $ETH = $coin_type['ETH'];
                    $XRP = $coin_type['XRP'];
                    $BCH = $coin_type['BCH'];
                    $LTC = $coin_type['LTC'];
                    $BNB = $coin_type['BNB'];
                    $OMG = $coin_type['OMG'];
                    $VEN = $coin_type['VEN'];

                    $data['BTC'] = $curldata['BTC']['result']['result'];
                    $data['ETH'] = $curldata['ETH'];
                    $data['LTC'] = $curldata['LTC']['result']['result'];
                    $data['BNB'] = $curldata['BNB'];
                    $data['OMG'] = $curldata['OMG'];
                    $data['VEN'] = $curldata['VEN'];

    
                    return view('wallet',compact('data','BTC','LTC','ETH','BNB','OMG','VEN'));
              
    }

    //network

     public function network(Request $request){
        try {

            // if($request->network == 0){
            //     return back()->with('flash_error', 'Kindly select the currency type !');
            // }

            if($request->network == 'BNB'){
                User::where('id', Auth::user()->id)->update(['address'=>Auth::user()->eth_address, 'network' => $request->network]);
            }
            if($request->network == 'VEN'){
                User::where('id', Auth::user()->id)->update(['address'=>Auth::user()->eth_address, 'network' => $request->network]);
            }
            if($request->network == 'OMG'){
                User::where('id', Auth::user()->id)->update(['address'=>Auth::user()->eth_address, 'network' => $request->network]);
            }
            if($request->network == 'MKR'){
                User::where('id', Auth::user()->id)->update(['address'=>Auth::user()->eth_address, 'network' => $request->network]);
            }
            if($request->network == 'ZRX'){
                User::where('id', Auth::user()->id)->update(['address'=>Auth::user()->eth_address, 'network' => $request->network]);
            }
            if($request->network == 'ZIL'){
                User::where('id', Auth::user()->id)->update(['address'=>Auth::user()->eth_address, 'network' => $request->network]);
            }
            if($request->network == 'ICX'){
                User::where('id', Auth::user()->id)->update(['address'=>Auth::user()->eth_address, 'network' => $request->network]);
            }










            if($request->network == 'BTC'){
                User::where('id', Auth::user()->id)->update(['address'=>Auth::user()->btc_address, 'network' => $request->network]);
            }
            // if($request->network == 'BCH'){
            //     User::where('id', Auth::user()->id)->update(['address'=>Auth::user()->bch_address, 'network' => $request->network]);
            // }
            if($request->network == 'LTC'){
                User::where('id', Auth::user()->id)->update(['address'=>Auth::user()->ltc_address, 'network' => $request->network]);
            }
            if($request->network == 'ETH'){
                User::where('id', Auth::user()->id)->update(['address'=>Auth::user()->eth_address, 'network' => $request->network]);
            }
            return back()->with('flash_success', 'Currency  changed Successfully !');
        } catch (Exception $e) {
            return back()->with('flash_error', 'something went wrong !');
        }
    }

    public function index(Request $request)
    {  
        // try{

            $name = Auth::user()->email;


            if(Auth::user()->address==null || Auth::user()->eth_address==null) {
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                ];
                $body = ["method" => "personal_newAccount", "params" => [Auth::user()->email], "id" => 1];

                $url ="http://localhost:8545";

                $res = $client->post($url, [
                    'headers' => $headers,
                    'body' => json_encode($body),
                ]);
                $eth_address = json_decode($res->getBody(),true);
                Auth::user()->eth_address = $eth_address['result'];
                Auth::user()->address = $eth_address['result'];
                Auth::user()->network = 'ETH';
                Auth::user()->save();
            }
            
            
            if(Auth::user()->address==null || Auth::user()->btc_address==null) {
    			
                //getaddressbyaccount
                $param=[$name];
                $body = [
                    'params' => $param,
                    'method' => 'getaccountaddress',
                ];
                $curldata=$this->bitcoin_npmcurl($body);

                $address = $curldata['result'];

                Auth::user()->address = $address;
                Auth::user()->btc_address = $address;
                Auth::user()->network = 'BTC';

                Auth::user()->save();
            }

            if(Auth::user()->ltc_address==null) {
                
                //getaddressbyaccount
                $param=[$name];
                $body = [
                    'params' => $param,
                    'method' => 'getaccountaddress',
                ];
                $curldata=$this->litecoin_npmcurl($body);

                $address = $curldata['result'];

                Auth::user()->ltc_address = $address;
                

                Auth::user()->save();
            }

            // if(Auth::user()->bch_address==null) {
                
            //     //getaddressbyaccount
            //     $param=[$name];
            //     $body = [
            //         'params' => $param,
            //         'method' => 'getaccountaddress',
            //     ];
            //     $curldata=$this->bitcoincash_npmcurl($body);

            //     $address = $curldata['result'];

            //     Auth::user()->bch_address = $address;
            
            //     Auth::user()->save();
            // }

            //getaddressbyaccount
            $param=[$name];
            $body = [
                'params' => $param,
                'method' => 'getaccountaddress',
            ];

            if(Auth::user()->network == 'BTC'){
            $curldata=$this->bitcoin_npmcurl($body);

            }
            if(Auth::user()->network == 'BCH'){
            $curldata=$this->bitcoincash_npmcurl($body);
            }
            if(Auth::user()->network == 'LTC'){
            $curldata=$this->litecoin_npmcurl($body);
            }

            if(Auth::user()->network == 'ETH' || Auth::user()->network == 'BNB' || Auth::user()->network == 'VEN' || Auth::user()->network == 'OMG' || Auth::user()->network == 'MKR' || Auth::user()->network == 'ZRX' || Auth::user()->network == 'ZIL' || Auth::user()->network == 'ICX'){
            $curldata['result']  = Auth::user()->eth_address;
            }

            //https://api.etherscan.io/api?module=transaction&action=getstatus&txhash=0x15f8e5ea1079d9a0bb04a4c58ae5fe7654b5b2b4463375ff7ffb490aa0032f3a&apikey=YourApiKeyToken

            $address = $curldata['result'];

            //getbalance
            $param=[$name,1];
            $body = [
                'params' => $param,
                'method' => 'getbalance',
            ];

            if(Auth::user()->network == 'ETH'){
                $client = new Client;
                $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=balance&address='.$address);
                $coindetails = json_decode($coindetails->getBody(),true);
                $amount = $coindetails['result']/1000000000000000000;
                $curldata['result'] = $amount;
            }

             if(Auth::user()->network == 'BNB' || Auth::user()->network == 'VEN' || Auth::user()->network == 'OMG' || Auth::user()->network == 'MKR' || Auth::user()->network == 'ZRX' || Auth::user()->network == 'ZIL' || Auth::user()->network == 'ICX')
             {


                if(Auth::user()->network == 'BNB'){$contract = "0xB8c77482e45F1F44dE1745F52C74426C631bDD52";}
                if(Auth::user()->network == 'VEN'){$contract = "0xd850942ef8811f2a866692a623011bde52a462c1";}
                if(Auth::user()->network == 'OMG'){$contract = "0xd26114cd6EE289AccF82350c8d8487fedB8A0C07";}
                if(Auth::user()->network == 'MKR'){$contract = "    0x9f8f72aa9304c8b593d555f12ef6589cc3a579a2";}
                if(Auth::user()->network == 'ZRX'){$contract = "0xe41d2489571d322189246dafa5ebde1f4699f498";}
                if(Auth::user()->network == 'ZIL'){$contract = "0x05f4a42e251f2d52b8ed15e9fedaacfcef1fad27";}
                if(Auth::user()->network == 'ICX'){$contract = "0xb5a5f22694352c15b00323844ad545abb2b11028";}

                $client = new Client;
                $coindetails = $client->get('https://api.tokenbalance.com/token/'.$contract.'/'.$address);
                $coindetails = json_decode($coindetails->getBody(),true);
                $curldata['result'] = $coindetails['balance'];

            }

           if(Auth::user()->network == 'BTC'){
            $curldata=$this->bitcoin_npmcurl($body);

            }
            if(Auth::user()->network == 'BCH'){
            $curldata=$this->bitcoincash_npmcurl($body);
            }
            if(Auth::user()->network == 'LTC'){
            $curldata=$this->litecoin_npmcurl($body);
            }

            $coin = $curldata['result'];

            $balance = User::where('id',Auth::user()->id)->first();
            $balance->coin = $coin;
            if(!$balance->address) {
                $balance->address = $address;
            }
            $balance->save();

            //listtransactions
            $param=[$name,10,0];
            $body = [                  
                'params' => $param,
                'method' => 'listtransactions',
            ];

           if(Auth::user()->network == 'BTC'){
            $curldata=$this->bitcoin_npmcurl($body);

            }
            if(Auth::user()->network == 'BCH'){
            $curldata=$this->bitcoincash_npmcurl($body);
            }
            if(Auth::user()->network == 'LTC'){
            $curldata=$this->litecoin_npmcurl($body);
            }
            $history = array();
            $history_tmp = array();

            if(Auth::user()->network == 'ETH' || Auth::user()->network == 'BNB' || Auth::user()->network == 'VEN' || Auth::user()->network == 'OMG' || Auth::user()->network == 'MKR' || Auth::user()->network == 'ZRX' || Auth::user()->network == 'ZIL' || Auth::user()->network == 'ICX'){

                $ethaddress = Auth::user()->eth_address;
                $client = new Client;
                $coindetails = $client->get('http://api.etherscan.io/api?module=account&action=txlist&address='.$ethaddress.'&startblock=0&endblock=99999999&sort=desc');
                $result = json_decode($coindetails->getBody(),true);
                // dd($result['result']);

                $result =  $result['result'] ;
               // dd($result);

                foreach($result as $index => $results){

                    $history_tmp=[
                        'txid' => $results['hash'],
                        'amount' => $results['value']/1000000000000000000,
                        'time' => $results['timeStamp'],
                    ];

                    array_push( $history, $history_tmp);
                }

                //dd($history);

                $curldata['result'] = $history;
            }

            //http://api.etherscan.io/api?module=account&action=txlist&address=0x1316f35873d5df1661719b9d1598D9Ea29b7Af4C&startblock=0&endblock=99999999&sort=desc&apikey=YourApiKeyToken

            if(Auth::user()->network == 'BNB' || Auth::user()->network == 'VEN' || Auth::user()->network == 'OMG' || Auth::user()->network == 'MKR' || Auth::user()->network == 'ZRX' || Auth::user()->network == 'ZIL' || Auth::user()->network == 'ICX'){

                $ethaddress = Auth::user()->eth_address;
                $client = new Client;
                $coindetails = $client->get('http://api.etherscan.io/api?module=account&action=txlist&address='.$ethaddress.'&startblock=0&endblock=99999999&sort=desc');
                $result = json_decode($coindetails->getBody(),true);
                // dd($result['result']);

                $result =  $result['result'] ;
               // dd($result);

                foreach($result as $index => $results){

                    $history_tmp=[
                        'txid' => $results['hash'],
                        'amount' => $results['value']/1000000000000000000,
                        'time' => $results['timeStamp'],
                    ];

                    array_push( $history, $history_tmp);
                }

                //dd($history);

                $curldata['result'] = $history;
            }

            if(isset($curldata['result'])){
                $history = $curldata['result'];
            }
            $currency=Currency::get();

            $coin_type = array();
            $client = new Client;
            $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/btcusd/');
            $bitstampdetails = json_decode($bitstamp->getBody(),true);
            $coin_type['BTC'] = $bitstampdetails['last'];

            $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/bchusd/');
            $bitstampdetails = json_decode($bitstamp->getBody(),true);
            $coin_type['BCH'] = $bitstampdetails['last'];

            $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/ethusd/');
            $bitstampdetails = json_decode($bitstamp->getBody(),true);
            $coin_type['ETH'] = $bitstampdetails['last'];

            $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/ltcusd/');
            $bitstampdetails = json_decode($bitstamp->getBody(),true);
            $coin_type['LTC'] = $bitstampdetails['last'];

            $bitstamp = $client->get('https://www.bitstamp.net/api/v2/ticker/xrpusd/');
            $bitstampdetails = json_decode($bitstamp->getBody(),true);
            $coin_type['XRP'] = $bitstampdetails['last'];


           
           

            $bitstamp = $client->get('https://api.coinmarketcap.com/v2/ticker/?convert=EUR');
            $bitstampdetails = json_decode($bitstamp->getBody(),true);
            $loop = $bitstampdetails['data'];

            foreach($loop as $data){

                if($data['symbol'] == 'BNB'){

                    $coin_type['BNB'] = $data['quotes']['USD']['price'];

                    //dd($coin_type['BNB']);
                    
                }

                if($data['symbol'] == 'OMG'){
                    
                    $coin_type['OMG']  = $data['quotes']['USD']['price'];
                    // dd($coin_type['OMG']);
                    
                }

                if($data['symbol'] == 'VET'){
                    
                    $coin_type['VEN']  = $data['quotes']['USD']['price'];
                    //dd($coin_type['VEN']);

                    
                }

                if($data['symbol'] == 'MKR'){
                    $coin_type['MKR']  = $data['quotes']['USD']['price'];
                }

                if($data['symbol'] == 'ZRX'){
                    $coin_type['ZRX']  = $data['quotes']['USD']['price'];
                }

                if($data['symbol'] == 'ZIL'){
                    $coin_type['ZIL']  = $data['quotes']['USD']['price'];
                }

                if($data['symbol'] == 'ICX'){
                    $coin_type['ICX']  = $data['quotes']['USD']['price'];
                }

                
            }


            $BTC = $coin_type['BTC'];
            $ETH = $coin_type['ETH'];
            $XRP = $coin_type['XRP'];
            $BCH = $coin_type['BCH'];
            $LTC = $coin_type['LTC'];
            $BNB = $coin_type['BNB'];
            $VEN = $coin_type['VEN'];
            $OMG = $coin_type['OMG'];
            $MKR = $coin_type['MKR'];
            $ZRX = $coin_type['ZRX'];
            $ZIL = $coin_type['ZIL'];
            $ICX = $coin_type['ICX'];


                $name = Auth::user()->email;
                $param=[$name,1];
                $body = [
                    'params' => $param,
                    'method' => 'getbalance',
                ];
                $address = Auth::user()->eth_address;
           
                $client = new Client;
                $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=balance&address='.$address);
                $coindetails = json_decode($coindetails->getBody(),true);
                $amount = $coindetails['result']/1000000000000000000;
                $curldata['ETH'] = $amount;
                      
                $curldata['BTC']['result']=$this->bitcoin_npmcurl($body);

                $curldata['LTC']['result']=$this->litecoin_npmcurl($body);

                    $eth_address = Auth::user()->eth_address;

                    $client = new Client;
                    // $coindetails = $client->get('https://api.tokenbalance.com/token/0xB8c77482e45F1F44dE1745F52C74426C631bDD52/'.$eth_address);
                    // $coindetails = json_decode($coindetails->getBody(),true);
                    // $curldata['result'] = $coindetails['balance'];
                    // $curldata['BNB'] = $curldata['result'];
                    $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=0x9f8f72aa9304c8b593d555f12ef6589cc3a579a2&address='.$eth_address);
                        $coindetails = json_decode($coindetails->getBody(),true);
                        $curldata['BNB'] = 0 ;
                        if($coindetails['status'] == 1){
                            $curldata['BNB'] = $coindetails['result']/1000000000000000000;
                        }


                    $client = new Client;
                    // $coindetails = $client->get('https://api.tokenbalance.com/token/0xd850942ef8811f2a866692a623011bde52a462c1/'.$eth_address);
                    // $coindetails = json_decode($coindetails->getBody(),true);
                    // $curldata['result'] = $coindetails['balance'];
                    // $curldata['VEN'] = $curldata['result'];
                    $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=0x9f8f72aa9304c8b593d555f12ef6589cc3a579a2&address='.$eth_address);
                        $coindetails = json_decode($coindetails->getBody(),true);
                        $curldata['VEN'] = 0 ;
                        if($coindetails['status'] == 1){
                            $curldata['VEN'] = $coindetails['result']/1000000000000000000;
                        }

                    $client = new Client;
                    // $coindetails = $client->get('https://api.tokenbalance.com/token/0xd26114cd6EE289AccF82350c8d8487fedB8A0C07/'.$eth_address);
                    // $coindetails = json_decode($coindetails->getBody(),true);
                    // $curldata['result'] = $coindetails['balance'];
                    // $curldata['OMG'] = $curldata['result'];
                    $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=0x9f8f72aa9304c8b593d555f12ef6589cc3a579a2&address='.$eth_address);
                        $coindetails = json_decode($coindetails->getBody(),true);
                        $curldata['OMG'] = 0 ;
                        if($coindetails['status'] == 1){
                            $curldata['OMG'] = $coindetails['result']/1000000000000000000;
                        }


                    $client = new Client;
                    // $coindetails = $client->get('https://api.tokenbalance.com/token/0x9f8f72aa9304c8b593d555f12ef6589cc3a579a2/'.$eth_address);
                    // $coindetails = json_decode($coindetails->getBody(),true);
                    // $curldata['result'] = $coindetails['balance'];
                    // $curldata['MKR'] = $curldata['result'];


                        $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=0x9f8f72aa9304c8b593d555f12ef6589cc3a579a2&address='.$eth_address);
                        $coindetails = json_decode($coindetails->getBody(),true);
                        $curldata['MKR'] = 0 ;
                        if($coindetails['status'] == 1){
                            $curldata['MKR'] = $coindetails['result']/1000000000000000000;
                        }

                    $client = new Client;
                    // $coindetails = $client->get('https://api.tokenbalance.com/token/0xe41d2489571d322189246dafa5ebde1f4699f498/'.$eth_address);
                    // $coindetails = json_decode($coindetails->getBody(),true);
                    // $curldata['result'] = $coindetails['balance'];
                    // $curldata['ZRX'] = $curldata['result'];
                    $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=0xe41d2489571d322189246dafa5ebde1f4699f498&address='.$eth_address);
                        $coindetails = json_decode($coindetails->getBody(),true);
                        $curldata['ZRX'] = 0 ;
                        if($coindetails['status'] == 1){
                            $curldata['ZRX'] = $coindetails['result']/1000000000000000000;
                        }

                    $client = new Client;
                    $coindetails = $client->get('https://api.tokenbalance.com/token/0x05f4a42e251f2d52b8ed15e9fedaacfcef1fad27/'.$eth_address);
                    $coindetails = json_decode($coindetails->getBody(),true);
                    $curldata['result'] = $coindetails['balance'];
                    $curldata['ZIL'] = $curldata['result'];

                    $client = new Client;
                    // $coindetails = $client->get('https://api.tokenbalance.com/token/0xb5a5f22694352c15b00323844ad545abb2b11028/'.$eth_address);
                    // $coindetails = json_decode($coindetails->getBody(),true);
                    // $curldata['result'] = $coindetails['balance'];
                    // $curldata['ICX'] = $curldata['result'];
                    $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=0xb5a5f22694352c15b00323844ad545abb2b11028&address='.$eth_address);
                        $coindetails = json_decode($coindetails->getBody(),true);
                        $curldata['ICX'] = 0 ;
                        if($coindetails['status'] == 1){
                            $curldata['ICX'] = $coindetails['result']/1000000000000000000;
                        }


                    $data['BTC'] = $curldata['BTC']['result']['result'];
                    $data['ETH'] = $curldata['ETH'];
                    $data['LTC'] = $curldata['LTC']['result']['result'];
                    $data['BNB'] = $curldata['BNB'];
                    $data['OMG'] = $curldata['OMG'];
                    $data['VEN'] = $curldata['VEN'];
                    $data['MKR'] = $curldata['MKR'];
                    $data['ZRX'] = $curldata['ZRX'];
                    $data['ZIL'] = $curldata['ZIL'];
                    $data['ICX'] = $curldata['ICX'];

                    $USD = ($data['BTC'] * $BTC ) + ($data['ETH'] * $ETH ) + ($data['LTC'] * $LTC ) + ($data['BNB'] * $BNB ) + ($data['OMG'] * $OMG ) + ($data['VEN'] * $VEN ) + ($data['MKR'] * $MKR ) + ($data['ZRX'] * $ZRX ) + ($data['ZIL'] * $ZIL ) + ($data['ICX'] * $ICX );


            if($request->ajax()) {
                return response()->json(['address' => $address,'coin'=>$coin,'history'=>$history,'currency'=>$currency], 200); 
            }else{
                return view('home',compact('address','coin','history','currency','BCH','LTC','BTC','ETH','VEN','BNB','OMG','data','USD','MKR','ZRX','ZIL','ICX'));
            }

        // }catch(Exception $e){

        //         return back()->with('flash_error', 'something went wrong !');
        // }
        
    }


    //Send Coin to another user

    public function sendcoin(Request $request){

        $this->validate($request, [
                'amount' => 'required|numeric',
                'to_address' => 'required|regex:/^[a-zA-Z0-9]+$/u',
            ]);

        try{

            $user=Auth::user();

            if($user->block_status==0){
                return back()->with('flash_error',"Transaction Failed, Please try again later.");
            }

        if(Auth::user()->network == 'BNB' || Auth::user()->network == 'VEN' || Auth::user()->network == 'OMG' || Auth::user()->network == 'MKR' || Auth::user()->network == 'ZRX' || Auth::user()->network == 'ZIL' || Auth::user()->network == 'ICX'){
            $address = Auth::user()->eth_address;
            $client = new Client;
            $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=balance&address='.$address);
            $coindetails = json_decode($coindetails->getBody(),true);

            $amount = $coindetails['result'] / 1000000000000000000 ;

            $limit = 0.000000003;

            if($amount < $limit){

                return back()->with('flash_error','Kindly maintain your gasLimit to send your coin !');

            }

            if(Auth::user()->network == 'BNB'){$contract = "0xB8c77482e45F1F44dE1745F52C74426C631bDD52"; $decimal = 18;}
            if(Auth::user()->network == 'VEN'){$contract = "0xd850942ef8811f2a866692a623011bde52a462c1"; $decimal = 18;}
            if(Auth::user()->network == 'OMG'){$contract = "0xd26114cd6EE289AccF82350c8d8487fedB8A0C07"; $decimal = 18;}
             if(Auth::user()->network == 'MKR'){$contract = "0x9f8f72aa9304c8b593d555f12ef6589cc3a579a2";$decimal = 18;}
            if(Auth::user()->network == 'ZRX'){$contract = "0xe41d2489571d322189246dafa5ebde1f4699f498"; $decimal = 18;}
            if(Auth::user()->network == 'ZIL'){$contract = "0x05f4a42e251f2d52b8ed15e9fedaacfcef1fad27"; $decimal = 12;}
            if(Auth::user()->network == 'ICX'){$contract = "0xb5a5f22694352c15b00323844ad545abb2b11028"; $decimal = 18;}

            $from = Auth::user()->eth_address;
            $coin = $request->amount;
            // $key = "1718a4ee7b38da23f7e08840c519ce21789f717b29a789057c0b6cab6b8c5e31";
           // $privatekey = (new UserApiController)->privatekey();
                $address = Auth::user()->eth_address;
                $email = Auth::user()->email;

                $client = new Client;
                // http://localhost:8070/getPrivatekey?pwdchk=demo@demo.com~0xa8307f6e9f829b9bf94389a248a1b5bba791633c
                $privatekey = $client->get('http://localhost:8070/getPrivatekey?pwdchk='.$email.'~'.$address);
                $privatekey = json_decode($privatekey->getBody(),true);
                $key = $privatekey['privatekey'];


            // $from = "0x1316f35873d5df1661719b9d1598D9Ea29b7Af4C";
            // $contract = "0xcba8a38e369f34ac8d7fe4a8b95c37d2d7c39ac3";
            // $decimal = 10;
            // $coin = $request->amount;
            // $coin = 1;
            // $key = "1718a4ee7b38da23f7e08840c519ce21789f717b29a789057c0b6cab6b8c5e31";
            return view('send-token')
                            ->with('from',$from)
                            ->with('to', $request->to_address)
                            ->with('contract', $contract)
                            ->with('decimal', $decimal)
                            ->with('coin', $coin)
                            ->with('key',$key);

            }
            
            if(Auth::user()->network == 'ETH'){

                //Check for gas Price

                    $address = Auth::user()->eth_address;
                    $client = new Client;
                    $coindetails = $client->get('https://api.etherscan.io/api?module=account&action=balance&address='.$address);
                    $coindetails = json_decode($coindetails->getBody(),true);

                    $amount = $coindetails['result'] / 1000000000000000000 ;

                    $limit = 0.000000003;

                    if($amount < $limit){
                        return back()->with('flash_error','Kindly maintain your gasLimit to send your coin !');
                    }



                    $eth_address = Auth::user();
                    $eth_address=$eth_address['eth_address'];
                    $name = Auth::user()->email;
                    $client = new Client();
                    $headers = [
                        'Content-Type' => 'application/json',
                    ];
                    $body = ["jsonrpc" => "2.0","method" => "personal_unlockAccount", "params" => [$eth_address,$name, 3600], "id" => 1];
                    $url ="http://localhost:8545";
                    $res = $client->post($url, [
                        'headers' => $headers,
                        'body' => json_encode($body),
                    ]);
                    $unlock = json_decode($res->getBody(),true);
                    \Log::info($unlock);
                    if($unlock['result']) {
                        $price_num = \DB::select('select ('.$request->amount.'-0.000084) * 1000000000000000000');
                        $arr = json_decode(json_encode($price_num[0]), true);
                        $final_num=current($arr);
                        $price = \DB::select('select CONV('.$final_num.',10,16)');
                        $arr = json_decode(json_encode($price[0]), true);
                        $price='0x'.current($arr);

                        $client = new Client();
                        $headers = [
                            'Content-Type' => 'application/json',
                        ];
                        $body = ["jsonrpc" => "2.0","method" => "eth_sendTransaction", "params" => array(["from" => $eth_address, "to" => $request->to_address, "gas" => "0x5208", "gasPrice" => "0xEE6B2800", "value" => $price]), "id" => 1];

                        //"gasPrice":web3.toHex( * 1e9),"gasLimit":web3.toHex(210000)

                        $res = $client->post($url, [
                            'headers' => $headers,
                            'body' => json_encode($body),
                        ]);
                        $details = json_decode($res->getBody(),true);

                        //dd($details);

                        if($details['error']['message']){

                            return back()->with('flash_error',$details['error']['message'] ); 
                        }

                        \Log::info($details);

                        // if($txs['result']) {

                        //     $coin = new SendCoin;
                        //     $coin->address = $request->to_address;
                        //     $coin->amount = $request->amount;
                        //     $coin->fee = $fee;
                        //     $coin->total = $total;
                        //     $coin->payment_mode = $request->coin_type;
                        //     $coin->user_id = Auth::user()->id;
                        //     $coin->date = Carbon::now();
                        //     $coin->tx_id = $txs['result'];
                        //     //$coin->status = 'PENDING';
                        //     $coin->save();

                        //     $coinlimit->value -= $total;
                        //     $coinlimit->save(); 

                        // }

                        // if($details['result']){

                        // $receiver = User::where('eth_address',$request->to_address)->first();

                        // $txn = new Transaction();
                        // $txn->sender = $user->id;
                        // $txn->receiver = $receiver->id;
                        // $txn->txn_hash = $details['result'];
                        // $txn->sender_address = $user->address;
                        // $txn->receiver_address = $request->to_address;
                        // $txn->amount = $request->amount;
                        // $txn->save();

                        // //$email = User::where('address',$request->to_address)->first();

                        //     $sender_email=$user->email;
                        //     $receiver_email=$receiver;

                        // $maildata=[
                        //         'sender'=> $user->address,
                        //         'receiver'=>$request->to_address,
                        //         'amount'=>$request->amount,                        
                        //     ];            
                    
                        // // Mail::to($sender_email)->send(new Sendtranscationmail($maildata));
                        // // Mail::to($receiver_email)->send(new Receivetranscationmail($maildata));
                        // }else{
                        //     return back()->with('flash_error', trans('api.something_went_wrong'));
                        // }
                    }else{
                        return back()->with('flash_error', trans('api.something_went_wrong'));
                    }
                    return back()->with('flash_success','Coin Send Successfully !'); 
                }



                $user=Auth::user();
                $name = $user['email'];

                //send coin 
                //$param=[$name,$request->to_address, (float) $request->amount, 6, $request->comment, $request->to_comment];
                // $param=[$name,$request->to_address, (float) $request->amount, 6];
                // $body = [
                //     'params' => $param,
                //     'method' => 'sendfrom',
                // ];

                $param=[$name,$request->to_address,  $request->amount];
                    $body = [
                        'params' => $param,
                        'method' => 'sendfrom',
                    ];


                // $curldata=$this->bitcoin_npmcurl($body);




                if(Auth::user()->network == 'BTC'){
                    $curldata=$this->bitcoin_npmcurl($body);
                }
                if(Auth::user()->network == 'BCH'){
                    $curldata=$this->bitcoincash_npmcurl($body);
                }
                if(Auth::user()->network == 'LTC'){
                    $curldata=$this->litecoin_npmcurl($body);
                }
                $details = $curldata;

                // if($details['error']['message']){

                //                 return back()->with('flash_error',$details['error']['message'] ); 
                //                 //return response()->json(['error' => $details['error']['message']], 500); 
                //             }


               //  if($details['result']){

               //      if(Auth::user()->network == 'BTC'){
               //  $receiver = User::where('btc_address',$request->to_address)->first();

               //  }
               //  if(Auth::user()->network == 'BCH'){
               //  $receiver = User::where('bch_address',$request->to_address)->first();
               //  }
               //  if(Auth::user()->network == 'LTC'){
               // $receiver = User::where('ltc_address',$request->to_address)->first();
               //  }

                    

               //      $txn = new Transaction();
               //      $txn->sender = $user->id;
               //      $txn->receiver = $receiver->id;
               //      $txn->txn_hash = $details['result'];
               //      $txn->sender_address = $user->address;
               //      $txn->receiver_address = $request->to_address;
               //      $txn->amount = $request->amount;
               //      $txn->save();

               //      //$email = User::where('address',$request->to_address)->first();

               //      $sender_email=$user->email;
               //      $receiver_email=$receiver;

               //      $maildata=[
               //              'sender'=> $user->address,
               //              'receiver'=>$request->to_address,
               //              'amount'=>$request->amount,                        
               //          ];            
                        
               //      Mail::to($sender_email)->send(new Sendtranscationmail($maildata));
               //      Mail::to($receiver_email)->send(new Receivetranscationmail($maildata));
               //  }


            
                if($details['result'] != null) {
                    return back()->with('flash_success','Coin Send Successfully !'); 
                }else{
                    return back()->with('flash_error', $details['error']['message']);
                }

        }catch(Exception $e){

            return back()->with('flash_error', 'something went wrong !');
        }

        
    }


    //Show history

    public function history(Request $request)
    {
        try{

        $history = array();
            $history_tmp = array();

            $name = Auth::user()->email;
            $details=array();
            //List Transactions
            $param=[$name,50,0];
            $body = [                  
                'params' => $param,
                'method' => 'listtransactions',
            ];
            if(Auth::user()->network == 'BTC'){
            $curldata=$this->bitcoin_npmcurl($body);

            }
            if(Auth::user()->network == 'BCH'){
            $curldata=$this->bitcoincash_npmcurl($body);
            }
            if(Auth::user()->network == 'LTC'){
            $curldata=$this->litecoin_npmcurl($body);
            }

           if(Auth::user()->network == 'ETH'){

                $ethaddress = Auth::user()->eth_address;
                $client = new Client;
                $coindetails = $client->get('http://api.etherscan.io/api?module=account&action=txlist&address='.$ethaddress.'&startblock=0&endblock=99999999&sort=desc');
                $result = json_decode($coindetails->getBody(),true);
                // dd($result['result']);

                $result =  $result['result'] ;
               // dd($result);

                foreach($result as $index => $results){

                    $history_tmp=[
                        'txid' => $results['hash'],
                        'amount' => $results['value']/1000000000000000000,
                        'time' => $results['timeStamp'],
                        'address' => $results['to'],
                    ];

                    array_push( $history, $history_tmp);
                }

                //dd($history);

                $curldata['result'] = $history;
            }

           // dd($curldata['result']);
            // $details = array();
            if(isset($curldata['result'])){
                $details = $curldata['result'];
            }
            /*if($details != null) {
                return view('transactions',compact('details'));
            }else{
                return back()->with('flash_error', $curldata['error']['message']);
           }*/
                return view('transactions',compact('details'));

        }catch(Exception $e){
            
            return back()->with('flash_error', 'something went wrong !');
        }
        
    }


    //Display Receive Address 

    public function receive(Request $request)
    {
        try{


            $address = Auth::user()->address;

            if($request->ajax()) {
                return response()->json(['address' =>$address], 200); 
            }else{
                return view('receive',compact('address'));
            } 
        }catch(Exception $e){
            
            return back()->with('flash_error', 'something went wrong !');
        }   
    }

     //Change Password

    public function update_password(Request $request)
    {
        $this->validate($request, [
                'password' => 'required|confirmed|min:6',
                'current_password' => 'required',
            ]);

        $User = Auth::user();

        if(Hash::check($request->current_password, $User->password))
        {
            $User->password = bcrypt($request->password);
            $User->save();

            $user_email=Auth::user()->email; 
            Mail::to($user_email)->send(new Changepasswordalert($user_email));

            //return back()->with('flash_success', trans('user.profiles.pass_updated'));
            Auth::logout();
            \Session::flash('flash_success',trans('user.profiles.pass_updated'));
            return redirect('/login');

        } else {
            return back()->with('flash_error', trans('user.profiles.same'));
        }
    }

    public function tokensellnotify($response){
        if($response) {
            return Redirect::route('home')->with('flash_success',$response);
        } else {
            return Redirect::route('home')->with('flash_success',$response);
        }
    }

    
    //curl to hit bitcoin server 

    public function bitcoin_npmcurl($body){

        try{
            $id=0;
            $status       = null;
            $error        = null;
            $raw_response = null;
            $response     = null;

            $proto="http";
            $username ="because";
            $password ="because";
            $host ="13.58.27.17";
            $port ="9332";
            $url='';
            $CACertificate=null;
            $method=$body['method'];
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
                'id' =>  "curltest"
            ));
            //$curl    = curl_init("{$proto}://{$host}:{$port}/{$url}");
            $curl    = curl_init("{$proto}://{$host}:{$port}/");
            $options = array(
                CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
                CURLOPT_USERPWD        => $username . ':' . $password,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_HTTPHEADER     => array('Content-type: application/json'),
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $request
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
            $response     = json_decode($raw_response, true);
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
                return false;
            }
            //return $response;
            return $response;
        }catch(Exception $e){

        }
    }


    
   

    public function litecoin_npmcurl($body){

        try{
            $id=0;
            $status       = null;
            $error        = null;
            $raw_response = null;
            $response     = null;

            //curl --user bvfK73aaKEMVSrPUmTeqDjS:UZpK7sjSc78E6ggj5RdZS4D --data-binary '{"jsonrpc": "1.0", "id":"curltest", "method": "getnewaddress", "params": [] }' -H 'content-type: text/plain;' http://142.93.194.154:19344/
            //{"result":"MH9diy2sRhSd8WGfHuN6mw5pGcru2G7d1Z","error":null,"id":"curltest"}


            $proto="http";
            $username ="TthlwaXDOvtybYbt";
            $password ="bitdlwdwmsdlwdw";
            $host ="18.191.223.5";
            $port ="19344";
            $url='';
            $CACertificate=null;
            $method=$body['method'];            
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
                'id' =>  "curltest"
            ));
            //$curl    = curl_init("{$proto}://{$host}:{$port}/{$url}");
            $curl    = curl_init("{$proto}://{$host}:{$port}/");
            //dd($curl);
            $options = array(
                CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
                CURLOPT_USERPWD        => $username . ':' . $password,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_HTTPHEADER     => array('Content-type: application/json'),
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $request
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
           $response     = json_decode($raw_response, true);

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
        }catch(Exception $e){

        }
    }


    //bitcoincash_npmcurl

    public function bitcoincash_npmcurl($body){

        try{
            $id=0;
            $status       = null;
            $error        = null;
            $raw_response = null;
            $response     = null;

            //curl --user user123456:pwd123456 --data-binary '{"jsonrpc": "1.0", "id":"curltest", "method": "getnewaddress", "params": [] }' -H 'content-type: text/plain;' http://204.48.18.111:19992/


            $proto="http";
            $username ="user123456";
            $password ="pwd123456";
            $host ="204.48.18.111";
            $port ="19992";
            $url='';
            $CACertificate=null;
            $method=$body['method'];            
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
                'id' =>  "curltest"
            ));
            //$curl    = curl_init("{$proto}://{$host}:{$port}/{$url}");
            $curl    = curl_init("{$proto}://{$host}:{$port}/");
            //dd($curl);
            $options = array(
                CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
                CURLOPT_USERPWD        => $username . ':' . $password,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_HTTPHEADER     => array('Content-type: application/json'),
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $request
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
           $response     = json_decode($raw_response, true);

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
        }catch(Exception $e){

        }
    }
}
