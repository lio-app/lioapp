<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','dob', 'password','address','verified','email_token','google2fa_secret','device_type','device_token','device_id','wallet','g2f_temp','referred_by','app_pin','app_pin_status','send_email_status','receive_email_status','ip','coin','mobile','eth_address','btc_address','xrp_address','x_remember_flag_port','x_remember_flag_star','bch_address','ltc_address','network','phrase_word','coin_types','fiat_currency'
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at'
    ];


    public function userbalance($id){

        $user=User::findOrFail($id);
        //getbalance
        $param=[$user->name,1];
        $body = [
            'params' => $param,
            'method' => 'getbalance',
        ];


            if($user->network == 'BTC'){
            $curldata=$this->bitcoin_npmcurl($body);

            }
            if($user->network == 'BCH'){
            $curldata=$this->bitcoincash_npmcurl($body);
            }
            if($user->network == 'LTC'){
            $curldata=$this->litecoin_npmcurl($body);
            }


        return $curldata['result'];

    }


   

}
