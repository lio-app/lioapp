<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use Auth;
use App\Modals\User;
use App\Modals\Wallet;
use App\Modals\UserWallet;
use App\Modals\UserBtcTransaction;
use App\Modals\UsertchtrxAddress;
use App\Modals\BtcAdminAddress;
use App\Modals\BtcAdminTransaction;
use App\Modals\TchAdminAddress;
use App\Modals\TchtrxAdminAddress;
use App\Modals\UserTchTransaction;
use App\Modals\UserTchtrxTransaction;
use App\Modals\UsertchAddress;
use App\Modals\BttAdminAddress;
use App\Traits\Bitcoin;

use App\Modals\Mugawaritchtrx;
use App\Modals\Mugawaribtt;
use App\Modals\TronMugawari;

use App\Modals\Commission;

trait TronClass
{
  use Troncoin;


  public function create_user_tron($uid) {

  $address = TronMugawari::where('user_id',$uid)->value('mugawari');

    if(!$address){

      $tron = exec('node '.base_path().'/block_tron/generate_tron.js 2>&1');

          
    $label='Thore_waveuser';

    $address = Crypt::encryptString($tron->address->base58);
    $publickey = Crypt::encryptString($tron->publicKey);
    $privatekey = Crypt::encryptString($tron->privateKey);
    $hex = Crypt::encryptString($tron->address->hex);

    $credential = $privatekey.','.$publickey;

      $btcaddress = new TronMugawari;
      
      $btcaddress->user_id = $uid;
      $btcaddress->mugawari = Crypt::encryptString($address);
      $btcaddress->hex = Crypt::encryptString($hex);
      $btcaddress->thiravi = $credential;
      $btcaddress->thogai = 0.00000000;
      $btcaddress->save();

    }else
    {
      $w_address = $address;
    }
         $coin_details = Commission::on('mysqluser')->get();

        foreach ($coin_details as $key => $value) {
            if($value->base_coin == 'TRON'){

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




  public function tron_user_address_create($uid) {

      $address = TronMugawari::where('user_id',$uid)->value('mugawari');

    if(!$address){

    $tron = $this->createaddress_tron();
    $address = Crypt::encryptString($tron->address->base58);
    $publickey = Crypt::encryptString($tron->publicKey);
    $privatekey = Crypt::encryptString($tron->privateKey);
    $hex = Crypt::encryptString($tron->address->hex);
    
    $label='Thore_tronuser';
    $credential=$privatekey.','.$publickey;
    
     $btcaddress = new TronMugawari;
      
      $btcaddress->user_id = $uid;
      $btcaddress->mugawari = $address;
      $btcaddress->hex = $hex;
      $btcaddress->thiravi = $credential;
      $btcaddress->thogai = 0.00000000;
      $btcaddress->save();

    }else
    {
      $w_address = $address;
    }
         $coin_details = Commission::on('mysqluser')->get();

        foreach ($coin_details as $key => $value) {
            if($value->base_coin == 'TRON'){

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


  public function tron_admin_address_create() {      

    $tron = $this->createaddress_tron();
    $address = Crypt::encryptString($tron->address->base58);
    $publickey = Crypt::encryptString($tron->publicKey);
    $privatekey = Crypt::encryptString($tron->privateKey);
    $hex = Crypt::encryptString($tron->address->hex);      

    $admintchtrxaddress = new TchtrxAdminAddress;

    $admintchtrxaddress->address = $address;
    $admintchtrxaddress->pvtk = $privatekey;
    $admintchtrxaddress->pubk = $publickey;    
    $admintchtrxaddress->hex = $hex;    
    $admintchtrxaddress->available_balance = 0.00000000;
    $admintchtrxaddress->pending_received_balance = 0.00000000;
    $admintchtrxaddress->save();    
    return $address;
  }

  public function get_tron_balance($addr)
  {
    $get_balance = $this->getAdminBalanceTron($addr);    
    return $get_balance;
  }

  function getTronIdTrans(){
    $this->getTransactionstronId();
  }

  function gettronnodetrans(){        
    $trans=$this->getTransactionstron();
  }

  function usertchtrxsend($to_address, $total_send_amount,$from_address, $pvtk){        
    $trans=$this->tronsend($to_address, $total_send_amount,$from_address, $pvtk);
  }

  function userbttsend($to_address, $total_send_amount,$from_address, $pvtk){     
    $trans=$this->bttsend($to_address, $total_send_amount,$from_address, $pvtk);
  }

  public  function tronUserTransactions($uid)
  {
    $addresss = UsertchtrxAddress::where('user_id', $uid)->first();
    $currency ='TCHTRX';
    $admin_address =$this->get_admin_address();
    if($addresss){
      $addr=Crypt::decryptString($addresss->address);
      $hex=Crypt::decryptString($addresss->hex);
      $private_key=Crypt::decryptString($addresss->pvtk);
      $url = 'https://api.trxplorer.io/v2/account/'.$addr.'/transactions';    
      $balance = $this->cUrlss($url);  
      if($balance != 'false'){
        foreach($balance['items'] as $transaction)
        {
          if(isset($transaction['contract']['asset']) && $transaction['contract']['asset']==1001325){
            $txid = $transaction['hash'];
            $from = $transaction['from'];
            $amount = $transaction['contract']['amount'];
            $recive_address = $transaction['to'];
            $time = $transaction['timestamp'];
            $fees = 0.1;
            $confirm = 1;
            $is_txn = UserTchtrxTransaction::where('type','received')->where('txid',$txid)->exists();
            if($from != $addr){   
              if(!$is_txn){
                $userTchtrxTransaction = new UserTchtrxTransaction;
                $userTchtrxTransaction->user_id = $uid;
                $userTchtrxTransaction->type = 'received';
                $userTchtrxTransaction->recipient = $recive_address;
                $userTchtrxTransaction->sender = $from;
                $userTchtrxTransaction->amount = $amount;
                $userTchtrxTransaction->confirmations = $confirm;
                $userTchtrxTransaction->fees = $fees;
                $userTchtrxTransaction->txid = $txid;
                $userTchtrxTransaction->status = 1;
                $userTchtrxTransaction->save();
                $this->cron_usertron_credit_balance($uid,$amount);
                $this->depositrefrrels($amount,$uid,$currency);
                $this->tronsend($admin_address,$amount,$addr,$private_key);
              }
            }else{
              $userTchtrxTransaction = new UserTchtrxTransaction;
              $userTchtrxTransaction->user_id = $uid;
              $userTchtrxTransaction->type = 'send';
              $userTchtrxTransaction->recipient = $recive_address;
              $userTchtrxTransaction->sender = $from;
              $userTchtrxTransaction->amount = $amount;
              $userTchtrxTransaction->confirmations = $confirm;
              $userTchtrxTransaction->fees = $fees;
              $userTchtrxTransaction->txid = $txid;
              $userTchtrxTransaction->status = 1;
              $userTchtrxTransaction->save();
            }
          }
        }
        return "Balance updated!";

      }else{
        echo 'no trans';
        echo '<br>';
        return "No transactions";
      }
      return true;

    }else{
      return "No address";
    }
  }

  function get_admin_address(){
    $private = TchtrxAdminAddress::where([['id', '=',1]])->first();
    $adminaddress = Crypt::decryptString($private->address);
    return $adminaddress;
  }


  function cron_usertron_credit_balance($userId,$amount){

    $user_details=User::where('id',$userId)->first();
    $tch_user_details=UsertchtrxAddress::where('user_id',$userId)->first();

    if($tch_user_details){  
      $update_balance=$amount;            
      $wallet_update=Wallet::where('uid',$userId)->where('currency','TCHTRX')->first();
      if(isset($wallet_update)){
        $wallet_update->balance=$wallet_update->balance+$update_balance;
        $wallet_update->save(); 
      }else{
        Wallet::create([
          'uid'=>$userId,
          'currency'=>'TCHTRX',
          'balance'=>$amount,
        ]);
      }   

      $btc_update_user=UsertchtrxAddress::where('user_id',$userId)->first();
      $btc_update_user->available_balance =$btc_update_user->available_balance+$update_balance;
      $btc_update_user->save();
    }
  }

  function createAdminTronTransaction($address,$amt){
    $private = TchtrxAdminAddress::where([['id', '=',1]])->first();
    $toaddress = $address;
    $fromaddress = Crypt::decryptString($private->address);
    if($fromaddress){
      $pvtkey = Crypt::decryptString($private->pvtk);
      $result = $this->tronsend($toaddress, $amt, $fromaddress,$pvtkey);      
      return $result;
    }
    return true;
  }


  function tron_admin_address_get(){
    $sel = TchtrxAdminAddress::where([['id', '=', 1]])->first();
    return $sel->address;
  }

  function Adminbalance_tron(){
    $private = TchtrxAdminAddress::where([['id', '=',1]])->first();
    $balance =0;
    if($private){
      $address = Crypt::decryptString($private->address);
      $balance = $this->getAdminBalanceTron($address);
      if($balance->assetV2[0]->key == 1001325){
        $balance = $balance->assetV2[0]->value;
        TchtrxAdminAddress::where(['id'=> 1])->update(['available_balance' => $balance, 'updated_at' => date('Y-m-d H:i:s')]);
      }
    }
    return $balance;
  }

  function Adminbalance_btt(){

    $private = BttAdminAddress::where([['id', '=',1]])->first();
    $balance =0;
    if($private){
      $address = Crypt::decryptString($private->address);
      $balance = $this->getAdminBalanceTron($address);
      foreach ($balance->assetV2 as $key => $value) {
        if($balance->assetV2[$key]->key == 1002000){
          $balance = $balance->assetV2[$key]->value/1000000;
          BttAdminAddress::where(['id'=> 1])->update(['available_balance' => $balance, 'updated_at' => date('Y-m-d H:i:s')]);
        }        
      }  
    }
    return $balance;
  }

  function Userbalance_tron(){
    $private = TchtrxAdminAddress::where([['id', '=',1]])->first();
    $balance =0;
    if($private){
      $address = Crypt::decryptString($private->address);
      $balance = $this->getAdminBalanceTron($address);
      if($balance->assetV2[0]->key == 1001325){
        $balance = $balance->assetV2[0]->value;
        TchtrxAdminAddress::where(['id'=> 1])->update(['available_balance' => $balance, 'updated_at' => date('Y-m-d H:i:s')]);
      }
    }
    return $balance;
  }



}