<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use Auth;
use App\User;
use App\Modals\Wallet;

use App\UserBtcTransaction;
use App\UserTchTransaction;
use App\UserThrwaveTransaction;
use App\UserThexTransaction;

use App\ThrwaveAdminAddress;
use App\BtcAdminAddress;
use App\TchAdminAddress;
use App\ThexAdminAddress;

use App\BtcAdminTransaction;
use App\TchAdminTransaction;
use App\ThexAdminTransaction;
use App\ThrwaveAdminTransaction;

use App\Traits\Bitcoin;

use App\Modals\Mugawaritch;
use App\Modals\Mugawarithex;
use App\Modals\Mugawarithorecoin;
use App\Modals\WaveMugawari;

use App\Modals\Commission;

trait WaveClass
{
  use Wavecoin;

/* Address creation for waves  curl*/
  public function create_user_wave($uid) {

    $address = WaveMugawari::where('user_id',$uid)->value('mugawari');

    if(!$address){

      $wave = json_decode(exec('node '.base_path().'/block_wave/generate_wave.js 2>&1'));

    $address = Crypt::encryptString($wave->address);
    $publickey = Crypt::encryptString($wave->publickey);
    $privatekey = Crypt::encryptString($wave->privatekey);
    $label='Thore_waveuser';
    $credential=$privatekey.','.$publickey;

      $btcaddress = new WaveMugawari;
      
      $btcaddress->user_id = $uid;
      $btcaddress->mugawari = Crypt::encryptString($address);
      $btcaddress->thiravi = $credential;
      $btcaddress->thogai = 0.00000000;
      $btcaddress->save();
    }else
    {
      $w_address = $address;
    }
         $coin_details = Commission::on('mysqluser')->get();

        foreach ($coin_details as $key => $value) {
            if($value->base_coin == 'WAVES'){

               $walletaddress = Wallet::on('mysqluser')->where(['uid'=> $uid,'currency' => $value->source])->first();

            if(!$walletaddress){  
                $walletaddress = new Wallet; 
                $walletaddress->setConnection('mysqluser');
                $walletaddress->uid = $uid;
                $walletaddress->currency = $value->source;
            }

              $walletaddress->mukavari            = $address;
              $walletaddress->created_at          = date('Y-m-d H:i:s',time()); 
              $walletaddress->updated_at          = date('Y-m-d H:i:s',time()); 
              $walletaddress->save();

            }
        }

    return $address;
  }

  /*Address creation for  node */

//   public function waves_tokens_address_create($uid) {

//     $wave = $this->createaddress_wave();

//     $address = Crypt::encryptString($wave->address);
//     $publickey = Crypt::encryptString($wave->publickey);
//     $privatekey = Crypt::encryptString($wave->privatekey);
//     $security = User::where('id', $uid)->first();
// //$label='Thore_'.$security->name;
//     $label='Thore_waveuser';
//     $credential=$privatekey.','.$publickey;

//     $checktch=Mugawaritch::where('user_id',$uid)->first();
//     $checkthex=Mugawarithex::where('user_id',$uid)->first();
//     $checkthorecoin=Mugawarithorecoin::where('user_id',$uid)->first();

//     $usrchecktch=Mugawaritch::on('mysqluser')->where('user_id',$uid)->first();
//     $usrcheckthex=Mugawarithex::on('mysqluser')->where('user_id',$uid)->first();
//     $usrcheckthorecoin=Mugawarithorecoin::on('mysqluser')->where('user_id',$uid)->first();

//     if(is_object($checktch)){
//       $add=$checktch->mugawari;

//       $usrchecktch->mugawari=$add;
//       $usrchecktch->update();

//       $checkthex->mugawari=$add;
//       $checkthex->thiravi=$credential;
//       $checkthex->update();

//       $usrcheckthex->mugawari=$add;
//       $usrcheckthex->update();

//       $checkthorecoin->mugawari=$add;
//       $checkthorecoin->thiravi=$credential;
//       $checkthorecoin->update();

//       $usrcheckthorecoin->mugawari=$add;
//       $usrcheckthorecoin->update();


//     }elseif(is_object($checkthex)){
//       $add=$checkthex->mugawari;

//       $usrcheckthex->mugawari=$add;
//       $usrcheckthex->update();

//       $checktch->mugawari=$add;
//       $checktch->thiravi=$credential;
//       $checktch->update();

//       $usrchecktch->mugawari=$add;
//       $usrchecktch->update();

//       $checkthorecoin->mugawari=$add;
//       $checkthorecoin->thiravi=$credential;
//       $checkthorecoin->update();

//       $usrcheckthorecoin->mugawari=$add;
//       $usrcheckthorecoin->update();

//     }elseif(is_object($checkthorecoin)){
//       $add=$checkthorecoin->mugawari;

//       $usrcheckthorecoin->mugawari=$add;
//       $usrcheckthorecoin->update();

//       $checktch->mugawari=$add;
//       $checktch->thiravi=$credential;
//       $checktch->update();

//       $usrchecktch->mugawari=$add;
//       $usrchecktch->update();

//       $checkthex->mugawari=$add;
//       $checkthex->thiravi=$credential;
//       $checkthex->update();

//       $usrcheckthex->mugawari=$add;
//       $usrcheckthex->update();

//     }else{
//       Mugawaritch::create([
//         'user_id' => $uid,
//         'mugawari' => $address,
//         'thiravi' => $credential,
//         'thogai' => 0.00,
//       ]);

//       Mugawaritch::on('mysqluser')->create([
//         'user_id' => $uid,
//         'mugawari' => $address,
//       ]);

//       Mugawarithex::create([
//         'user_id' => $uid,
//         'mugawari' =>$address,
//         'thiravi' => $credential,
//         'thogai' => 0.00,
//       ]);
//       Mugawarithex::on('mysqluser')->create([
//         'user_id' => $uid,
//         'mugawari' => $address,
//       ]);
//       Mugawarithorecoin::create([
//         'user_id' => $uid,
//         'mugawari' => $address,
//         'thiravi' => $credential,
//         'thogai' => 0.00,
//       ]);
//       Mugawarithorecoin::on('mysqluser')->create([
//         'user_id' => $uid,
//         'mugawari' => $address,
//       ]);
//     }


//     return $address;
//   }


  public function wave_user_address_create($uid) {

    $wave = $this->createaddress_wave();
    $address = Crypt::encryptString($wave->address);
    $publickey = Crypt::encryptString($wave->publickey);
    $privatekey = Crypt::encryptString($wave->privatekey);
    $security = User::where('id', $uid)->first();
//$label='Thore_'.$security->name;
    $label='Thore_waveuser';

    $tchaddress = new Mugawaritch;
    $tchaddress->user_id = $uid;
    $tchaddress->address = $address;
    $tchaddress->pvtk = $privatekey;
    $tchaddress->pubk = $publickey;    
    $tchaddress->available_balance = 0.00000000;
    $tchaddress->pending_received_balance = 0.00000000;
    $tchaddress->save();    

    return $address;
  }

  public function thrwave_user_address_create($uid) {

    $wave = $this->createaddress_wave();
    $address = Crypt::encryptString($wave->address);
    $publickey = Crypt::encryptString($wave->publickey);
    $privatekey = Crypt::encryptString($wave->privatekey);
    $security = User::where('id', $uid)->first();
//$label='Thore_'.$security->name;
    $label='Thore_thrwaveuser';

    $tchaddress = new Mugawarithorecoin;

    $tchaddress->user_id = $uid;
    $tchaddress->address = $address;
    $tchaddress->pvtk = $privatekey;
    $tchaddress->pubk = $publickey;    
    $tchaddress->available_balance = 0.00000000;
    $tchaddress->pending_received_balance = 0.00000000;
    $tchaddress->save();    
    return $address;
  }

  public function wave_admin_address_create() {      

    $wave = $this->createaddress_wave();
    $address = Crypt::encryptString($wave->address);
    $publickey = Crypt::encryptString($wave->publickey);
    $privatekey = Crypt::encryptString($wave->privatekey);     

    $admintchaddress = new TchAdminAddress;
    $admintchaddress->uid = 1;
    $admintchaddress->address = $address;
    $admintchaddress->pvtk = $privatekey;
    $admintchaddress->pubk = $publickey;    
    $admintchaddress->available_balance = 0.00000000;
    $admintchaddress->pending_received_balance = 0.00000000;
    $admintchaddress->status = 1;
    $admintchaddress->save();    
    return $address;
  }

  public function get_wave_balance($addr,$assetId)
  {
    $get_balance = $this->getBalancewave($addr,$assetId);    
    return $get_balance;
  }

  public function get_address_public($public_key)
  {
    $get_balance = $this->getWaveAddress($public_key);    
    return $get_address;
  }

  public function get_address_private($private_key)
  {
    $get_balance = $this->getWaveAddressprivate($private_key);    
    return $get_address;
  }


  public  function waveUserTransactions($uid)
  {
    $addresss = Mugawaritch::where('user_id', $uid)->first();
    $currency='TCH';
    if($addresss){
      $addr=Crypt::decryptString($addresss->address);
      echo $uid.'=>'.$addr;
      echo '<br>';
      $url ="https://nodes.wavesnodes.com/addresses/validate/".$addr;
      $address_response = $this->cUrlwaves($url);
      if($address_response->valid == 'true'){

        $tran = $this->getTransactionswave($addr);     
        if($tran != 'false'){
          foreach($tran as $transaction)
          {
            $txid = $transaction->id;
            $from = $transaction->sender;
            $amount = $transaction->amount / 100000000;
            $recive_address = $transaction->recipient;
            $assetId =$transaction->assetId;
            $time = $transaction->timestamp;
            $fees = $transaction->fee;
            $confirm = 1;
            if($assetId == 'FeVnABkSLcroug3dz6rDPZPPVXuero3ACwqm7YCrcSQa'){  
              $is_txn = UserTchTransaction::where('txid',$txid)->exists();
              if($from != $addr){            
                if(!$is_txn){
                  $userTchTransaction = new UserTchTransaction;
                  $userTchTransaction->user_id = $uid;
                  $userTchTransaction->type = 'received';
                  $userTchTransaction->recipient = $recive_address;
                  $userTchTransaction->sender = $from;
                  $userTchTransaction->amount = $amount;
                  $userTchTransaction->confirmations = $confirm;
                  $userTchTransaction->fees = $fees;
                  $userTchTransaction->txid = $txid;
                  $userTchTransaction->status = 1;
                  $userTchTransaction->save();
                  $this->cron_userwave_credit_balance($uid,$amount);
                  $this->depositrefrrels($amount,$uid,$currency);
                }
              }
            }
          }     
        }else{
          echo 'no trans';
          echo '<br>';
          return "No transactions";
        }
        return true;
      } 
    }else{
      return "No address";
    }    
  }


  public  function thrwaveUserTransactions($uid)
  {
    $addresss = Mugawarithorecoin::where('user_id', $uid)->first();
    $currency='THORECOIN';
    if($addresss){    
      $addr=Crypt::decryptString($addresss->address);
      echo $uid.'=>'.$addr;
      echo '<br>';
      $url ="https://nodes.wavesnodes.com/addresses/validate/".$addr;
      $address_response = $this->cUrlwaves($url);
      if($address_response->valid == 'true'){
        $tran = $this->getTransactionswave($addr); 
        if($tran != 'false'){
          foreach($tran as $transaction)
          {
            $txid = $transaction->id;
            $from = $transaction->sender;
            $amount = $transaction->amount / 10000;
            $recive_address = $transaction->recipient;
            $time = $transaction->timestamp;
            $assetId = $transaction->assetId;
            $fees = $transaction->fee;
            $confirm = 1;
            if($assetId == '51ZCHw4vzWpj4WgviWjjF6buAFga8tjoUSXze2Pkdui9'){    
              $is_txn = UserThrwaveTransaction::where('txid',$txid)->exists();
              if($from != $addr){            
                if(!$is_txn){
                  $userTchTransaction = new UserThrwaveTransaction;
                  $userTchTransaction->user_id = $uid;
                  $userTchTransaction->type = 'received';
                  $userTchTransaction->recipient = $recive_address;
                  $userTchTransaction->sender = $from;
                  $userTchTransaction->amount = $amount;
                  $userTchTransaction->confirmations = $confirm;
                  $userTchTransaction->fees = $fees;
                  $userTchTransaction->txid = $txid;
                  $userTchTransaction->status = 1;
                  $userTchTransaction->save();
                  $this->cron_userthrwave_credit_balance($uid,$amount);
                  $this->depositrefrrels($amount,$uid,$currency);
                }
              }
            }
          }     
        }else{

          echo 'no trans';
          echo '<br>';
          return "No transactions";
        }
        return true;
      } 
    }else{
      return "No address";
    }    
  }

  public  function thexUserTransactions($uid)
  {
    $addresss = Mugawarithex::where('user_id', $uid)->first();
    $currency='THEX';
    if($addresss){    
      $addr=Crypt::decryptString($addresss->address);
      echo $uid.'=>'.$addr;
      echo '<br>';
      $url ="https://nodes.wavesnodes.com/addresses/validate/".$addr;
      $address_response = $this->cUrlwaves($url);
      if($address_response->valid == 'true'){
        $tran = $this->getTransactionswave($addr); 
        if($tran != 'false'){
          foreach($tran as $transaction)
          {
            $txid = $transaction->id;
            $from = $transaction->sender;
            $amount = $transaction->amount;
            $recive_address = $transaction->recipient;
            $time = $transaction->timestamp;
            $assetId = $transaction->assetId;
            $fees = $transaction->fee;
            $confirm = 1;
            if($assetId == 'J7zcUbZ4FCSk4eH5FUpU13bGURkiN8fPfppbpVxu8Gkw'){    
              $is_txn = UserThexTransaction::where('txid',$txid)->exists();
              if($from != $addr){            
                if(!$is_txn){
                  $userTchTransaction = new UserThexTransaction;
                  $userTchTransaction->user_id = $uid;
                  $userTchTransaction->type = 'received';
                  $userTchTransaction->recipient = $recive_address;
                  $userTchTransaction->sender = $from;
                  $userTchTransaction->amount = $amount;
                  $userTchTransaction->confirmations = $confirm;
                  $userTchTransaction->fees = $fees;
                  $userTchTransaction->txid = $txid;
                  $userTchTransaction->status = 1;
                  $userTchTransaction->save();
                  $this->cron_userthex_credit_balance($uid,$amount);
                  $this->depositrefrrels($amount,$uid,$currency);
                }
              }
            }
          }     
        }else{

          echo 'no trans';
          echo '<br>';
          return "No transactions";
        }
        return true;
      } 
    }else{
      return "No address";
    }    
  }

  public  function waveAdminTransactions()
  {
    $address = TchAdminAddress::where('id', 1)->first();
    if($address){      
      $addr=Crypt::decryptString($address->address);
      $url ="https://nodes.wavesnodes.com/addresses/validate/".$addr;
      $address_response = $this->cUrlwaves($url);
      if($address_response->valid == 'true'){
        $tran = $this->getTransactionswave($addr); 
        if($tran != 'false'){
          foreach($tran as $transaction)
          {
            $txid = $transaction->id;
            $from = $transaction->sender;
            $amount = $transaction->amount / 100000000;
            $recive_address = $transaction->recipient;
            $time = $transaction->timestamp;
            $fees = $transaction->fee;
            $assetId = $transaction->assetId;
            $confirm = 1;
            if($assetId == 'FeVnABkSLcroug3dz6rDPZPPVXuero3ACwqm7YCrcSQa'){  
              if($from){
                $is_txn = TchAdminTransaction::where('txid',$txid)->first();
                if(!$is_txn){
                  $userBtcTransaction = new TchAdminTransaction;
                  $userBtcTransaction->type = 'received';
                  $userBtcTransaction->recipient = $recive_address;
                  $userBtcTransaction->sender = $from;
                  $userBtcTransaction->amount = $amount;
                  $userBtcTransaction->confirmations = $confirm;
                  $userBtcTransaction->txid = $txid;
                  $userBtcTransaction->fees = 1;
                  $userBtcTransaction->status = 1;
                  $userBtcTransaction->save();
                }
              }
            }

          }
        }
        return true;
      }

    }else{
      return "No address";
    }

  }

  public  function thrwaveAdminTransactions()
  {
    $address = ThrwaveAdminAddress::where('id', 1)->first();
    if($address){      
      $addr=Crypt::decryptString($address->address);
      $url ="https://nodes.wavesnodes.com/addresses/validate/".$addr;
      $address_response = $this->cUrlwaves($url);
      if($address_response->valid == 'true'){
        $tran = $this->getTransactionswave($addr); 
        if($tran != 'false'){
          foreach($tran as $transaction)
          {
            $txid = $transaction->id;
            $from = $transaction->sender;
            $amount = $transaction->amount / 10000;
            $recive_address = $transaction->recipient;
            $time = $transaction->timestamp;
            $fees = $transaction->fee;
            $assetId = $transaction->assetId;
            $confirm = 1;
            if($assetId == '51ZCHw4vzWpj4WgviWjjF6buAFga8tjoUSXze2Pkdui9'){          
              if($from){
                $is_txn = ThrwaveAdminTransaction::where('txid',$txid)->first();
                if(!$is_txn){
                  $userBtcTransaction = new ThrwaveAdminTransaction;
                  $userBtcTransaction->type = 'received';
                  $userBtcTransaction->recipient = $recive_address;
                  $userBtcTransaction->sender = $from;
                  $userBtcTransaction->amount = $amount;
                  $userBtcTransaction->confirmations = $confirm;
                  $userBtcTransaction->txid = $txid;
                  $userBtcTransaction->fees = 1;
                  $userBtcTransaction->status = 1;
                  $userBtcTransaction->save();
                }
              }
            }

          }
        }
        return true;
      }

    }else{
      return "No address";
    }   
  }

  public  function thexAdminTransactions()
  {
    $address = ThexAdminAddress::where('id', 1)->first();
    if($address){      
      $addr=Crypt::decryptString($address->address);
      $url ="https://nodes.wavesnodes.com/addresses/validate/".$addr;
      $address_response = $this->cUrlwaves($url);
      if($address_response->valid == 'true'){
        $tran = $this->getTransactionswave($addr); 
        if($tran != 'false'){
          foreach($tran as $transaction)
          {
            $txid = $transaction->id;
            $from = $transaction->sender;
            $amount = $transaction->amount;
            $recive_address = $transaction->recipient;
            $time = $transaction->timestamp;
            $fees = $transaction->fee;
            $assetId = $transaction->assetId;
            $confirm = 1;
            if($assetId == '51ZCHw4vzWpj4WgviWjjF6buAFga8tjoUSXze2Pkdui9'){          
              if($from){
                $is_txn = ThexAdminTransaction::where('txid',$txid)->first();
                if(!$is_txn){
                  $userBtcTransaction = new ThexAdminTransaction;
                  $userBtcTransaction->type = 'received';
                  $userBtcTransaction->recipient = $recive_address;
                  $userBtcTransaction->sender = $from;
                  $userBtcTransaction->amount = $amount;
                  $userBtcTransaction->confirmations = $confirm;
                  $userBtcTransaction->txid = $txid;
                  $userBtcTransaction->fees = 1;
                  $userBtcTransaction->status = 1;
                  $userBtcTransaction->save();
                }
              }
            }

          }
        }
        return true;
      }

    }else{
      return "No address";
    }   
  }


  function cron_userwave_credit_balance($userId,$amount){

    $user_details=User::where('id',$userId)->first();
    $tch_user_details=Mugawaritch::where('user_id',$userId)->first();

    if($tch_user_details){  
      $update_balance=$amount;      
      $wallet_update=Wallet::where('uid',$userId)->where('currency','TCH')->first();
      if(isset($wallet_update)){
        $wallet_update->pending_balance=$wallet_update->pending_balance+$update_balance;
        $wallet_update->save(); 
      }else{
        $wallet_eth=new Wallet;
        $wallet_eth->uid = $userId;
        $wallet_eth->currency ='TCH';
        $wallet_eth->balance =$amount;
        $wallet_eth->escrow_balance ='0.00';
        $wallet_eth->save();
      } 

      $btc_update_user=Mugawaritch::where('user_id',$userId)->first();
      $btc_update_user->available_balance =$btc_update_user->available_balance+$update_balance;
      $btc_update_user->save();
    }
  }

  function cron_userthrwave_credit_balance($userId,$amount){
    $user_details=User::where('id',$userId)->first();
    $tch_user_details=Mugawarithorecoin::where('user_id',$userId)->first();

    if($tch_user_details){  
      $update_balance=$amount;      
      $wallet_update=Wallet::where('uid',$userId)->where('currency','THORECOIN')->first();
      if(isset($wallet_update)){
        $wallet_update->pending_balance=$wallet_update->pending_balance+$update_balance;
        $wallet_update->save(); 
      }else{
        $wallet_eth=new Wallet;
        $wallet_eth->uid = $userId;
        $wallet_eth->currency ='THORECOIN';
        $wallet_eth->pending_balance =$amount;
        $wallet_eth->escrow_balance ='0.00';
        $wallet_eth->save();
      } 

      $btc_update_user=Mugawarithorecoin::where('user_id',$userId)->first();
      $btc_update_user->available_balance =$btc_update_user->available_balance+$update_balance;
      $btc_update_user->save();
    }
  }

  function cron_userthex_credit_balance($userId,$amount){
    $user_details=User::where('id',$userId)->first();
    $tch_user_details=Mugawarithex::where('user_id',$userId)->first();

    if($tch_user_details){  
      $update_balance=$amount;      
      $wallet_update=Wallet::where('uid',$userId)->where('currency','THEX')->first();
      if(isset($wallet_update)){
        $wallet_update->pending_balance=$wallet_update->pending_balance+$update_balance;
        $wallet_update->save(); 
      }else{
        $wallet_eth=new Wallet;
        $wallet_eth->uid = $userId;
        $wallet_eth->currency ='THEX';
        $wallet_eth->pending_balance =$amount;
        $wallet_eth->escrow_balance ='0.00';
        $wallet_eth->save();
      } 

      $btc_update_user=Mugawarithex::where('user_id',$userId)->first();
      $btc_update_user->available_balance =$btc_update_user->available_balance+$update_balance;
      $btc_update_user->save();
    }
  }

  function update_all_user_wave_transaction(){
    $select_user = Mugawaritch::get();
    if($select_user)
    {
      foreach($select_user as $list){       
        $this->UserTchTransaction($list->user_id);             
      }           
      return true;
    }

  }
  function createUserWaveTransaction($amount,$to_address,$assetId,$pvtk,$from_address){

    $this->wavesend($amount,$to_address,$assetId,$pvtk,$from_address);
  }
  function createAdminWaveTransaction($address,$amt){

    $private = TchAdminAddress::where([['id', '=',1]])->first();
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

  function Wave_admin_address_get(){
    $sel = Mugawaritch::where([['id', '=', 1]])->first();
    return $sel->address;
  }
  function userbalance_wave($uid){

    $private = Mugawaritch::where([['user_id', '=',$uid]])->first();
    if($private){
      $address = $private->address;
      $balance = $this->getBalancewave('$address');
      Mugawaritch::where(['user_id'=> $uid])->update(['balance' => $balance, 'updated_at' => date('Y-m-d H:i:s')]);
    }
    return true;
  }
  function Adminbalance_wave(){

    $private = Mugawaritch::where([['id', '=',1]])->first();
    if($private){
      $address = Crypt::decryptString($private->address);
      $balance = $this->getBalancewave($address);
      Mugawaritch::where(['id'=> 1])->update(['available_balance' => $balance, 'updated_at' => date('Y-m-d H:i:s')]);
    }
    return true;
  }

  private function cUrlwaves($url){
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
    return json_decode($result);
  }
}