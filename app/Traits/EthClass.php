<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;
use App\Modals\User;
use App\Modals\Wallet;

use App\Modals\Commission;

use App\Modals\EthAdminAddress;
use App\Modals\TheAdminAddress;
use App\Modals\ThrAdminAddress;
use App\Modals\TchAdminAddress;

use App\Modals\UserEthTransaction;
use App\Modals\UserTheTransaction;
use App\Modals\UserThrTransaction;
use App\Modals\UserTchTransaction;
use App\Modals\UserThxTransaction;

Use App\Traits\RefrrealClass;


use App\Modals\Mugawarieth;
use App\Modals\Mugawarithe;
use App\Modals\Mugawarithx;
use App\Modals\Mugawarithr;

trait EthClass
{
  use RefrrealClass;

  public function ethcreate($tokenblock) {
    $url = "https://api.blockcypher.com/v1/eth/main/addrs?token=$tokenblock";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, 1);
    if (curl_errno($ch)) {
      $result = 'Error:' . curl_error($ch);
    } else {
      $result = curl_exec($ch);
    }
    curl_close($ch);
    return json_decode($result);
  }

    public function create_user_ether($id)
    {
        $address = Mugawarieth::where('user_id',$id)->value('mugawari');


        if(!$address){
            $ethaddress = $this->ethcreate(env('ETH_TOKEN_BLOCK'));

            $e_address = Crypt::encryptString("0x".$ethaddress->address);
            $pvtk = Crypt::encryptString($ethaddress->private);
            $pubk = Crypt::encryptString($ethaddress->public);

            $ethtable = new Mugawarieth;
            $ethtable->user_id = $id;
            $ethtable->mugawari = Crypt::encryptString("0x".$ethaddress->address);
            
            $ethtable->thiravi = $pvtk.','.$pubk;
            $ethtable->thogai = 0.00000000;
            $ethtable->save();

            $userethtable = new Mugawarieth;
            $userethtable->setConnection('mysqluser');
            $userethtable->user_id = $id;
            $userethtable->mugawari = Crypt::encryptString("0x".$ethaddress->address);
            $userethtable->thogai = 0.00000000;
            $userethtable->save();

        }
        else
        {
           $e_address = $address;
        }

        $coin_details = Commission::on('mysqluser')->get();

        foreach ($coin_details as $key => $value) {
            if($value->base_coin == 'ETH'){

               $walletaddress = Wallet::on('mysqluser')->where(['uid'=> $id,'currency' => $value->source])->first();

            if(!$walletaddress){  
                $walletaddress = new Wallet; 
                $walletaddress->setConnection('mysqluser');
                $walletaddress->uid = $id;
                $walletaddress->currency = $value->source;
            }

              $walletaddress->mukavari            = $e_address;
              $walletaddress->created_at          = date('Y-m-d H:i:s',time()); 
              $walletaddress->updated_at          = date('Y-m-d H:i:s',time()); 
              $walletaddress->save();

            }
        }

        
        
        return $e_address;
            
    }




  // public function createUserEtherAddress($uid)
  // {
  //   $ethaddress = $this->ethcreate(env('ETH_TOKEN_BLOCK'));
  //   $address = Crypt::encryptString($ethaddress->address);
  //   $privatekey = Crypt::encryptString($ethaddress->private);
  //   $publickey = Crypt::encryptString($ethaddress->public);
  //   $credential=$privatekey.','.$publickey;

  //   $checketh=Mugawarieth::where('user_id',$uid)->first();
  //   $checkthe=Mugawarithe::where('user_id',$uid)->first();
  //   $checkthr=Mugawarithr::where('user_id',$uid)->first();
  //   $checkthx=Mugawarithx::where('user_id',$uid)->first();

  //   $usrchecketh=Mugawarieth::on('mysqluser')->where('user_id',$uid)->first();
  //   $usrcheckthe=Mugawarithe::on('mysqluser')->where('user_id',$uid)->first();
  //   $usrcheckthr=Mugawarithr::on('mysqluser')->where('user_id',$uid)->first();
  //   $usrcheckthx=Mugawarithx::on('mysqluser')->where('user_id',$uid)->first();

  //   if(is_object($checketh)){
  //     $add=$checketh->mugawari;
  //     $checkthe->mugawari=$add;
  //     $checkthe->thiravi=$credential;
  //     $checkthe->update();

  //     $usrcheckthe->mugawari=$add;
  //     $usrcheckthe->update();

  //     $checkthr->mugawari=$add;
  //     $checkthr->thiravi=$credential;
  //     $checkthr->update();

  //     $usrcheckthr->mugawari=$add;
  //     $usrcheckthr->update();

  //     $checkthx->mugawari=$add;
  //     $checkthx->thiravi=$credential;
  //     $checkthx->update();

  //     $usrcheckthx->mugawari=$add;
  //     $usrcheckthx->update();

  //   }elseif(is_object($checkthe)){
  //     $add=$checkthe->mugawari;
  //     $checketh->mugawari=$add;
  //     $checketh->thiravi=$credential;
  //     $checketh->update();

  //     $usrchecketh->mugawari=$add;
  //     $usrchecketh->update();

  //     $checkthr->mugawari=$add;
  //     $checkthr->thiravi=$credential;
  //     $checkthr->update();

  //     $usrcheckthr->mugawari=$add;
  //     $usrcheckthr->update();

  //     $checkthx->mugawari=$add;
  //     $checkthx->thiravi=$credential;
  //     $checkthx->update();

  //     $usrcheckthx->mugawari=$add;
  //     $usrcheckthx->update();

  //   }elseif(is_object($checkthr)){
  //     $add=$checkthr->mugawari;
  //     $checketh->mugawari=$add;
  //     $checketh->thiravi=$credential;
  //     $checketh->update();

  //     $usrchecketh->mugawari=$add;
  //     $usrchecketh->update();

  //     $checkthe->mugawari=$add;
  //     $checkthe->thiravi=$credential;
  //     $checkthe->update();

  //     $usrcheckthe->mugawari=$add;
  //     $usrcheckthe->update();

  //     $checkthx->mugawari=$add;
  //     $checkthx->thiravi=$credential;
  //     $checkthx->update();

  //     $usrcheckthx->mugawari=$add;
  //     $usrcheckthx->update();

  //   }elseif(is_object($checkthx)){
  //     $add=$checkthx->mugawari;
  //     $checketh->mugawari=$add;
  //     $checketh->thiravi=$credential;
  //     $checketh->update();

  //     $usrchecketh->mugawari=$add;
  //     $usrchecketh->update();

  //     $checkthe->mugawari=$add;
  //     $checkthe->thiravi=$credential;
  //     $checkthe->update();

  //     $usrcheckthe->mugawari=$add;
  //     $usrcheckthe->update();

  //     $checkthr->mugawari=$add;
  //     $checkthr->thiravi=$credential;
  //     $checkthr->update();

  //     $usrcheckthr->mugawari=$add;
  //     $usrcheckthr->update();
  //   }else{
  //     Mugawarieth::create([
  //       'user_id' => $uid,
  //       'mugawari' => $address,
  //       'thiravi' => $credential,
  //       'thogai' => 0.00,
  //     ]);
  //     Mugawarieth::on('mysqluser')->create([
  //       'user_id' => $uid,
  //       'mugawari' => $address,
  //     ]);

  //     Mugawarithe::create([
  //       'user_id' => $uid,
  //       'mugawari' => $address,
  //       'thiravi' => $credential,
  //       'thogai' => 0.00,
  //     ]);
  //     Mugawarithe::on('mysqluser')->create([
  //       'user_id' => $uid,
  //       'mugawari' => $address,
  //     ]);

  //     Mugawarithr::create([
  //       'user_id' => $uid,
  //       'mugawari' => $address,
  //       'thiravi' => $credential,
  //       'thogai' => 0.00,
  //     ]);

  //     Mugawarithr::on('mysqluser')->create([
  //       'user_id' => $uid,
  //       'mugawari' => $address,
  //     ]);
  //     Mugawarithx::create([
  //       'user_id' => $uid,
  //       'mugawari' => $address,
  //       'thiravi' => $credential,
  //       'thogai' => 0.00,
  //     ]);
  //     Mugawarithx::on('mysqluser')->create([
  //       'user_id' => $uid,
  //       'mugawari' => $address,
  //     ]);
  //   }
  // }


  // public function createMugawarieth($userid)
  // {
  //   $ethaddress = $this->ethcreate(env('ETH_TOKEN_BLOCK'));

  //   $ethtable = new Mugawarieth;
  //   $ethtable->user_id = $userid;
  //   $ethtable->address = Crypt::encryptString($ethaddress->address);
  //   $ethtable->imgenc = Crypt::encryptString($ethaddress->private);
  //   $ethtable->pubk = Crypt::encryptString($ethaddress->public);
  //   $ethtable->available_balance = 0.00000000;
  //   $ethtable->pending_received_balance = 0.00000000;
  //   $ethtable->save(); 

  //   return Crypt::decryptString($ethtable->address); 
  // }


  // public function createMugawarithe($userid)
  // {
  //   $theaddress = $this->ethcreate(env('ETH_TOKEN_BLOCK'));

  //   $thetable = new Mugawarithe;
  //   $thetable->user_id = $userid;
  //   $thetable->address =Crypt::encryptString($theaddress->address);
  //   $thetable->pvtk = Crypt::encryptString($theaddress->private);
  //   $thetable->pubk = Crypt::encryptString($theaddress->public);
  //   $thetable->available_balance = 0.00000000;
  //   $thetable->pending_received_balance = 0.00000000;
  //   $thetable->save();   

  //   return Crypt::decryptString($thetable->address); 

  // }


  // public function createMugawarithx($userid)
  // {
  //   $tchaddress = $this->ethcreate(env('ETH_TOKEN_BLOCK'));

  //   $tchtable = new Mugawarithx;
  //   $tchtable->user_id = $userid;
  //   $tchtable->address = Crypt::encryptString($tchaddress->address);
  //   $tchtable->pvtk = Crypt::encryptString($tchaddress->private);
  //   $tchtable->pubk = Crypt::encryptString($tchaddress->public);
  //   $tchtable->available_balance = 0.00000000;
  //   $tchtable->pending_received_balance = 0.00000000;
  //   $tchtable->save();     

  //   return Crypt::decryptString($tchtable->address); 

  // }


  // public function createMugawarithr($userid)
  // {
  //   $thraddress = $this->ethcreate(env('ETH_TOKEN_BLOCK'));

  //   $thrtable = new Mugawarithr;
  //   $thrtable->user_id = $userid;
  //   $thrtable->address =Crypt::encryptString($thraddress->address); 
  //   $thrtable->pvtk = Crypt::encryptString($thraddress->private);
  //   $thrtable->pubk = Crypt::encryptString($thraddress->public);
  //   $thrtable->available_balance = 0.00000000;
  //   $thrtable->pending_received_balance = 0.00000000;
  //   $thrtable->save(); 
  //   return Crypt::decryptString($thrtable->address); 
  // }

  function ethTxn($uid){
    $currency = "ETH";
    $sel = Mugawarieth::where([['user_id', '=', $uid]])->first(); 
    if($sel){
      $address = Crypt::decryptString($sel->address);   
      $url = "http://api.etherscan.io/api?module=account&action=txlist&address=0x".$address."&startblock=0&endblock=99999999&sort=asc";
      $balance = $this->cUrlss($url);
      $count = count($balance['result']);
      if($count > 0)
      {
        $result_data = $balance['result'];
        for($i = 0; $i < $count; $i++)
        {
          $type = '';
          $from = '';
          $to = '';
          $tx_hash = '';
          $data = $result_data[$i];
          $total = '';
          $tx_hash = $data['hash'];                    
          $total = $this->wei2eth($data['value']);  
          $from = $data['from'];
          $to = $data['to']; 
          $is_txn = UserEthTransaction::where('txid',$tx_hash)->exists();
          if(!$is_txn && $tx_hash!=NULL)
          {
            if('0x'.$address != $from)
            {
              $type = 'received';            
              $total = number_format($total, 8);
              $tota = str_replace(",","",$total);                                
              if ($total != 0)
              {
                $total = bcmul($total,1,8);
                $ethaddress = new UserEthTransaction;
                $ethaddress->user_id = $uid;
                $ethaddress->txid = $tx_hash;
                $ethaddress->type =  $type;
                $ethaddress->recipient = $to;
                $ethaddress->sender = $from;
                $ethaddress->amount = $total;
                $ethaddress->fees = 0.00042;
                $ethaddress->status = 1;
                $ethaddress->confirmations = $data['confirmations'];
                $ethaddress->created_at = date('Y-m-d H:i:s');
                $ethaddress->save();

                $this->cron_user_credit_balance($uid,$total,$currency);  
                $this->depositrefrrels($total,$uid,$currency);
              } 
            }                               
          }
        }
      }
      return $address;
    }
    return true;
  }


  function thrTxn($uid){

    $sel = Mugawarithr::where([['user_id', '=', $uid]])->first();
    $contract_address='0x1cb3209d45b2a60b7fbca1ccdbf87f674237a4aa';
    $currency='THR';
    if($sel){
      $address = Crypt::decryptString($sel->address);
      $url = "https://api.etherscan.io/api?module=account&action=tokentx&contractaddress=".$contract_address."&address=0x".$address;
      $balance = $this->cUrlss($url);
      $count = count($balance['result']);
      if($count > 0)
      {
        $result_data = $balance['result'];
        for($i = 0; $i < $count; $i++)
        {
          $type = '';
          $from = '';
          $to = '';
          $tx_hash = '';
          $data = $result_data[$i];
          $tx_hash = $data['hash'];
          $from = $data['from'];
          $to = $data['to']; 
          $total = $data['value'];
          $is_txn = UserThrTransaction::where('txid',$tx_hash)->exists();
          if(!$is_txn && $tx_hash!=NULL)
          {
            if('0x'.$address != $from)
            {
              $type = 'received';            
              $total = number_format($total, 8);
              $tota = str_replace(",","",$total); 

              if ($total != 0)
              {
                $total = $total/10000;
                $ethaddress = new UserThrTransaction;
                $ethaddress->user_id = $uid;
                $ethaddress->txid = $tx_hash;
                $ethaddress->type = $type;
                $ethaddress->recipient = $to;
                $ethaddress->sender = $from;
                $ethaddress->amount = $total;
                $ethaddress->fees = 0.000563904;
                $ethaddress->status = 1;
                $ethaddress->confirmations = $data['confirmations'];
                $ethaddress->created_at = date('Y-m-d H:i:s',$data['timeStamp']);
                $ethaddress->save();
              }
              $this->cron_user_credit_balance($uid,$total,$currency); 
              $this->depositrefrrels($total,$uid,$currency);
            }
          }
        }
      }
      return $address;
    }
    return true;
  }


  function theTxn($uid){
    $sel = Mugawarithe::where([['user_id', '=', $uid]])->first();
    $contract_address='0x3204dcde0c50b7b2e606587663a0fe2ee8dfb6bf';
    $currency='THE';
    if($sel){
      $address = Crypt::decryptString($sel->address); 
      $url = "https://api.etherscan.io/api?module=account&action=tokentx&contractaddress=".$contract_address."&address=0x".$address;
      $balance = $this->cUrlss($url);
      $count = count($balance['result']);
      if($count > 0)
      {
        $result_data = $balance['result'];
        for($i = 0; $i < $count; $i++)
        {
          $type = '';
          $from = '';
          $to = '';
          $tx_hash = '';
          $data = $result_data[$i];
          $tx_hash = $data['hash'];
          $from = $data['from'];
          $to = $data['to']; 
          $total = $data['value'];
          $is_txn = UserTheTransaction::where('txid',$tx_hash)->exists();
          if(!$is_txn && $tx_hash!=NULL)
          {
            if('0x'.$address != $from)
            {
              $type = 'received';            
              $total = number_format($total, 8);
              $tota = str_replace(",","",$total);

              if ($total != 0)
              {
                $ethaddress = new UserTheTransaction;
                $ethaddress->user_id = $uid;
                $ethaddress->txid = $tx_hash;
                $ethaddress->type = $type;
                $ethaddress->recipient = $to;
                $ethaddress->sender = $from;
                $ethaddress->amount = $total;
                $ethaddress->fees = 0;
                $ethaddress->status = 1;
                $ethaddress->confirmations = $data['confirmations'];
                $ethaddress->created_at = date('Y-m-d H:i:s',$data['timeStamp']);
                $ethaddress->save();
                $this->cron_user_credit_balance($uid,$total,$currency); 
              }
              $this->depositrefrrels($total,$uid,$currency);
            }
          }
        }
      }
      return $address;
    }
    return true;
  }


  function thxTxn($uid){
    $sel = Mugawarithx::where([['user_id', '=', $uid]])->first();
// $contract_address=env('thx_contract_address');
    $contract_address='0xf08c68bd5f4194d994fd70726746bf529ee5a617';
    $currency='THX';
    if($sel){
      $address = Crypt::decryptString($sel->address); 
      $url ="https://api.etherscan.io/api?module=account&action=tokentx&contractaddress=".$contract_address."&address=0x".$address;
      $balance = $this->cUrlss($url);
      $count = count($balance['result']);
      if($count > 0)
      {
        $result_data = $balance['result'];
        for($i = 0; $i < $count; $i++)
        {
          $type = '';
          $from = '';
          $to = '';
          $tx_hash = '';
          $data = $result_data[$i];
          $tx_hash = $data['hash'];
          $from = $data['from'];
          $to = $data['to']; 
          $total = $data['value'];
          $is_txn = UserThxTransaction::where('txid',$tx_hash)->exists();
          if(!$is_txn && $tx_hash!=NULL)
          {
            if('0x'.$address != $from)
            {
              $type = 'received';            
              $total = number_format($total, 8);
              $tota = str_replace(",","",$total);
              if ($total != 0)
              {
                $total = bcmul($total,1,8);
                $ethaddress = new UserThxTransaction;
                $ethaddress->user_id = $uid;
                $ethaddress->txid = $tx_hash;
                $ethaddress->type = $type;
                $ethaddress->recipient = $to;
                $ethaddress->sender = $from;
                $ethaddress->amount = $total;
                $ethaddress->fees = 0.00;
                $ethaddress->status = 1;
                $ethaddress->confirmations = $data['confirmations'];
                $ethaddress->created_at = date('Y-m-d H:i:s',$data['timeStamp']);
                $ethaddress->save();

                $this->cron_user_credit_balance($uid,$total,$currency); 
                $this->depositrefrrels($total,$uid,$currency);
              }
            }
          }
        }
      }
      return $address;
    }
    return true;
  }


  function sendtoAdminThx($uid, $amount)
  {
    $sel = Mugawarithx::where([['user_id', '=', $uid]])->first();
    $address = Crypt::decryptString($sel->address); 
    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';
    $path = '/v2/account/moneyTransfers?currency=THX&type=deposit&from='.$address.'';
    $nonce = round(microtime(true) * 1000);
    $key = base64_decode($secret);
    $message = $path . $nonce;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'X-CREX24-API-KEY:' . $apiKey,
      'X-CREX24-API-NONCE:' . $nonce,
      'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 
  }


  function cron_user_credit_balance($userId,$amount,$currency){

    $currency =$currency;      
    $userbalance = Wallet::where([['uid', '=', $userId], ['currency', '=',$currency]])->first();
    if($userbalance) {
      if($currency == 'ETH'){
//user balance update
        $userbalance = Wallet::where([['uid', '=', $userId], ['currency', '=',$currency]])->first();
        if($userbalance) {
          $total = bcadd($amount, $userbalance->pending_balance,8);
          $userbalance->pending_balance= $total;
          $userbalance->save();        
        } else {
          Wallet::create([
            'user_id' => $uid,
            'currency' => $currency,
            'balance' => 0,
            'pending_balance' => $amount,
          ]);
        }

//eth address balance update
/*   $useraddressbalance = Mugawarieth::where('user_id', '=', $userId)->first();
$useraddressbalance->available_balance =bcadd($useraddressbalance->available_balance, $amount, 8);
$useraddressbalance->save();*/

return  true;
} 

if($currency == 'THX'){
//user balance update
  $userbalance = Wallet::where([['uid', '=', $userId], ['currency', '=',$currency]])->first();
  if($userbalance) {
    $total = bcadd($amount, $userbalance->pending_balance,8);
    $userbalance->pending_balance= $total;
    $userbalance->save();

  } else {
    Wallet::create([
      'user_id' => $uid,
      'currency' => $currency,
      'balance' => 0,
      'pending_balance' => $amount,
    ]);
  }

/*   //thx address balance update
$useraddressbalance = Mugawarithx::where('user_id', '=', $userId)->first();
$useraddressbalance->available_balance =bcadd($useraddressbalance->available_balance, $amount, 8);
$useraddressbalance->save();        */
return  true;
} 

elseif($currency == 'THE'){
//user balance update
  $userbalance = Wallet::where([['uid', '=', $userId], ['currency', '=',$currency]])->first();
  if($userbalance) {
    $total = bcadd($amount, $userbalance->pending_balance,8);
    $userbalance->pending_balance= $total;
    $userbalance->save();
  } else {
    Wallet::create([
      'user_id' => $uid,
      'currency' => $currency,
      'balance' => 0,
      'pending_balance' => $amount,
    ]);
  }

//the address balance update
/*  $useraddressbalance = Mugawarithe::where('user_id', '=', $userId)->first();
$useraddressbalance->available_balance =bcadd($useraddressbalance->available_balance, $amount, 8);
$useraddressbalance->save();      */  
return  true;
}

elseif($currency == 'THR'){

//user balance update
  $userbalance = Wallet::where([['uid', '=', $userId], ['currency', '=',$currency]])->first();

  if($userbalance) {
    $total = bcadd($amount, $userbalance->pending_balance,8);
    $userbalance->pending_balance= $total;
    $userbalance->save();

  } else {
    Wallet::create([
      'user_id' => $uid,
      'currency' => $currency,
      'balance' => 0,
      'pending_balance' => $amount,
    ]);
  }

//thr address balance update
/* $useraddressbalance = Mugawarithr::where('user_id', '=', $userId)->first();
$useraddressbalance->available_balance =bcadd($useraddressbalance->available_balance, $amount, 8);
$useraddressbalance->save();        */
return  true;
}

elseif($currency == 'TCH'){

//user balance update
  $userbalance = Wallet::where([['uid', '=', $userId], ['currency', '=',$currency]])->first();

  if($userbalance) {
    $total = bcadd($amount, $userbalance->pending_balance,8);
    $userbalance->pending_balance= $total;
    $userbalance->save();

  } else {
    Wallet::create([
      'user_id' => $uid,
      'currency' => $currency,
      'balance' => 0,
      'pending_balance' => $amount,
    ]);
  }


return  true;
}
}

else {
  Wallet::insert(['uid' => $uid, 'currency' => $currency, 'balance' => $amount, 'created_at' => date('Y-m-d H:i:s',time()), 'updated_at' => date('Y-m-d H:i:s',time())]);
}
return  true;
}

public function cUrlss($url, $postfilds=null){
  $this->url = $url;
  $this->ch = curl_init();
  curl_setopt($this->ch, CURLOPT_URL, $this->url);                         
  curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
  if(!is_null($postfilds)){
    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postfilds);
  }
  if(strpos($this->url, '?') !== false){
    curl_setopt($this->ch, CURLOPT_POST, 1);
  }
  $headers = array('Content-Length: 0');
  $headers[] = "Content-Type: application/x-www-form-urlencoded";
  curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
  if (curl_errno($this->ch)) {
    $this->result = 'Error:' . curl_error($this->ch);
  } else {
    $this->result = curl_exec($this->ch);
  } 
  curl_close($this->ch);
  return json_decode($this->result, true);
}


function createUserEthTransaction($toaddress, $fromaddress, $amount)
{  
  $tokenblock = env('ETH_TOKEN_BLOCK',null);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.blockcypher.com/v1/eth/main/txs/new?token=$tokenblock");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"inputs\":[{\"addresses\": [\"$fromaddress\"]}],\"outputs\":[{\"addresses\": [\"$toaddress\"], \"value\": $amount}], \"gas_limit\" : 21000, \"gas_price\" : 20000000000 }");
  curl_setopt($ch, CURLOPT_POST, 1);
  $headers = array();
  $headers[] = "Content-Type: application/x-www-form-urlencoded";
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
  }
  curl_close($ch);
  $send = json_decode($result);

  if($send->errors){
    return 'Insufficient Balance';
    exit();
  } elseif($send->error){
    return $send->error;
    exit();
  } elseif($send->tx){
    $f_address = $fromaddress;
    $t_address = $toaddress;
    $from_addr = Crypt::encryptString($f_address);
    $to_addr = Crypt::encryptString($t_address);
    $select = array('address' => $from_addr);
    $private = Mugawarieth::where([['uid', '=', $user],['currency','ETH']])->first();
    if($private){

      $privatekey = Crypt::decryptString($private->pvtk);
      $data = rtrim($result,"}");
      $tosign_count = count($send->tosign);
      $outputs = '';
      for($i = 0; $i < $tosign_count; $i++)
      {
        $tosign = $send->tosign[$i];
        $output = shell_exec($dir."btcutils/signer/signer $tosign $privatekey 2>&1");
        $outputs .= '"'.trim($output).'",';
      }
      $outputs = trim($outputs, ", ");
      $tx = $data.', "signatures" : ['.$outputs.' ] } ';
      $data = $this->sendEthTransaction($tx,$tokenblock);

      if($data->error){
        return 'Transaction failed';
      } 
      elseif($data->tx){
        $hash = $data->tx->hash;
        $total = $this->weitoeth($data->tx->total);
        $fees = $this->weitoeth($data->tx->fees);

        $ethtransaction = new UserEthTransaction;
        $ethtransaction->user_id = $private->uid;
        $ethtransaction->type = 'send';
        $ethtransaction->recipient = $to_addr;
        $ethtransaction->sender = $from_addr;
        $ethtransaction->amount = $total;
        $ethtransaction->confirmations = $fees;
        $ethtransaction->txid = $hash;
        $ethtransaction->created_at = date('Y-m-d H:i:s');
        $ethtransaction->updated_at = date('Y-m-d H:i:s');
        $txinsert = $ethtransaction->save();               
        if($txinsert)
        {
          return 'Success';
        }
        else
        {
          return false;
        }
      }
    }
  }
}


function createAdminEthTransaction($toaddress, $amount)
{  
  $tokenblock = env('ETH_TOKEN_BLOCK',null);
  $private = EthAdminAddress::where([['uid', '=',1]])->first();
  $fromaddress = $this->eth_admin_address_get();
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.blockcypher.com/v1/eth/main/txs/new?token=$tokenblock");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"inputs\":[{\"addresses\": [\"$fromaddress\"]}],\"outputs\":[{\"addresses\": [\"$toaddress\"], \"value\": $amount}], \"gas_limit\" : 21000, \"gas_price\" : 20000000000 }");
  curl_setopt($ch, CURLOPT_POST, 1);
  $headers = array();
  $headers[] = "Content-Type: application/x-www-form-urlencoded";
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
  }
  curl_close($ch);
  $send = json_decode($result);

  if($send->error){
    return 'Insufficient Balance';
    exit();
  } elseif($send->error){
    return $send->error;
    exit();
  } elseif($send->tx){
    $f_address = $fromaddress;
    $t_address = $toaddress;
    $from_addr = $f_address;
    $to_addr = $t_address;
    if($private){

      $privatekey = Crypt::decryptString($private->pvtk);
      $data = rtrim($result,"}");
      $tosign_count = count($send->tosign);
      $outputs = '';
      for($i = 0; $i < $tosign_count; $i++)
      {
        $tosign = $send->tosign[$i];
        $output = shell_exec($dir."btcutils/signer/signer $tosign $privatekey 2>&1");
        $outputs .= '"'.trim($output).'",';
      }
      $outputs = trim($outputs, ", ");
      $tx = $data.', "signatures" : ['.$outputs.' ] } ';
      $data = $this->sendEthTransaction($tx,$tokenblock);

      if($data->error){
        return 'Transaction failed';
      } 
      elseif($data->tx){
        $hash = $data->tx->hash;
        $total = $this->weitoeth($data->tx->total);
        $fees = $this->weitoeth($data->tx->fees);

        $ethtransaction = new EthAdminTransaction;
        $ethtransaction->uid = 1;
        $ethtransaction->type = 'send';
        $ethtransaction->recipient = $to_addr;
        $ethtransaction->sender = $from_addr;
        $ethtransaction->amount = $total;
        $ethtransaction->txid = $hash;
        $ethtransaction->created_at = date('Y-m-d H:i:s');
        $ethtransaction->updated_at = date('Y-m-d H:i:s');
        $txinsert = $ethtransaction->save();               
        if($txinsert)
        {
          return 'Success';
        }
        else
        {
          return false;
        }
      }
    }
  }
}


function wei($amount){
  return number_format((1000000000000000000 * $amount), 0,'.','');
}

function weitoeth($amount){
  return $amount / 1000000000000000000;
}

function sendEthTransaction($tx,$tokenblock)
{

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.blockcypher.com/v1/eth/main/txs/send?token=$tokenblock");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $tx);
  curl_setopt($ch, CURLOPT_POST, 1);
  $headers = array();
  $headers[] = "Content-Type: application/x-www-form-urlencoded";
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
  }
  curl_close($ch);
  return json_decode($result);
}

function updateETHBalance($id)
{

  $sel = Mugawarieth::where([['uid', '=', $id]])->first();
  if($sel){
    $address1 = Crypt::decryptString($sel->address);
    $address = $sel->address;        
    $url = "https://api.blockcypher.com/v1/eth/main/addrs/".$address1."/balance";
    $balance = $this->cUrlss($url);
    if(isset($balance['address'])){             
      if(isset($balance['balance'])){
        $data = Mugawarieth::where(['address'=>$address])->update(['available_balance' => $this->weitoeth($balance['balance'])]);
        if($data){                        
          return true;
        }
      }
    }
  }
}
function updateETHTxnsBalance($id)
{
  $sel = Mugawarieth::where([['uid', '=', $id]])->first();
  if($sel){
    $address1 = Crypt::decryptString($sel->address);
    $address = $sel->address;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.blockcypher.com/v1/eth/main/addrs/".$address1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $headers = array();
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    $send = json_decode($result, true);
    $uncofirm_count = count($send['unconfirmed_txrefs']);
    $cofirm_count = count($send['txrefs']);
    if($send['final_balance'] < 0 || $send['final_balance'] == 0)
    {
      $update_balance = parent::dbRowUpdate(COIN_USER_ETH_ADDRESS, array('available_balance' => 0.00000000), array('uid' => $id));
      parent::dbRowUpdate(USER_WALLET, array('eth_mathipu' => self::weitoeth($balance['balance'])), array('user_id' => $id));
      return true;
    }
    else
    {
      $final_balance = self::weitoeth($send['final_balance']);
      $update_balance = parent::dbRowUpdate(COIN_USER_ETH_ADDRESS, array('available_balance' => $final_balance), array('uid' => $id));
      if($update_balance)
      {
        if($uncofirm_count > 0)
        {
          for($i = 0; $i <= $uncofirm_count; $i++)
          {
            $tx_hash = $send['unconfirmed_txrefs'][$i]['tx_hash'];
            if($tx_hash)
            {
              $transaction = self::txns($tx_hash);
              $sender = $transaction['inputs']['0']['addresses']['0'];
              $receiver = $transaction['outputs']['0']['addresses']['0'];
              $fees = self::weitoeth($transaction['fees']);
              $total = self::weitoeth($transaction['outputs']['0']['value']);
              $select_user = parent::dbSelect(COIN_USER_ETH_ADDRESS, array('address' => $receiver));
              if($select_user)
              {
                $is_txn = parent::dbSelect(COIN_ETH_TRANSACTION, array('txid' => $tx_hash));
                if(!$is_txn)
                {
                  $sender = parent::encrypt($sender, $setkey, $iv);
                  $receiver = parent::encrypt($receiver, $setkey, $iv);
                  $insert = array(
                    'uid' => $select_user->uid,
                    'type' => 'received',
                    'recipient' => $receiver,
                    'sender' => $sender,
                    'amount' => $total,
                    'fees' => $fees,
                    'confirmations' => 0,
                    'created' => date('Y-m-d H:i:s'),
                    'txid' => $tx_hash
                  );
                  $txinsert = parent::dbInsert(COIN_ETH_TRANSACTION, $insert);
                }
              }
            }
          }
        }
        if($cofirm_count > 0)
        {
          for($i = 0; $i <= $cofirm_count; $i++)
          {
            $tx_hash = $send['txrefs'][$i]['tx_hash'];
            if($tx_hash)
            {
              $transaction = self::txns($tx_hash);
              $sender = $transaction['inputs']['0']['addresses']['0'];
              $receiver = $transaction['outputs']['0']['addresses']['0'];
              $fees = self::weitoeth($transaction['fees']);
              $total = self::weitoeth($transaction['outputs']['0']['value']);
              $confirmations = $transaction['confirmations'];
              $select_user = parent::dbSelect(COIN_USER_ETH_ADDRESS, array('address' => $receiver));
              if($select_user)
              {
                $is_txn = parent::dbSelect(COIN_ETH_TRANSACTION, array('txid' => $tx_hash));
                if(!$is_txn)
                {
                  $sender = parent::encrypt($sender, $setkey, $iv);
                  $receiver = parent::encrypt($receiver, $setkey, $iv);
                  $insert = array(
                    'uid' => $select_user->uid,
                    'type' => 'received',
                    'recipient' => $receiver,
                    'sender' => $sender,
                    'amount' => $total,
                    'fees' => $fees,
                    'confirmations' => 0,
                    'created' => date('Y-m-d H:i:s'),
                    'txid' => $tx_hash
                  );
                  $txinsert = parent::dbInsert(COIN_ETH_TRANSACTION, $insert);
                }
                else
                {
                  $update_txn = parent::dbRowUpdate(COIN_ETH_TRANSACTION, array('confirmations' => $confirmations), array('txid' => $tx_hash));
                }
              }
            }
          }
        }
      }
      return true;
    }
    return true;
  }
  return true;
}
function updateAdminETHTxnsBalance()
{
  global $setkey, $iv, $dir, $tokenblock;
  $id=1;
  $select = array('uid' => $id);      
  $sel = parent::dbSelect(ETH_ADMIN_ADDRESS, $select);
  if($sel){
    $address1 = parent::decrypt($sel->address, $setkey, $iv);
    $address = $sel->address;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.blockcypher.com/v1/eth/main/addrs/".$address1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $headers = array();
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    $send = json_decode($result, true);
    $uncofirm_count = count($send['unconfirmed_txrefs']);
    $cofirm_count = count($send['txrefs']);
    if($send['final_balance'] < 0 || $send['final_balance'] == 0)
    {
      $update_balance = parent::dbRowUpdate(ETH_ADMIN_ADDRESS, array('available_balance' => 0.00000000), array('uid' => $id));
      return true;
    }
    else
    {
      $final_balance = self::weitoeth($send['final_balance']);
      $update_balance = parent::dbRowUpdate(ETH_ADMIN_ADDRESS, array('available_balance' => $final_balance), array('uid' => $id));
      if($update_balance)
      {
        if($uncofirm_count > 0)
        {
          for($i = 0; $i <= $uncofirm_count; $i++)
          {
            $tx_hash = $send['unconfirmed_txrefs'][$i]['tx_hash'];
            if($tx_hash)
            {
              $transaction = self::txns($tx_hash);
              $sender = $transaction['inputs']['0']['addresses']['0'];
              $receiver = $transaction['outputs']['0']['addresses']['0'];
              $fees = self::weitoeth($transaction['fees']);
              $total = self::weitoeth($transaction['outputs']['0']['value']);
              $select_user = parent::dbSelect(COIN_USER_ETH_ADDRESS, array('address' => $receiver));
              if($select_user)
              {
                $is_txn = parent::dbSelect(ETH_ADMIN_TRANSACTIONS, array('txid' => $tx_hash));
                if(!$is_txn)
                {
                  $sender = parent::encrypt($sender, $setkey, $iv);
                  $receiver = parent::encrypt($receiver, $setkey, $iv);
                  $insert = array(
                    'uid' => $select_user->uid,
                    'type' => 'received',
                    'recipient' => $receiver,
                    'sender' => $sender,
                    'amount' => $total,
                    'fees' => $fees,
                    'confirmations' => 0,
                    'created' => date('Y-m-d H:i:s'),
                    'txid' => $tx_hash
                  );
                  $txinsert = parent::dbInsert(ETH_ADMIN_TRANSACTIONS, $insert);
                }
              }
            }
          }
        }
        if($cofirm_count > 0)
        {
          for($i = 0; $i <= $cofirm_count; $i++)
          {
            $tx_hash = $send['txrefs'][$i]['tx_hash'];
            if($tx_hash)
            {
              $transaction = self::txns($tx_hash);
              $sender = $transaction['inputs']['0']['addresses']['0'];
              $receiver = $transaction['outputs']['0']['addresses']['0'];
              $fees = self::weitoeth($transaction['fees']);
              $total = self::weitoeth($transaction['outputs']['0']['value']);
              $confirmations = $transaction['confirmations'];
              $select_user = parent::dbSelect(COIN_USER_ETH_ADDRESS, array('address' => $receiver));
              if($select_user)
              {
                $is_txn = parent::dbSelect(ETH_ADMIN_TRANSACTIONS, array('txid' => $tx_hash));
                if(!$is_txn)
                {
                  $sender = parent::encrypt($sender, $setkey, $iv);
                  $receiver = parent::encrypt($receiver, $setkey, $iv);
                  $insert = array(
                    'uid' => $select_user->uid,
                    'type' => 'received',
                    'recipient' => $receiver,
                    'sender' => $sender,
                    'amount' => $total,
                    'fees' => $fees,
                    'confirmations' => 0,
                    'created' => date('Y-m-d H:i:s'),
                    'txid' => $tx_hash
                  );
                  $txinsert = parent::dbInsert(ETH_ADMIN_TRANSACTIONS, $insert);
                }
                else
                {
                  $update_txn = parent::dbRowUpdate(ETH_ADMIN_TRANSACTIONS, array('confirmations' => $confirmations), array('txid' => $tx_hash));
                }
              }
            }
          }
        }
      }
      return true;
    }
    return true;
  }
  return true;
}
function txns($tx)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.blockcypher.com/v1/eth/main/txs/$tx");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $headers = array();
  $headers[] = "Content-Type: application/x-www-form-urlencoded";
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
  }   
  curl_close($ch);
  $send = json_decode($result, true);
  return $send;
}

function wei2eth($amount){
  return $amount / 1000000000000000000;
}

}

?>