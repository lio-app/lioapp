<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Setting;
use Storage;
use Auth;
use GuzzleHttp\Client;
use App\Http\Controllers\TransactionController;

use App\WalletPassbook;
use App\UserTrasaction;

use App\KycDocument;
use App\User;
use App\Document;
use App\UserCoin;
use App\WithdrawHistory;
use App\Currency;
use App\Admin;

use Mail;
use App\Mail\Signupwelcomemail;

class AdminController extends Controller
{

    public function dsgvo_en()
    {
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/pdf/datenschutzerklaerung-english.pdf";
        $headers = array(
          'Content-Type: application/pdf',
        );

        return \Response::download($file, 'datenschutzerklaerung-english.pdf', $headers);
    }

    public function dsgvo_gr()
    {
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/pdf/datenschutzerklaerung-german.pdf";
        $headers = array(
          'Content-Type: application/pdf',
        );

        return \Response::download($file, 'datenschutzerklaerung-german.pdf', $headers);
    }

    public function dashboard() {
        try{
            $user = User::count();
            $user_total_balance = User::sum('wallet');

            $param=[];
            $body = [
                'params' => $param,
                'method' => 'getinfo',
            ];
            $curldata=$this->bitcoin_npmcurl($body);
            $getinfo = $curldata;

            /*$client = new Client;
            $balance = $client->get('https://ico.mcan.io/mcan/balance');
            $bal = json_decode($balance->getBody(),true);
            $getinfo['balance'] += $bal;*/

            $param=[];
            $body = [
                'params' => $param,
                'method' => 'getwalletinfo',
            ];
            $curldata=$this->bitcoin_npmcurl($body);
            $getwalletinfo = $curldata;


            //List Transactions
            $param=[];
            $body = [                  
            'params' => $param,
            'method' => 'listtransactions',
            ];
            $curldata=$this->bitcoin_npmcurl($body);
            $details = $curldata;

            $client = new Client;

            $response = $client->get('https://api.cryptonit.net/api/ticker/FBTC%2FBTC' , [

             'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                   
            ]);  
            
            $coin_details = json_decode($response->getBody(),true);
            
            $coin_value = $coin_details['last'];
            
            $client = new Client;

            $response = $client->get('http://206.81.15.128/api/getdifficulty' , [

                 'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                       
            ]);  
            $difficulty = json_decode($response->getBody(),true);


            $client = new Client;

            $response = $client->get('http://206.81.15.128/ext/getmoneysupply' , [

                 'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                       
            ]);  
            $supply = json_decode($response->getBody(),true);
            
            return view('admin.home',compact('getinfo','getwalletinfo','user','details','coin_value','difficulty','supply','user_total_balance'));
        }
        catch(Exception $e){
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function history()
    {
        try{


        	$History = UserTrasaction::orderBy('id','desc')->get();

        	return view('admin.history.index',compact('History'));

        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function settings()
    {
        try{
            return view('admin.settings.settings');
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function settings_store(Request $request)
    {
        //dd($request);
        $this->validate($request,[
                'site_title' => 'required',
                'site_url'=> 'required',
                'site_icon' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
                'site_logo' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
                'contact_no' => 'required',
                'contact_email' => 'required',
                'contact_website' => 'required',
                'coin_value' => 'required',
                'currency_symbol' => 'required',
            ]);

        try{

            if($request->hasFile('site_icon')) {

                $site_icon = $request->site_icon->store('settings');
                Setting::set('site_icon', $site_icon);
            }

           

            if($request->hasFile('site_logo')) {
                $site_logo = $request->site_logo->store('settings');
                Setting::set('site_logo', $site_logo);
            }

            if($request->hasFile('site_email_logo')) {
                $site_email_logo = $request->site_icon->store('settings');
                Setting::set('site_email_logo', $site_email_logo);
            }
            // dd($request->all());

            Setting::set('site_title', $request->site_title);
            Setting::set('site_url', $request->site_url);
            Setting::set('site_copyright', $request->site_copyright);
            Setting::set('contact_no', $request->contact_no);
            Setting::set('contact_email', $request->contact_email);
            Setting::set('contact_website', $request->contact_website);
            Setting::set('coin_value', $request->coin_value);
            Setting::set('currency_symbol', $request->currency_symbol);


            //Setting::set('usd_bdx', $request->usd_bdx);
            // Setting::set('kyc_approval', $request->kyc_approval == 'on' ? 1 : 0 );

            // Setting::set('store_link_android', $request->store_link_android);
            // Setting::set('store_link_ios', $request->store_link_ios);
            //dd(Setting);
            Setting::save();
            
            return back()->with('flash_success',trans('api.setting_status'));

        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }

    	
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings_payment()
    {
        try{
            return view('admin.payment.settings');
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    /**
     * Save payment related settings.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings_payment_store(Request $request)
    {
        $this->validate($request, [
                'CARD' => 'in:on',
                'CASH' => 'in:on',
                'PAYPAL' => 'in:on',
               
                'stripe_secret_key' => 'required_if:CARD,on|max:255',
                'stripe_publishable_key' => 'required_if:CARD,on|max:255',
                'currency' => 'required',
              

            ]);

        try{

            Setting::set('CARD', $request->has('CARD') ? 1 : 0 );
            Setting::set('CASH', $request->has('CASH') ? 1 : 0 );
            Setting::set('PAYPAL', $request->has('PAYPAL') ? 1 : 0 );
            // Setting::set('paypal_client_id', $request->paypal_client_id);
            // Setting::set('paypal_secret_key', $request->paypal_secret_key);
            Setting::set('stripe_secret_key', $request->stripe_secret_key);
            Setting::set('stripe_publishable_key', $request->stripe_publishable_key);
            Setting::set('increase_percentage', $request->increase_percentage);


            // Setting::set('e_wallet', $request->e_wallet);
            // Setting::set('p_wallet', $request->p_wallet);
            Setting::set('currency', $request->currency);
            Setting::set('referral', $request->referral);
            Setting::set('withdraw_time', $request->withdraw_time);
            Setting::set('withdraw_comission', $request->withdraw_comission);

            Setting::save();

            return back()->with('flash_success',trans('api.setting_status'));

        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function historySuccess($id)
    {
        try{

            $History = WithdrawHistory::findOrFail($id);
           
            $History->status = "SUCCESS";
            $History->save();

            return back()->with('flash_success',trans('api.success_status'));

        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function historyFailed($id)
    {

            $History = WithdrawHistory::findOrFail($id);

            $History->status = "FAILED";
            $History->save();

            $User = User::where('id',$History->user_id)->first();
            $User->wallet += $History->amount;
            $User->save();


            WalletPassbook::create([
                'user_id' => $History->user_id,
                'amount' => $History->amount,
                'status' => 'CREDITED',
                'via' => "WITHDRAW - FAILED",
                ]);



            return back()->with('flash_success',trans('api.success_status'));;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        try{

            return view('admin.account.profile');

        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile_update(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|max:255',
            'email' => 'required|max:255|email|unique:admins,email,'.Auth::guard('admin')->user()->id,
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
        ]);

        try{
            $admin = Auth::guard('admin')->user();
            $admin->name = $request->name;
            $admin->email = $request->email;
            
            if($request->hasFile('picture')){
                $admin->picture = $request->picture->store('admin/profile');  
            }
            $admin->save();

            return redirect()->back()->with('flash_success','Profile Updated');
        }

        catch (Exception $e) {
             return back()->with('flash_error', trans('api.something_went_wrong'));
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password()
    {
        try{
            return view('admin.account.change-password');
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password_update(Request $request)
    {

        $this->validate($request,[
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        try {

           $Admin = Admin::find(Auth::guard('admin')->user()->id);

            if(password_verify($request->old_password, $Admin->password))
            {
                $Admin->password = bcrypt($request->password);
                $Admin->save();

                return redirect()->back()->with('flash_success','Password Updated');
            }
        } catch (Exception $e) {
             return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function fiatHistory()
    {
        try{
            $History = WalletPassbook::orderBy('id','desc')->get();

            return view('admin.history.fiat',compact('History'));

        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }


    
    public function privacy(){

        try{

            return view('admin.pages.privacy')
                ->with('title',"Privacy Page")
                ->with('page', "privacy");

        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

   
    public function pages(Request $request){
        $this->validate($request, [
                'page' => 'required|in:page_privacy',
                'content' => 'required',
            ]);
        try{

            Setting::set($request->page, $request->content);
            Setting::save();

            return back()->with('flash_success',trans('api.content_updated'));

        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function terms(){
        try{
            return view('admin.pages.terms')
                ->with('title',"Terms Page")
                ->with('page', "terms");
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

   
    public function termspages(Request $request){
        $this->validate($request, [
                'terms' => 'required',
                'content' => 'required',
            ]);

        Setting::set($request->terms, $request->content);
        Setting::save();

        return back()->with('flash_success', trans('api.content_updated'));
    }

    public function transhistory($id)
    {
        
        try {

            //$User = User::with('transaction')->findOrFail($id);
            $History = UserTrasaction::where('user_id',$id)->get();
            return view('admin.user.history', compact('History'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.user.user_not_found'));
        }
    }

     public function kycdoc($id)
    {
        
        try {

            $Doc = KycDocument::where('user_id',$id)->get();
            
            return view('admin.user.document', compact('Doc'));
        } catch (Exception $e) {

            return back()->with('flash_error', trans('api.user.user_not_found'));
        }
    }

    public function approve($id)
    {
        try {

            $KycCount = KycDocument::where('user_id',$id)->count();
            $DocCount = Document::count();

            $Kyc = KycDocument::where('user_id',$id)->where('status',"!=","APPROVED")->get();

            if($KycCount == $DocCount)
            {

                if(count($Kyc) >= 1)
                {
                    return back()->with('flash_error',trans('api.kyc_not_verified'));
                } 

                $user = User::findOrFail($id);        
                $user->update(['kyc' => 1]);
                return back()->with('flash_success', trans('api.approved'));

            }else{
                return back()->with('flash_error', trans('api.not_submitted'));
            }

            
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error',  trans('api.something_went_wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function disapprove($id)
    {
        
        User::where('id',$id)->update(['kyc' => 0]);
        return back()->with('flash_error',trans('api.disapproved'));
    }

    public function userdocument_approve(Request $request)
    {
        $Kyc = KycDocument::where('user_id',$request->user_id)
                    ->where('document_id',$request->doc_id)
                    ->first();

        $Kyc->status = $request->status;

        $Kyc->save();

         return back()->with('flash_success',trans('api.success_status'));

    }

    public function userdocument_reject(Request $request)
    {
        $Kyc = KycDocument::where('user_id',$request->user_id)
                    ->where('document_id',$request->doc_id)
                    ->first();

        $Kyc->status = $request->status;

        $Kyc->save();

         return back()->with('flash_success',trans('api.success_status'));

    }

    public function coins($id)
    {
        $BTC = UserCoin::where('user_id',$id)->where('coin',"BTC")->first();
        $BCH = UserCoin::where('user_id',$id)->where('coin',"BCH")->first();
        $ETH = UserCoin::where('user_id',$id)->where('coin',"ETH")->first();
        $LTC = UserCoin::where('user_id',$id)->where('coin',"LTC")->first();
        $XRP = UserCoin::where('user_id',$id)->where('coin',"XRP")->first();
        $User= User::where('id',$id)->first();

        return view('admin.user.coin',compact('BTC','BCH','ETH','LTC','XRP','User'));
    }


    public function pendingwithdraw()
    {
        $History = WithdrawHistory::where('status',"PENDING")->get();

        return view('admin.history.withdraw',compact('History'));
    }

    public function allwithdraw()
    {
        $History = WithdrawHistory::get();

        return view('admin.history.withdraw',compact('History'));
    }


    public function editcoin(Request $request)
    {
         if($request->type == "FIAT")
         {
            $User = User::where('id',$request->user_id)->first();

            return $User;

         }else{


            if($request->coin_id == 0)
            {
                $Coin = new UserCoin;
                $Coin->user_id = $request->user_id;
                $Coin->coin = $request->type;
                $Coin->value = 0;
                $Coin->save();

                $User = User::where('id',$request->user_id)->first();

                return view('admin.user.editcoin',compact('Coin','User'));

            }else{

                $Coin = UserCoin::where('id',$request->coin_id)->first();
                $User = User::where('id',$request->user_id)->first();

                return view('admin.user.editcoin',compact('Coin','User'));

            }
         }
    }

    public function savecoin(Request $request)
    {
       

        $Usercoin = UserCoin::where('id',$request->id)->first();

        $Usercoin->value = $request->amount;
        $Usercoin->save();

        $id = $Usercoin->user_id;

        $BTC = UserCoin::where('user_id',$id)->where('coin',"BTC")->first();
        $BCH = UserCoin::where('user_id',$id)->where('coin',"BCH")->first();
        $ETH = UserCoin::where('user_id',$id)->where('coin',"ETH")->first();
        $LTC = UserCoin::where('user_id',$id)->where('coin',"LTC")->first();
        $XRP = UserCoin::where('user_id',$id)->where('coin',"XRP")->first();
        $User= User::where('id',$id)->first();

        return view('admin.user.coin',compact('BTC','BCH','ETH','LTC','XRP','User'));



    }

    public function translation(){

        try{
            return view('admin.translation');
        }

        catch (Exception $e) {
             return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }


    public function user_history($id)
    {
        $User = User::where('id',$id)->first();
        $name = $User->name;

        //List Transactions
        $param=[$name,10,0];
        $body = [                  
            'params' => $param,
            'method' => 'listtransactions',
        ];


        if($User->network == 'BTC'){
            $curldata=$this->bitcoin_npmcurl($body);

            }
            if($User->network == 'BCH'){
            $curldata=$this->bitcoincash_npmcurl($body);
            }
            if($User->network == 'LTC'){
            $curldata=$this->litecoin_npmcurl($body);
            }

        
        $details = $curldata;
 
        
            return view('admin.history.index',compact('details'));
           
    }

    public function userallblock(){

        $user=User::get();
        foreach ($user as $value) {
            if($value->block_status==1){
                $value->block_status=0;
            }else{
                $value->block_status=1;
            }            
            $value->save();
        } 

        return back()->with('flash_success',"Users block status updated successfully...");   
    }

    public function userblock($id=null){

        $user=User::findOrFail($id); 
        
        //echo $user->block_status;
        
        if($user->block_status==1){
            $user->block_status=0;
        }else{
            $user->block_status=1;
        }
        
        $user->save();

        return back()->with('flash_success',"User block status updated successfully...");   

       
    }

    public function currencyindex(){
        $currency=Currency::get();
        return view('admin.currency.index',compact('currency'));
    }

    public function currencyadd(){
        return view('admin.currency.add');
    }

    public function currencystore(Request $request){

        $currency_name=strtoupper($request->currency);
        $currency=Currency::where('currency','=',$currency_name)->first();
        
        if(!$currency){

            $currency1=new Currency;
            
            $currency1->currency=$currency_name;
            $currency1->coin_value=$request->coin_value;
            $currency1->save();

            return back()->with('flash_success','Currency added successfully...');
        
        }else{
            
            return back()->with('flash_error','Already this currency exist...');
        }

    }

    public function currencyedit($id=null){
        $currency=Currency::findOrFail($id);
        return view('admin.currency.edit',compact('currency'));
    }

    public function currencyupdate(Request $request)
    {
        $id=$request->currency_id;

        $currency_name=strtoupper($request->currency);

        $currency1=Currency::where('id','!=',$id)->where('currency','=',$currency_name)->first();
        
        if(!$currency1){
            
            $currency=Currency::findOrFail($id);
            $currency->currency=$currency_name;
            $currency->coin_value=$request->coin_value;
            $currency->save();
            
            return back()->with('flash_success','Currency updated successfully...');
        }

        return back()->with('flash_error','Currency Already Exist...');
    }

    public function npmcurl($body){
        
        try{
            $id=0;
            $status       = null;
            $error        = null;
            $raw_response = null;
            $response     = null;
            
            $proto='http';
            $username = 'liorpc';
            $password = 'E8mnS4yo97Ue3Jazb5VPh6HpM2sWhRgK3iyfn3HimTZv';
            $host = '127.0.0.1';
            $port = '8668';
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
                'method' => $method,
                'params' => $params,
                'id'     => $id
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
                return $response;
                //return false;
            }
            return $response;
            //return $response['result'];
        }catch(Exception $e){
        }
    }

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
            $host ="127.0.0.1";
            $port ="8332";
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
            return $response['result'];
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


    public function testapi(){

        
        $email="test@gmail.com";

        $param=[$email];
        
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
        ];

        $body = [
            'params' => $param,
            'method' => 'getaccountaddress',
        ];
        

        $lio_url ="http://localhost/liotest/lioapi.php";

        $response = $client->request('POST',$lio_url, [
            'headers' => $headers,
            'form_params' => [
                'params' => $param,
                'method' => 'getaccountaddress',
                ],
        ]);

        return $response;
        
        $curldata1 = json_decode($lio_res->getBody(),true);

    }

    public function guzzlesaveuser(Request $request){
        
        try{
            $userold=User::where('email','=',$request['email'])->first();

            if(count($userold)==0){

                
                $email=$request['email'];

                $userdata=[
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'password' => bcrypt($request['password']),                    
                    'email_token' => base64_encode($request['email']),
                ];
                    
                //return json_decode($userdata,true);

                User::create($userdata);

                //Mail::to($email)->send(new Signupwelcomemail($userdata));
        
            }

            return 1;


        }catch(Exception $e){

            return 0;
        }
    }
    
}
