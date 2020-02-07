<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use Exception;
use Log;
use Setting;

class SendPushNotification extends Controller
{
	
    /**
     * New Incoming request
     *
     * @return void
     */
    public function SendTransaction($sender,$amt){

        //$msg="You have sent ".$amt." BDX";
        $msg="You’ve successfully sent ".$amt." FBTC coins from your wallet";
        //return $this->sendPushToUser($sender, trans('api.push.incoming_request'));
        return $this->sendPushToUser($sender, $msg);

    }  

    public function ReceiveTransaction($receiver,$amt){

        //$msg="You have received ".$amt." BDX";
        $msg="You’ve successfully received ".$amt." FBTC coins in your wallet";
        return $this->sendPushToUser($receiver, $msg);

    }   


    /**
     * Sending Push to a user Device.
     *
     * @return void
     */
    public function sendPushToUser($user_id, $push_message){

    	try{

	    	$user = User::findOrFail($user_id);

            if($user->device_token != ""){

                \Log::info('sending push for user : '. $user->name);

    	    	if($user->device_type == 'ios'){

    	    		return \PushNotification::app('IOSUser')
    		            ->to($user->device_token)
    		            ->send($push_message);

    	    	}elseif($user->device_type == 'android'){
    	    		
    	    		return \PushNotification::app('AndroidUser')
    		            ->to($user->device_token)
    		            ->send($push_message);

    	    	}
            }

    	} catch(Exception $e){
    		return $e;
    	}

    }


}
