<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use Auth;
use App\User;
use App\Modals\Wallet;

use App\Modals\BtcAdminAddress;
use App\Modals\UserBtcTransaction;
use App\Modals\BtcAdminTransaction;
use App\Traits\Bitcoin;

use App\Modals\Mugawaribtc;

trait BtcClass
{
  use Bitcoin;

  public function sends($to_address, $total_send_amount, $from_address, $thiravi, $fee)
  {
    $send = $this->send($to_address, $total_send_amount, $from_address, $thiravi, $fee);
  }


  /*  this is node address creation */

// public function btc_user_address_create($uid) {    
//   $btc = $this->createaddress_btc();

//   $address = $btc->address;
//   $publickey = Crypt::encryptString($btc->publickey);
//   $wif = Crypt::encryptString($btc->wif);
//   $privatekey = Crypt::encryptString($btc->privatekey);
//   $security = User::on('mysqluser')->where('id', $uid)->first();
//   $label='Thore_'.$security->name;
//   $credential = $publickey.','.$wif.','.$privatekey;

//   $checkbtc=Mugawaribtc::where('user_id',$uid)->first();

//   if($checkbtc == ''){

//     Mugawaribtc::create([
//       'user_id' => $uid,
//       'label' => $label,
//       'mugawari' => Crypt::encryptString($address),
//       'thiravi' => $credential,
//       'thogai' => 0.00,
//     ]);

//     $someModel = new Mugawaribtc;
//     $someModel->setConnection('mysqluser');
//     $someModel->user_id = $uid; 
//     $someModel->label = $label;
//     $someModel->mugawari =Crypt::encryptString($address);
//     $someModel->save();

//     return $address;
//   }
// }


  /* Address creation for bitcore lib  curl*/
  public function create_user_btc($uid) {

    $address = Mugawaribtc::where('user_id',$uid)->value('mugawari');
    if(!$address){

// dd('node '.base_path().'/block_btc/generate_btc.js');
      $btc = json_decode(exec('node '.base_path().'/block_btc/generate_btc.js 2>&1'));

      $address = $btc->address;
      $publickey = Crypt::encryptString($btc->publickey);
      $wif = Crypt::encryptString($btc->wif);
      $privatekey = Crypt::encryptString($btc->privatekey);

      $security = User::on('mysqluser')->where('id', $uid)->first();
      $label='Thore_'.$security->name;

      $btcaddress = new Mugawaribtc;
      $credential = $publickey.','.$wif.','.$privatekey;
      $btcaddress->user_id = $uid;
      $btcaddress->label = $label;
      $btcaddress->mugawari = Crypt::encryptString($address);
      $btcaddress->thiravi = $credential;
      $btcaddress->thogai = 0.00000000;
      $btcaddress->save();

      $userbtctable = new Mugawaribtc;
      $userbtctable->setConnection('mysqluser');
      $userbtctable->user_id = $uid;
      $userbtctable->mugawari = Crypt::encryptString($address);
      $userbtctable->label = $label;
      $userbtctable->save();
    } else
    {
      $b_address = $address;
    }

    $walletaddress = Wallet::on('mysqluser')->where(['uid'=> $uid,'currency' => 'BTC'])->first();

    if(!$walletaddress){
      $walletaddress = new Wallet; 
      $walletaddress->setConnection('mysqluser');
      $walletaddress->uid = $uid;
      $walletaddress->currency = 'BTC';
    }

    $walletaddress->mukavari    = $address; 
    $walletaddress->balance     = 0.00000000; 
    $walletaddress->escrow_balance  = 0.00000000; 
    $walletaddress->created_at    = date('Y-m-d H:i:s',time()); 
    $walletaddress->updated_at    = date('Y-m-d H:i:s',time()); 
    $walletaddress->save();

    return $address;
  }


  /* Address creation for bitcore lib  curl*/
  public function create_user_btc1() {

      // dd('node '.base_path().'/block_btc/generate_btc.js');
      $btc = json_decode(exec('node '.base_path().'/block_btc/generate_btc.js 2>&1'));
      $address = $btc->address;
      $publickey = Crypt::encryptString($btc->publickey);
      $wif = Crypt::encryptString($btc->wif);
      $privatekey = Crypt::encryptString($btc->privatekey);

    
    return $address;
  }



  public  function btcUserTransactions($uid)
  {
    $currency ='BTC';
    $sel = Mugawaribtc::where([['user_id', '=', $uid]])->first();
    if($sel){            
      $address1 = Crypt::decryptString($sel->address);
      $address = $this->getTransactions($address1); 
      if($address){
        if(is_object($address) && !property_exists($address,'error')) {
          if(count($address->txs) > 0 ){
            foreach($address->txs as $addr){ 

              $tx_hash    = $addr->txid;
              $sender     = $addr->vin[0]->addr;
              $confirm    = $addr->confirmations;
              $fees       = $addr->fees;
              $time       = $addr->time;

              foreach($addr->vin as $vin){ 
                if($address1 != $vin->addr){

                  foreach ($addr->vout as $vout) {  
                    if($address1 == $vout->scriptPubKey->addresses[0]){

                      $receiver = $address1;
                      $total = $vout->value;
                      $is_txn = UserBtcTransaction::where('txid',$tx_hash)->first();
                      if(!$is_txn)
                      {
                        $userTotalBalance =  $this->getBalance($address1);
                        if ($userTotalBalance > 0.0001)
                        {
                          $btcaddress = new UserBtcTransaction;
                          $btcaddress->user_id    = $uid;
                          $btcaddress->txid       = $tx_hash;
                          $btcaddress->type       = 'received';
                          $btcaddress->recipient  = $receiver;
                          $btcaddress->sender     = $sender;
                          $btcaddress->amount     = $total; 
                          $btcaddress->fees     = 0; 
                          $btcaddress->confirmations = $confirm;
                          $btcaddress->status = ($confirm > 0)?1:0;
                          $btcaddress->created_at = date('Y-m-d H:i:s',$time);
                          $btcaddress->save();                            
                          $this->cron_userbtc_credit_balance($sel->user_id,$total);
                          $amt = bcsub($total,0.0001,8);
                          $this->createUserBtcTransaction($sel->user_id,$amt);
                          $this->depositrefrrels($total,$uid,$currency);
                          return $tx_hash;
                        } 
                        else
                        {
                          $this->depositrefrrels($total,$uid,$currency);
                          echo "Amount should be greater than 0.0001"."<br>";
                        }

                      }
                      else{
                        $btcaddress = UserBtcTransaction::where('txid',$tx_hash)->first();
/*  if($btcaddress->confirmations >= 6 && $btcaddress->status == 0)
{                                            $this->cron_userbtc_credit_balance($uid,$total,'ETH');  
$btcaddress->status = 1;
$ethaddress->save();
}*/
$btcaddress->confirmations = $confirm;
$btcaddress->save(); 
}
} else {
  $receiver = '';
}             
} 
} 
}

} 
}
}
} 
}
return true;
}
public  function btcAdminTransactions()
{
  $addr = BtcAdminAddress::where('id', 1)->first();

  if($addr){
    $tran = $this->getTransactions($addr->address);
    if(isset($tran->txs)){
      foreach($tran->txs as $transaction){
        $txid = $transaction->txid;
        $from = Crypt::decryptString($transaction->vin[0]->addr);
        $amount = $transaction->vout[0]->value;
        $recive_address = $addr->address;
        $time = $transaction->time;
        $confirm = $transaction->confirmations;
        if($from){
          $is_txn = BtcAdminTransaction::where('txid',$txid)->first();
          if(!$is_txn){
            $userBtcTransaction = new BtcAdminTransaction;
            $userBtcTransaction->uid = 1;
            $userBtcTransaction->type = 'received';
            $userBtcTransaction->recipient = $recive_address;
            $userBtcTransaction->sender = $from;
            $userBtcTransaction->amount = $amount;
            $userBtcTransaction->confirmations = $confirm;
            $userBtcTransaction->txid = $txid;
            $userBtcTransaction->created_at = $time;
            $userBtcTransaction->save();
            return "Balance updated!";
          }
        }

      }
    }
    return true;

  }else{
    return "No address";
  }

}


function cron_userbtc_credit_balance($uid,$amount){

  $currency ='BTC';
  $userbalance = Wallet::where([['uid', '=', $uid], ['currency', '=',$currency]])->first();
  if($userbalance) {
    $total = bcadd($amount, $userbalance->balance,8);
    Wallet::where([['uid', '=', $uid], ['currency', '=', $currency]])->update(['balance' => $total], ['updated_at' => date('Y-m-d H:i:s',time())]);
  } else {
    Wallet::insert(['uid' => $uid, 'currency' => $currency, 'balance' => $amount, 'created_at' => date('Y-m-d H:i:s',time()), 'updated_at' => date('Y-m-d H:i:s',time())]);
  }
  $btc_update_user=Mugawaribtc::where('user_id',$uid)->first();
  $update_balance = bcadd($btc_update_user->available_balance, $amount,8);

  $btc_update_user->available_balance = $update_balance;
  $btc_update_user->save();
  return  true;
}



function update_all_user_btc_transaction(){
//   $this->btcUserTransactions(162);   
  $select_user = Mugawaribtc::get();
  if($select_user)
  {
    foreach($select_user as $list){       
      $this->btcUserTransactions($list->user_id);             
    }           
    return true;
  }

}
function createUserBtcTransaction($uid,$amt){
  $private = Mugawaribtc::where([['user_id', '=',$uid]])->first();
  $toaddress = $this->btc_admin_address_get();
  $toaddress = Crypt::decryptString($toaddress);
  $fromaddress = Crypt::decryptString($private->address);
  $credential = explode(',',$private->credential);
  if($fromaddress){
    $pvtkey = Crypt::decryptString($credential[2]);
    $fee=0.0001;      
    $send = $this->send($toaddress, $amt, $fromaddress,$pvtkey, $fee);
    return $send;
  }
  return true;
}
function createAdminBtcTransaction($address,$amt){

  $private = BtcAdminAddress::where([['id', '=',1]])->first();
  $toaddress = $address;
  $fromaddress = Crypt::decryptString($private->address);
  $credential = explode(',',$private->credential);
  if($fromaddress){

    $pvtkey = Crypt::decryptString($credential[2]);

    $fee=0.0001;
    $result = $this->send($toaddress, $amt, $fromaddress,$pvtkey, $fee);

    return $result;
  }
  return true;
}


function createUsersBtcTransaction($uid,$amt,$toaddress){

  $private = Mugawaribtc::where([['user_id', '=',$uid]])->first();
  $toaddress = $toaddress;
  $fromaddress = Crypt::decryptString($private->address);
  $credential = explode(',',$private->credential);

  if($fromaddress){
    $pvtkey = Crypt::decryptString($credential[2]);
    $fee=0.0001;      
    $send = $this->send($toaddress, $amt, $fromaddress,$pvtkey, $fee);
    return $send;
  }
  return true;
}



function btc_admin_address_get(){
  $sel = BtcAdminAddress::where([['id', '=', 1]])->first();
  return $sel->address;
}
function userbalance_btc($uid){
  $private = Mugawaribtc::where([['user_id', '=',$uid]])->first();
  if($private){
    $address = $private->address;
    $balance = $this->getBalance('$address');
    Mugawaribtc::where(['user_id'=> $uid])->update(['balance' => $balance, 'updated_at' => date('Y-m-d H:i:s')]);
  }
  return true;
}
function Adminbalance_btc(){

  $private = BtcAdminAddress::where('id',1)->first();
  if($private){
    $address = Crypt::decryptString($private->address);
    $balance = $this->getBalance($address);
    Mugawaribtc::where(['id'=> 1])->update(['available_balance' => $balance, 'updated_at' => date('Y-m-d H:i:s')]);
    return $balance;
  }
  return true;
}
}