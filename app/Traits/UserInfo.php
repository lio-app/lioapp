<?php 
namespace App\Traits;

use App\Commission;
use App\User;
use App\Buytrade;
use App\Selltrade;
use App\Wallet;
use App\TranstableBtc;
use App\TranstableEth;

use App\TranstableXrp;
use App\BtcAdminAddress;
use App\EthAdminAddress;

use App\XrpAdminAddress;
use Illuminate\Support\Facades\Crypt;
use App\Traits\UserInfo;
use App\Buytradebtcusd;
use App\Selltradebtcusd;


trait UserInfo {

     //commission
     function adminCommissionBtc($amount)
     {

        $btcDeatils = Commission::where('source', 'BTC')->first();
        if($btcDeatils){
            $commission = ($btcDeatils->transaction / 100) * $amount;
            if($commission < 0.0001){
                return floatval(0.0001);
            } else {
                return floatval($commission);
            }
        } else {
            return false;
        }
    }


    function adminCommissionEth($amount)
    {
        $ethDeatils = Commission::where('source', 'ETH')->first();
        if($ethDeatils){
            $commission = ($ethDeatils->transaction / 100) * $amount;
            if($commission < 0.00042){
                return floatval(0.00042);
            } else {
                return floatval($commission);
            }
        } else {
            return false;
        }
    }

     function adminCommissionLtc($amount)
    {
        $ltcDeatils = Commission::where('source', 'LTC')->first();
        if($ltcDeatils){
            $commission = ($ltcDeatils->transaction / 100) * $amount;
            if($commission < 0.00042){
                return floatval(0.00042);
            } else {
                return floatval($commission);
            }
        } else {
            return false;
        }
    }


     function adminCommissionXrp($amount)
     {
        $xrpDeatils = Commission::where('source', 'XRP')->first();
        if($xrpDeatils){
            $commission = ($xrpDeatils->transaction / 100) * $amount;
            if($commission < 0.00042){
                return floatval(0.00042);
            } else {
                return floatval($commission);
            }
        } else {
            return false;
        }
    }
    function adminCommissionUsd($amount)
     {
        $usdDeatils = Commission::where('source', 'USD')->first();
        if($usdDeatils){
            $commission = ($usdDeatils->transaction / 100) * $amount;
            if($commission < 0.00042){
                return floatval(0.00042);
            } else {
                return floatval($commission);
            }
        } else {
            return false;
        }
    }

    function userbalanceupdateusd($uid,$amount,$option=NULL)
    {
        $balance = Wallet::where('uid', $uid )->first();
        $real = $balance->usd_mathipu;
        if($option == 'debit'){
            $main = $real - $amount;
            /*$update = array(
                'usd_mathipu'   => $main,
                'modified'      => date('Y-m-d',time())
            );
            $action = parent::dbRowUpdate(USER_WALLET, $update,array('user_id' => $uid));*/
            Wallet::where(['uid' => $uid])->update(['USD'   => $main,'updated_at'  => date('Y-m-d',time())]);

        }else{
            $main = $real + $amount;
          /*  $update = array(
                'usd_mathipu'   => $main,
                'modified'      => date('Y-m-d',time())
            );
            $action = parent::dbRowUpdate(USER_WALLET, $update,array('user_id' => $uid));*/

            Wallet::where(['uid' => $uid])->update(['usd_mathipu'   => $main,'updated_at'  => date('Y-m-d',time())]);

        }
        return true;
        
    }


    //User Address
    function getUserBtcAddress($uid)
    {
        
        $userAddress = User::where('id', $uid)->with('userBtcDetails')->first();       
        if($userAddress) 
        {
                  if(isset($userAddress->userBtcDetails->address))
                  {
                      return Crypt::decryptString($userAddress->userBtcDetails->address);
                  }
                  else
                  {
                       return false;
                  }
        } else {
            return false;
        }
    }

    function getUserEthAddress($uid)
    {
        $userAddress = User::where('id', $uid)->with('userEthDetails')->first();       
        if($userAddress) 
        {
                 if(isset($userAddress->userEthDetails->address))
                 {
                      return Crypt::decryptString($userAddress->userEthDetails->address);
                 }
                 else
                 {
                    return false;
                 }
        } else {
            return false;
        }
    }

    function getUserLtcAddress($uid)
    {
        $userAddress = User::where('id', $uid)->with('userLtcDetails')->first();      
        if($userAddress) 
        {
               if(isset($userAddress->userLtcDetails->address))
               {
                   return Crypt::decryptString($userAddress->userLtcDetails->address);
               }
               else
               {
                   return false;
               }
           
        } else {
            return false;
        }

    }

     function getUserXrpAddress($uid)
    {
        $userAddress = User::where('id', $uid)->with('userXrpDetails')->first();  
         
        if($userAddress) 
        {
                if(isset($userAddress->userXrpDetails->address))
                {
                    return Crypt::decryptString($userAddress->userXrpDetails->address);
                }
                else
                {
                     return false;
                }
        } else {
            return false;
        }

    }


     //User Balance
     function getUserBtcBalance()
     {

        $balance = 0.00000;
        $balance1 = 0.00000;
        $sendamount = 0.00000;
        $btcDeatils = Commission::where('source', 'BTC')->first();
        $commission = ($btcDeatils->transaction / 100);
        $userAddress = User::where('id', \Auth::id())->with('userBtcDetails')->first();
        if($userAddress){
             if(isset($userAddress->userBtcDetails->address))
             {
                   $address = $userAddress->userBtcDetails->address;
                   $sendamount = $this->pendingBalancesendBtc($address);
             }
          
        }
        $results = Selltrade::where([
                        ['uid', \Auth::id()],
                        ['remaining', '!=', 0],
                        ['pair', '=', 1],
                    ])->orderBy('id', 'asc')->get();
        if($results->count() > 0) {
            foreach($results as $bal) {
                $adminCommission = $commission * $bal->remaining;
                $adminTo = $adminCommission + $bal->remaining;
                $balance+= $adminTo + 0.0005;
            }
        }  

        $results1 = buytrade::where([
                        ['uid', \Auth::id()],
                        ['remaining', '!=', 0]
                        ])->whereIn('pair', array(2, 3, 4))->orderBy('id', 'asc')->get();
        if($results1->count() > 0) {
            foreach($results1 as $bal1) {
                $tbal = $bal1->remaining * $bal1->amount;
                $adminCommission1 = $commission * $tbal;
                $adminTo1 = $adminCommission1+$tbal;
                $balance1+= $adminTo1  + 0.0005;
            }
        }  
        $balance = $balance + $balance1;
        $set = Wallet::where('uid', \Auth::id())->where('currency','BTC')->first();

        $btc = $set->balance;
        $balance = bcadd($balance , $sendamount,8);
        $actual = bcsub($btc , $balance, 8);
        if($actual <= 0){
            return number_format(0.00000000, 8);
        } else {
            return $actual;
        }
    }

     function getUserEthBalance()
     {
        // dd('eth balance get');
      $balance = 0.00000;
        $sendamount = 0.00000;
        $buyBalance = 0.00000;
        $sellBalance = 0.00000;

        $eth = Commission::where('source', 'ETH')->first();
        $commission = ($eth->withdraw / 100);

        $userAddress = User::where('id', \Auth::id())->with('userEthDetails')->first();

        if($userAddress){
            $address = $userAddress->userEthDetails->address;
            $sendamount = $this->pendingBalancesendEth($address);
        }

        // $results = json_decode(parent::dbSelectAll(SELL_TRADE." WHERE uid=$uid  AND remaining!=0  AND pair=2",'ORDER BY id ASC'));

        $buyResults = Buytrade::where([
                        ['uid', \Auth::id()],
                        ['remaining', '!=', 0],
                        ['pair', '=', 4],
                    ])->orderBy('id', 'asc')->get();
        if($buyResults->count() > 0) {
            foreach($buyResults as $bal) {

                $total = bcmul($bal->remaining, $bal->price, 8);
                $adminCommission = bcmul($commission, $total, 8);
                $adminTo = bcadd($adminCommission, $total, 8);
                $buyBalance += $adminTo + 0.0021;
            }
        }


         $results = Selltrade::where([
                        ['uid', \Auth::id()],
                        ['remaining', '!=', 0],
                        ['pair', '=', 4],
                    ])->orderBy('id', 'asc')->get();

        if($results->count() > 0) 
        {
            foreach($results as $bal) {
                $adminCommission = bcmul($commission, $bal->remaining, 8);
                $adminTo = bcadd($adminCommission, $bal->remaining, 8);
                $sellBalance += $adminTo + 0.0021;
            }
        }      
      
         $balance = bcadd($buyBalance , $sellBalance, 8);   
        $set = Wallet::where('uid', \Auth::id())->where('currency','ETH')->first();
        
        $eth = $set->balance;
        
        $balance = bcadd($balance , $sendamount,8);
        $actual = bcsub($eth , $balance, 8);

        if($actual <= 0){
            return number_format(0.00000000, 8);
        } else {
            return $actual;
        }
        
    }


     function getUserLtcBalance()
     {
        $balance = 0.00000;
        $sendamount = 0.00000;
        $ltc = Commission::where('source', 'LTC')->first();
        $commission = ($ltc->transaction / 100);
        $userAddress = User::where('id', \Auth::id())->with('userLtcDetails')->first();
        if($userAddress){
            if(isset($userAddress->userLtcDetails->address))
            {
                $address = $userAddress->userLtcDetails->address;
                $sendamount = $this->pendingBalancesendLtc($address);
            }
            
        }
        $results = Selltrade::where([
                        ['uid', \Auth::id()],
                        ['remaining', '!=', 0],
                        ['pair', '=', 4],
                    ])->orderBy('id', 'asc')->get();
        if($results->count() > 0) 
        {
            foreach($results as $bal) {
                $adminCommission = $commission * $bal->remaining;
                $adminTo = $adminCommission + $bal->remaining;
                $balance += $adminTo + 0.0021;
            }
        } 
        $balance = $balance;
        $set = Wallet::where('uid', \Auth::id())->first();
        $ltc = $set->ltc_mathipu;
        $balance = bcadd($balance , $sendamount,8);
        $actual = bcsub($ltc , $balance, 8);
        if($actual <= 0){
            return number_format(0.00000000, 8);
        } else {
            return $actual;
        }
        
     }
    

     function getUserXrpBalance()
     {
        $balance = 0.00000;
        $sendamount = 0.00000;
        $xrp = Commission::where('source', 'XRP')->first();
        $commission = ($xrp->transaction / 100);
        $userAddress = User::where('id', \Auth::id())->with('userXrpDetails')->first();
        if($userAddress){
            if(isset($userAddress->userXrpDetails->address))
            {
                $address = $userAddress->userXrpDetails->address;
                $sendamount = $this->pendingBalancesendXrp($address);
            }
          
        }
        $results = Selltrade::where([
                        ['uid', \Auth::id()],
                        ['remaining', '!=', 0],
                        ['pair', '=', 3],
                    ])->orderBy('id', 'asc')->get();

        if($results->count() > 0) 
        {
            foreach($results as $bal) {
                $adminCommission = $commission * $bal->remaining;
                $adminTo = $adminCommission + $bal->remaining;
                $balance += $adminTo + 0.0005;
            }
        }      
        $set = Wallet::where('uid', \Auth::id())->first();
        $xrp = $set->xrp_mathipu;
        $balance = bcadd($balance , $sendamount,8);
        $actual = bcsub($xrp , $balance, 8);
        if($actual <= 0){
            return number_format(0.00000000, 8);
        } else {
            return $actual;
        }

     }

    function userrealbalanceusd($uid){
        $balance = 0.00000;
        $balance1 = 0.00000;
        $usd = Commission::where('source', 'USD' )->first();
        if($usd->transaction!=0){
            $commission = ($usd->transaction / 100);
        }else{
            $commission = 0;
        }
        $results = json_decode(parent::dbSelectAll(BUY_TRADE_BTCUSD." WHERE uid=$uid AND  remaining!=0 ",'ORDER BY amount ASC'));
        if($results) {
            foreach($results->result as $bal) {
                $tbal = $bal->remaining * $bal->amount;
                $adminCommission = $commission * $tbal;
                $adminTo = $adminCommission+$tbal;
                $balance+= $adminTo;
            }
        }
        $results1 = json_decode(parent::dbSelectAll(BUY_TRADE_ETHUSD." WHERE uid=$uid AND  remaining!=0 ",'ORDER BY amount ASC'));
        if($results1) {
            foreach($results1->result as $bal1) {
                $tbal1 = $bal1->remaining * $bal1->amount;
                $adminCommission1 = $commission * $tbal1;
                $adminTo1 = $adminCommission1 + $tbal1;
                $balance1+= $adminTo1;
            }
        }
        $balance = $balance + $balance1 ;
        $set = Wallet::where('uid', $uid )->first(); 
        $usd    = $set->usd_mathipu;
        
        $actual = bcsub($usd , $balance, 8);
        if($actual <= 0){
            return number_format(0.00000000, 8);
        } else {
            return $actual;
        }
    }

     //pending balance
     function pendingBalancesendBtc($address)
     {
        $address = Crypt::decryptString($address);
        $amount = 0.00000;
        $trade_amount = 0.00000;
        $count = 0;
        $fees = 0.0002;
        $trans = TranstableBtc::where([
                        ['fromaddr', $address],
                        ['status', 0]
                    ])->get();
        $trade_amount = 0;
        if($trans->count() > 0){
            foreach($trans as $transaction){
                $count = $count + 1;
                $amount += $transaction->amount1;
            }
            $admin_comm = $this->adminCommissionBtc($amount);
            $tranfee= $count * $fees;
            $trade_amount = bcadd(bcadd($amount , $admin_comm,8) , $tranfee, 8);
        }
        return $trade_amount;
    }

    function pendingBalancesendEth($address)
    {

        $amount = 0.00000;
        $trade_amount = 0.00000;
        $count = 0;
        $fees = 0.00084;
        $trans = TranstableEth::where([
                        ['fromaddr', $address],
                        ['status', 0]
                    ])->get();

        if($trans->count())
        {
            
            foreach($trans as $transaction){                
                $count = $count + 1;
                $amount+= $transaction->amount1;
            }
            $admin_comm = self::adminCommissionEth($amount);
            $tranfee= $count * $fees;
            $trade_amount = bcadd(bcadd($amount , $admin_comm,8) , $tranfee, 8);
        }


        return $trade_amount;
    }

     function pendingBalancesendLtc($address)
     {
        $amount = 0.00000;
        $trade_amount = 0.00000;
        $count = 0;
        $fees = 0.00084;
        $trans = TranstableLtc::where([
                        ['fromaddr', $address],
                        ['status', 0]
                    ])->get();
        if($trans->count())
        {
            foreach($trans as $transaction){                
                $count = $count + 1;
                $amount+= $transaction->amount1;
            }
            $admin_comm = self::adminCommissionLtc($amount);
            $tranfee= $count * $fees;
            $trade_amount = bcadd(bcadd($amount , $admin_comm,8) , $tranfee, 8);
        }
        
        return $trade_amount;
    }


    function pendingBalancesendXrp($address)
    {
        $amount = 0.00000;
        $trade_amount = 0.00000;
        $count = 0;
        $fees = 0.00084;
        $trans = TranstableXrp::where([
                        ['fromaddr', $address],
                        ['status', 0]
                    ])->get();
        if($trans->count())
        {
            foreach($trans as $transaction){                
                $count = $count + 1;
                $amount+= $transaction->amount1;
            }
            $admin_comm = self::adminCommissionXrp($amount);
            $tranfee= $count * $fees;
            $trade_amount = bcadd(bcadd($amount , $admin_comm,8) , $tranfee, 8);
        }
        
        return $trade_amount;

    }
    function pendingBalancesendUsd($address)
    {
        $amount = 0.00000;
        $trade_amount = 0.00000;
        $count = 0;
        $fees = 0.0001;
        $trans = TranstableUsd::where([
                        ['fromaddr', $address],
                        ['status', 0]
                    ])->get();
        if($trans->count())
        {
            foreach($trans as $transaction){                
                $count = $count + 1;
                $amount+= $transaction->amount1;
            }
            $admin_comm = self::adminCommissionUsd($amount);
            $tranfee= $count * $fees;
            $trade_amount = bcadd(bcadd($amount , $admin_comm,8) , $tranfee, 8);
        }
        
        return $trade_amount;

    }

    //Admin Address
    function btcAdminaddress()
    {
        $address =  BtcAdminAddress::first();
        if($address)
            return $address;
        else
            return false;
    }

    function ethAdminaddress()
    {
        $address =  EthAdminAddress::first();
        if($address)
            return $address;
        else
            return false;
    }

     function ltcAdminaddress()
     {
        $address =  LtcAdminAddress::first();
        if($address)
            return $address;
        else
            return false;
     }

     function xrpAdminaddress()
     {
        $address =  XrpAdminAddress::first();
        if($address)
            return $address;
        else
            return false;
     }
     
      function useravailbalance($uid){
        $select = array('uid' => $uid);
        $set = Wallet::where($select)->first();
        $result['btc']  = $set->btc_mathipu;
        $result['eth']  = $set->eth_mathipu;
        $result['ltc']  = $set->ltc_mathipu;
        $result['xtp']  = $set->xrp_mathipu;
        $result['xof']  = $set->xof_mathipu;
       return json_encode($result);
    }

     //update user balance
     function trade_userbalanceupdate_btc($uid,$amount,$option=NULL){
        $balance = json_decode($this->useravailbalance($uid));
        $real = $balance->btc;
        if($option == 'debit'){
            $main = $real - $amount;
            $update = array(
                'btc_mathipu'   => $main
            );
             $action=Wallet::where(['uid' => $uid])->update($update);
            }else{
            $main = $real + $amount;
            $update = array(
                'btc_mathipu'   => $main
            );
            $action=Wallet::where(['uid' => $uid])->update($update);
        }
        return true;
    }

    function trade_userbalanceupdate_eth($uid,$amount,$option=NULL){
        $balance = json_decode($this->useravailbalance($uid));
        $real = $balance->eth;
        if($option == 'debit'){
            $main = $real - $amount;
            $update = array(
                'eth_mathipu'   => $main
            );
             $action=Wallet::where(['uid' => $uid])->update($update);
            }else{
            $main = $real + $amount;
            $update = array(
                'eth_mathipu'   => $main
            );
            $action=Wallet::where(['uid' => $uid])->update($update);
        }
        return true;
    }

    function trade_userbalanceupdate_ltc($uid,$amount,$option=NULL){
        $balance = json_decode($this->useravailbalance($uid));
        $real = $balance->ltc;
        if($option == 'debit'){
            $main = $real - $amount;
            $update = array(
                'ltc_mathipu'   => $main
            );
             $action=Wallet::where(['uid' => $uid])->update($update);
            }else{
            $main = $real + $amount;
            $update = array(
                'ltc_mathipu'   => $main
            );
            $action=Wallet::where(['uid' => $uid])->update($update);
        }
        return true;
    }

      function trade_userbalanceupdate_xrp($uid,$amount,$option=NULL){
        $balance = json_decode($this->useravailbalance($uid));
        $real = $balance->xrp;
        if($option == 'debit'){
            $main = $real - $amount;
            $update = array(
                'xrp_mathipu'   => $main
            );
             $action=Wallet::where(['uid' => $uid])->update($update);
            }else{
            $main = $real + $amount;
            $update = array(
                'xrp_mathipu'   => $main
            );
            $action=Wallet::where(['uid' => $uid])->update($update);
        }
        return true;
    }


       //Withdraw
    function withdrawCommission_eth($amount){
        $ethDeatils = Commission::where('source', 'ETH')->first();
        if($ethDeatils){
            $commission = ($amount / 100) * $ethDeatils->withdraw;
                if($commission < 0.00042){
                    return floatval(0.00042);
                }
                $commission = bcdiv($commission, 1, 8); 
                return bcdiv($commission, 1, 8);
            }
        }
        
        function xrp_account_validation($address)
        {
                if($address)
                {
                    $url = 'https://data.ripple.com/v2/accounts/'.$address;
                    $data = self::cUrl($url);
                    return $data;
                }
        }

        function cUrl($url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                $result = 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            return json_decode($result, true);
        }    

        //market limit
        /*function calculatecountbuy($volume,$uid,$pair)
        {  
            $trade = Selltrade::where([
                        ['uid', '!=', $uid],
                        ['remaining', '!=', 0],
                        ['pair', '=', $pair],
                        ['ordertype', '=', 1],
                    ])->orderBy('amount', 'ASC')->get();   
            $count = 0;
            $avail_capacity = 0.00000000; 
            foreach($trade->result as $capacity){
                    $avail_capacity+=$capacity->volume;
                    if($avail_capacity < $volume){
                        $count++;
                    } else if($avail_capacity >= $volume) {
                        $count = $count + 1;
                            break;
                    }
                }
                return $count;
        }*/    

    
 }