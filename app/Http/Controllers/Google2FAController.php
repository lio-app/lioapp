<?php

namespace App\Http\Controllers;
use ValidatesRequests;
use Auth;
use Cache;
use Crypt;
use Google2FA;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Foundation\Validation\ValidatesRequests;
use \ParagonIE\ConstantTime\Base32;
use App\Http\Requests\ValidateSecretRequest;
use App\User;

class Google2FAController extends Controller
{
    

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function enableTwoFactor(Request $request)
    {
        //generate new secret
        $secret = $this->generateSecret();

        //get user
        $user = $request->user();

        //encrypt and then save secret
        //$user->google2fa_secret = Crypt::encrypt($secret);
        $user->g2f_temp = Crypt::encrypt($secret);
        $user->save();

        //generate image for QR barcode
        $imageDataUri = Google2FA::getQRCodeInline(
            $request->getHttpHost(),
            $user->email,
            $secret,
            200
        );

        return view('2fa/enableTwoFactor', ['image' => $imageDataUri,
            'secret' => $secret]);
        
    }    

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function disableTwoFactor(Request $request)
    {
        $user = $request->user();
        //make secret column blank
        $user->google2fa_secret = null;
        //$user->g2f_temp = null;
        $user->save();

        return view('2fa/disableTwoFactor');
        
    }    

    /**
     * Generate a secret key in Base32 format
     *
     * @return string
     */
    private function generateSecret()
    {
        $randomBytes = random_bytes(10);

        return Base32::encodeUpper($randomBytes) ;
    }

    public function g2fotpcheckenable(Request $request)
    {    
        
        $userId = Auth::user()->id;        
        //$value=$request->totp;
        $key    = $userId . ':' . $request->totp;        
        //$secret = Crypt::decrypt(Auth::user()->google2fa_secret);
        $secret = Crypt::decrypt(Auth::user()->g2f_temp);
        $temp=Google2FA::verifyKey($secret,$request->totp);

        $response=[];
        $status=0;
        $message="";

        if(!Cache::has($key)){
            if($temp==true){
                Cache::add($key, true, 4);

                $user=User::findOrFail($userId);
                $user->google2fa_secret = $user->g2f_temp;
                $user->g2f_temp = Null;
                $user->save();

                $status=1;
                $message="Google two factor authentication enabled successfully...";

                //\Session::flash('flash_success',"Google two factor authentication enabled successfully...");

            }else{
                $status=0;
                $message="Please check the otp, and try again...";
                //\Session::flash('flash_error',"Please check the otp, and try again...");
            }
        }else{

            $status=0;
            $message="Used token,Cannot reuse token...";
            //\Session::flash('flash_error',"Used Token,Cannot reuse token...");            
        }         
        //return redirect('/security');
        //$response=['status'=>$status,'message'=>$message];

        return response()->json(['status'=>$status,'message'=>$message], 200); 
    }

    public function enableTwoFactorapi(Request $request)
    {
        try{
            //generate new secret
            $secret = $this->generateSecret();

            //get user
            $user = $request->user();

            //encrypt and then save secret
            //$user->google2fa_secret = Crypt::encrypt($secret);
            $user->g2f_temp = Crypt::encrypt($secret);
            $user->save();
            
            return response()->json(['secret' => $secret], 200); 

        } catch (Exception $e) {
             return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
        
    }

    public function disableTwoFactorapi(Request $request)
    {
        try{
            $user = $request->user();
            //make secret column blank
            $user->google2fa_secret = null;
            //$user->g2f_temp = null;
            $user->save(); 

            return response()->json(['message' => 'Disabled Successfully'], 200); 
            
        } catch (Exception $e) {
             return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
       
    }

    //public function postValidateToken(Request $request)
    public function gfavalidateotp(Request $request)
    {        
        $userId = Auth::user()->id;        
        //$value=$request->totp;
        $key    = $userId . ':' . $request->totp;        
        $secret = Crypt::decrypt(Auth::user()->google2fa_secret);
        $temp=Google2FA::verifyKey($secret,$request->totp);

        if(!Cache::has($key)){
            if($temp==true){
                Cache::add($key, true, 4);
                // Auth::loginUsingId($userId);
                return response()->json(['status'=>1,'message' =>'Logged Successfully'], 200);  
            }else{
                return response()->json(['status'=>0,'message' =>'Token Mismatch'], 200);   
            }
        }else{
            //echo "Used TOken,Cannot reuse token";
            return response()->json(['status'=>0,'message' =>'Used Token,Cannot reuse token'], 200); 
        }         
    }
}
