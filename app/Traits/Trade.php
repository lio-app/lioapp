<?php 
namespace App\Traits;
use Blockchain\Btc\Facades\Blockchain;
use App\Traits\BlockchainCredentials;
use App\Modals\Selltrade;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use App\Modals\CompleteTrade;
use App\Modals\TranstableBtc;
use App\Traits\UserInfo;
use App\Modals\TranstableLtc;
use App\Modals\TranstableEth;
use App\Modals\Buytrade;

trait Trade {
  function buyBtcLtcProcess($pair, $insertid, $amount, $volume)
  {

    $needed     = $volume;
    $admins_addres = $this->btcAdminaddress();
    $admins_address = Crypt::decryptString($admins_addres->address);

    $admins_addres_ltc = $this->ltcAdminaddress();
    $admins_address_ltc = Crypt::decryptString($admins_addres_ltc->address);

    $uid        = \Auth::id();    
    $user_address_btc = $this->getUserBtcAddress($uid);
    $user_address_ltc = $this->getUserLtcAddress($uid);
    $actualbal  = $this->getUserLtcBalance();
    $Ttl_LTC    = bcmul($volume,$amount,8);


    $trades = Selltrade::where([
      ['uid', \Auth::id()],
      ['remaining', '!=', 0],
      ['pair', $pair]
    ])
    ->where('stop_limit', '>=',  $amount)
    ->where('price', '<=',  $amount) 
    ->orWhere('price', '=',  $amount)
    ->orderBy('price', 'DESC')->get();


// $trade = json_decode($myClass->dbSelectAll(SELL_TRADE_BTCETH." WHERE uid!=$uid  AND remaining!=0  AND (amount=$amount OR (stop_limit >= $amount AND amount <= $amount))",'ORDER BY amount DESC'));
    if($trades->count() > 0){
      foreach($trades as $cal_amount){
        $buy_price = $amount;
        $close_price = $amount;
        $start_volume = $cal_amount->remaining;
        $needed = $needed;
        if($start_volume >= $needed AND $needed!=0)
        {
          $salesAmount = bcmul($needed , $buy_price,8);


          $insert = new CompleteTrade;
          $insert->pair = $pair;
          $insert->order_type = 1;
          $insert->buy_id = $insertid;
          $insert->sales_id = $cal_amount->id;
          $insert->quantity = $needed;
          $insert->amount = $salesAmount;
          $insert->market_price = $close_price;
          $insert->save();

          $limit_btc      = $needed;
          $limit_userid   = $cal_amount->uid;

// update selltrade 
          $close_remain = bcsub($cal_amount->remaining, $needed, 8);
          $seller_address_org = $this->getUserBtcAddress($limit_userid);
          if($close_remain == 0)
          {

            $updatedata = Selltrade::where('id', $cal_amount->id)->first();
            $updatedata->remaining = $close_remain;
            $updatedata->status = 1;
            $updatedata->save();

            $adminCommission_btc = $this->adminCommissionBtc($cal_amount->volume);
            $totalBtc = bcadd(bcadd($adminCommission_btc , $limit_btc ,8), 0.0001,8);
//BTC SEND
//seller address 

            $sended_BTC = new TranstableBtc;
            $sended_BTC->fromaddr = Crypt::encryptString($seller_address_org);
            $sended_BTC->toaddr = Crypt::encryptString($user_address_btc);
            $sended_BTC->addminaddr = Crypt::encryptString($admins_address);
            $sended_BTC->amount1 = $limit_btc;
            $sended_BTC->amount2 = $adminCommission_btc;
            $sended_BTC->status = 0;
            $sended_BTC->save();


//LTC SEND
//buyer address
            $from_address_org = $this->getUserLtcAddress($uid);
            $to_address_org = $this->getUserLtcAddress($limit_userid);
            $AmtRelased_LTC = bcmul($cal_amount->volume , $buy_price,8);
            $adminCommission_ltc = $this->adminCommissionLtc($AmtRelased_LTC);

            $sendedLtc = new TranstableLtc;
            $sendedLtc->fromaddr = Crypt::encryptString($from_address_org);
            $sendedLtc->toaddr = Crypt::encryptString($to_address_org);
            $sendedLtc->addminaddr = Crypt::encryptString($admins_addres_ltc);
            $sendedLtc->amount1 = $AmtRelased_LTC;
            $sendedLtc->amount2 = $adminCommission_ltc;
            $sendedLtc->status = 0;
            $sendedLtc->save();

          } 
          else 
          {

            $updatedata = Selltrade::where('id', $cal_amount->id)->first();
            $updatedata->remaining = $close_remain;
            $updatedata->save();

            $totalBtc = bcadd($limit_btc , 0.0001,8);
//$myClass->userbalanceupdatebtc($limit_userid,$totalBtc,'debit');
//BTC SEND

            $sended_BTC = new TranstableBtc;
            $sended_BTC->fromaddr = Crypt::encryptString($seller_address_org);
            $sended_BTC->toaddr = Crypt::encryptString($user_address_btc);
            $sended_BTC->addminaddr = '';
            $sended_BTC->amount1 = $limit_btc;
            $sended_BTC->amount2 = 0;
            $sended_BTC->status = 0;
            $sended_BTC->save();


//LTC SEND
            $from_address_org = $this->getUserLtcAddress($uid);
            $to_address_org = $tradeCoin->getUserLtcAddress($limit_userid);
            $AmtRelased_LTC = bcmul($limit_btc , $buy_price,8);

// $insert = array(
//     'fromaddr' => $myClass->encrypt($from_address_org, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($to_address_org, $setkey, $iv),
//     'addminaddr' => '',
//     'amount1' => $AmtRelased_LTC,
//     'amount2' => 0,
//     'status' => 0
// );
// $sended_LTC = $myClass->dbInsert(TRANSTABLE_ETH, $insert);

            $sendedLtc = new TranstableLtc;
            $sendedLtc->fromaddr = Crypt::encryptString($from_address_org);
            $sendedLtc->toaddr = Crypt::encryptString($to_address_org);
            $sendedLtc->addminaddr = '';
            $sendedLtc->amount1 = $AmtRelased_LTC;
            $sendedLtc->amount2 = 0;
            $sendedLtc->status = 0;
            $sendedLtc->save();

          }

// $where = array('id' => $cal_amount->id);
// $updatedata = $myClass->dbRowUpdate(SELL_TRADE_BTCETH, $update1, $where);



          $limitcomplete = Buytrade::where('id', $cal_amount->id)->first();
          $limitcomplete->remaining = 0;
          $limitcomplete->remaining = 1;
          $limitcomplete->save();

// $limitcomplete = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, array('remaining' => 0, 'status' => 1), array('id' => $insertid));

          $remaining  = $needed - $needed;



        }
        else if($start_volume < $needed AND $needed!=0) 
        {
          $remaining = bcsub($needed , $start_volume, 8);
          $buyAmount = bcmul($cal_amount->remaining , $buy_price,8);
// $insert = array(
//     'buy_id'    => $insertid,
//     'sales_id'  => $cal_amount->id,
//     'quantity'  => $cal_amount->remaining,
//     'amount'    => $buyAmount,
//     'market_price'  => $close_price,
//     'created'   => date('Y-m-d H:i:s', time())
// );
// $insert         = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $insert = new CompleteTrade;
          $insert->buy_id = $insertid;
          $insert->sales_id = $cal_amount->id;
          $insert->quantity = $cal_amount->remaining;
          $insert->amount = $buyAmount;
          $insert->market_price = $close_price;
          $insert->save();


// $update         = array('remaining' => 0, 'status' => 1);
// $where          = array('id' => $cal_amount->id);

// $updatedata     = $myClass->dbRowUpdate(SELL_TRADE_BTCETH, $update, $where);

          $updatedata = Selltrade::where('id', $cal_amount->id)->first();
          $updatedata->remaining = 0;
          $updatedata->status = 1;
          $updatedata->save();


// $limitcomplete = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, array('remaining' => $remaining), array('id' => $insertid));

          $limitcomplete = Buytrade::where('id', $insertid)->first();
          $limitcomplete->remaining = $remaining;
          $limitcomplete->save();

          $limit_userid   = $cal_amount->uid;
          $limit_btc      = $cal_amount->remaining;
          $seller_address_org = $this->getUserBtcAddress($limit_userid);
          $adminCommission = $this->adminCommissionBtc($cal_amount->volume);
          $totalBtc = bcadd(bcadd($adminCommission , $limit_btc, 8) , 0.0001, 8);
//$myClass->userbalanceupdatebtc($limit_userid,$totalBtc,'debit');
//BTC SEND
// $insert_BTC = array(
//         'fromaddr' => $myClass->encrypt($seller_address_org, $setkey, $iv),
//         'toaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//         'addminaddr' => $myClass->encrypt($admins_address, $setkey, $iv),
//         'amount1' => $limit_btc,
//         'amount2' => $adminCommission,
//         'status' => 0
//     );
// $sended_BTC = $myClass->dbInsert(TRANSTABLE_BTC, $insert_BTC);


          $insert_BTC = new TranstableBtc;
          $insert_BTC->fromaddr = Crypt::encryptString($seller_address_org);
          $insert_BTC->toaddr = Crypt::encryptString($user_address_btc);
          $insert_BTC->addminaddr = Crypt::encryptString($admins_address);
          $insert_BTC->amount1 = $limit_btc;
          $insert_BTC->amount2 = $adminCommission;
          $insert_BTC->status = 0;
          $insert_BTC->save();


//LTC SEND
          $from_address_org = $this->getUserLtcAddress($uid);
          $to_address_org = $this->getUserLtcAddress($limit_userid);
          $AmtRelased_LTC = bcmul($limit_btc , $buy_price,8);
          $adminCommission_ltc = $this->adminCommissionLtc($AmtRelased_LTC);

// $insert_ETH = array(
//         'fromaddr' => $myClass->encrypt($from_address_org, $setkey, $iv),
//         'toaddr' => $myClass->encrypt($to_address_org, $setkey, $iv),
//         'addminaddr' => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//         'amount1' => $AmtRelased_LTC,
//         'amount2' => $adminCommission_ltc,
//         'status' => 0
//     );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_ETH);

          $sended_LTC = new TranstableLtc;
          $sended_LTC->fromaddr = Crypt::encryptString($from_address_org);
          $sended_LTC->toaddr = Crypt::encryptString($to_address_org);
          $sended_LTC->addminaddr = Crypt::encryptString($admins_address_ltc);
          $sended_LTC->amount1 = $AmtRelased_LTC;
          $sended_LTC->amount2 = $adminCommission_ltc;
          $sended_LTC->status = 0;
          $sended_LTC->save();

//$remaining    = $needed - $cal_amount->remaining;
        }
        $needed = $remaining;
        if($needed == 0){
          break;
        }   
      }                                    

    }                    



  }



  function buyBtcEthProcess($pair, $insertid, $amount, $volume)
  {

    $needed     = $volume;
    $admins_addres = $this->btcAdminaddress();
    $admins_address = Crypt::decryptString($admins_addres->address); 

    $admins_addres_eth = $this->ethAdminaddress();
    $admins_address_eth = Crypt::decryptString($admins_addres_eth->address);

    $uid        = \Auth::id();    
    $user_address_btc = $this->getUserBtcAddress($uid);
    $user_address_eth = $this->getUserEthAddress($uid);
    $actualbal  = $this->getUserEthBalance();
    $Ttl_ETH    = bcmul($volume,$amount,8);

    $trades = Selltrade::where([
      ['uid', \Auth::id()],
      ['remaining', '!=', 0],
      ['pair', $pair]                                        
    ])
    ->where('stop_limit', '>=',  $amount)
    ->where('price', '<=',  $amount) 
    ->orWhere('price', '=',  $amount)
    ->orderBy('price', 'DESC')->get();


// $trade = json_decode($myClass->dbSelectAll(SELL_TRADE_BTCETH." WHERE uid!=$uid  AND remaining!=0  AND (amount=$amount OR (stop_limit >= $amount AND amount <= $amount))",'ORDER BY amount DESC'));
    if($trades->count() > 0){
      foreach($trades as $cal_amount){
        $buy_price = $amount;
        $close_price = $amount;
        $start_volume = $cal_amount->remaining;
        $needed = $needed;
        if($start_volume >= $needed AND $needed!=0)
        {
          $salesAmount = bcmul($needed , $buy_price,8);


          $insert = new CompleteTrade;
          $insert->pair = $pair;
          $insert->order_type = 1;
          $insert->buy_id = $insertid;
          $insert->sales_id = $cal_amount->id;
          $insert->quantity = $needed;
          $insert->amount = $salesAmount;
          $insert->market_price = $close_price;
          $insert->save();

          $limit_btc      = $needed;
          $limit_userid   = $cal_amount->uid;

// update selltrade 
          $close_remain = bcsub($cal_amount->remaining, $needed, 8);
          $seller_address_org = $this->getUserBtcAddress($limit_userid);
          if($close_remain == 0)
          {

            $updatedata = Selltrade::where('id', $cal_amount->id)->first();
            $updatedata->remaining = $close_remain;
            $updatedata->status = 1;
            $updatedata->save();

            $adminCommission_btc = $this->adminCommissionBtc($cal_amount->volume);
            $totalBtc = bcadd(bcadd($adminCommission_btc , $limit_btc ,8), 0.0001,8);

//BTC SEND
//seller address 

            $sended_BTC = new TranstableBtc;
            $sended_BTC->fromaddr = Crypt::encryptString($seller_address_org);
            $sended_BTC->toaddr = Crypt::encryptString($user_address_btc);
            $sended_BTC->addminaddr = Crypt::encryptString($admins_address);
            $sended_BTC->amount1 = $limit_btc;
            $sended_BTC->amount2 = $adminCommission_btc;
            $sended_BTC->status = 0;
            $sended_BTC->save();


//ETH SEND
//buyer address
            $from_address_org = $this->getUserEthAddress($uid);
            $to_address_org = $this->getUserEthAddress($limit_userid);
            $AmtRelased_ETH = bcmul($cal_amount->volume , $buy_price,8);
            $adminCommission_eth = $this->adminCommissionEth($AmtRelased_Eth);

            $sendedEth = new TranstableEth;
            $sendedEth->fromaddr = Crypt::encryptString($from_address_org);
            $sendedEth->toaddr = Crypt::encryptString($to_address_org);
            $sendedEth->addminaddr = Crypt::encryptString($admins_addres_eth);
            $sendedEth->amount1 = $AmtRelased_ETH;
            $sendedEth->amount2 = $adminCommission_eth;
            $sendedEth->status = 0;
            $sendedEth->save();

          } 
          else 
          {

            $updatedata = Selltrade::where('id', $cal_amount->id)->first();
            $updatedata->remaining = $close_remain;
            $updatedata->save();

            $totalBtc = bcadd($limit_btc , 0.0001,8);
//$myClass->userbalanceupdatebtc($limit_userid,$totalBtc,'debit');
//BTC SEND

            $sended_BTC = new TranstableBtc;
            $sended_BTC->fromaddr = Crypt::encryptString($seller_address_org);
            $sended_BTC->toaddr = Crypt::encryptString($user_address_btc);
            $sended_BTC->addminaddr = '';
            $sended_BTC->amount1 = $limit_btc;
            $sended_BTC->amount2 = 0;
            $sended_BTC->status = 0;
            $sended_BTC->save();


//ETH SEND
            $from_address_org = $this->getUserEthAddress($uid);
            $to_address_org = $tradeCoin->getUserEthAddress($limit_userid);
            $AmtRelased_ETH = bcmul($limit_btc , $buy_price,8);

// $insert = array(
//     'fromaddr' => $myClass->encrypt($from_address_org, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($to_address_org, $setkey, $iv),
//     'addminaddr' => '',
//     'amount1' => $AmtRelased_ETH,
//     'amount2' => 0,
//     'status' => 0
// );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert);

            $sendedEth = new TranstableEth;
            $sendedEth->fromaddr = Crypt::encryptString($from_address_org);
            $sendedEth->toaddr = Crypt::encryptString($to_address_org);
            $sendedEth->addminaddr = '';
            $sendedEth->amount1 = $AmtRelased_ETH;
            $sendedEth->amount2 = 0;
            $sendedEth->status = 0;
            $sendedEth->save();

          }

// $where = array('id' => $cal_amount->id);
// $updatedata = $myClass->dbRowUpdate(SELL_TRADE_BTCETH, $update1, $where);                                        

          $limitcomplete = Buytrade::where('id', $cal_amount->id)->first();
          $limitcomplete->remaining = 0;
          $limitcomplete->remaining = 1;
          $limitcomplete->save();

// $limitcomplete = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, array('remaining' => 0, 'status' => 1), array('id' => $insertid));

          $remaining  = $needed - $needed;



        }
        else if($start_volume < $needed AND $needed!=0) 
        {
          $remaining = bcsub($needed , $start_volume, 8);
          $buyAmount = bcmul($cal_amount->remaining , $buy_price,8);
// $insert = array(
//     'buy_id'    => $insertid,
//     'sales_id'  => $cal_amount->id,
//     'quantity'  => $cal_amount->remaining,
//     'amount'    => $buyAmount,
//     'market_price'  => $close_price,
//     'created'   => date('Y-m-d H:i:s', time())
// );
// $insert         = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $insert = new CompleteTrade;
          $insert->buy_id = $insertid;
          $insert->sales_id = $cal_amount->id;
          $insert->quantity = $cal_amount->remaining;
          $insert->amount = $buyAmount;
          $insert->market_price = $close_price;
          $insert->save();


// $update         = array('remaining' => 0, 'status' => 1);
// $where          = array('id' => $cal_amount->id);

// $updatedata     = $myClass->dbRowUpdate(SELL_TRADE_BTCETH, $update, $where);

          $updatedata = Selltrade::where('id', $cal_amount->id)->first();
          $updatedata->remaining = 0;
          $updatedata->status = 1;
          $updatedata->save();


// $limitcomplete = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, array('remaining' => $remaining), array('id' => $insertid));

          $limitcomplete = Buytrade::where('id', $insertid)->first();
          $limitcomplete->remaining = $remaining;
          $limitcomplete->save();

          $limit_userid   = $cal_amount->uid;
          $limit_btc      = $cal_amount->remaining;
          $seller_address_org = $this->getUserBtcAddress($limit_userid);
          $adminCommission = $this->adminCommissionBtc($cal_amount->volume);
          $totalBtc = bcadd(bcadd($adminCommission , $limit_btc, 8) , 0.0001, 8);
//$myClass->userbalanceupdatebtc($limit_userid,$totalBtc,'debit');
//BTC SEND
// $insert_BTC = array(
//         'fromaddr' => $myClass->encrypt($seller_address_org, $setkey, $iv),
//         'toaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//         'addminaddr' => $myClass->encrypt($admins_address, $setkey, $iv),
//         'amount1' => $limit_btc,
//         'amount2' => $adminCommission,
//         'status' => 0
//     );
// $sended_BTC = $myClass->dbInsert(TRANSTABLE_BTC, $insert_BTC);


          $insert_BTC = new TranstableBtc;
          $insert_BTC->fromaddr = Crypt::encryptString($seller_address_org);
          $insert_BTC->toaddr = Crypt::encryptString($user_address_btc);
          $insert_BTC->addminaddr = Crypt::encryptString($admins_address);
          $insert_BTC->amount1 = $limit_btc;
          $insert_BTC->amount2 = 0;
          $insert_BTC->status = $adminCommission;
          $insert_BTC->save();


//ETH SEND
          $from_address_org = $this->getUserEthAddress($uid);
          $to_address_org = $this->getUserEthAddress($limit_userid);
          $AmtRelased_ETH = bcmul($limit_btc , $buy_price,8);
          $adminCommission_eth = $this->adminCommissionEth($AmtRelased_ETH);

// $insert_ETH = array(
//         'fromaddr' => $myClass->encrypt($from_address_org, $setkey, $iv),
//         'toaddr' => $myClass->encrypt($to_address_org, $setkey, $iv),
//         'addminaddr' => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//         'amount1' => $AmtRelased_LTC,
//         'amount2' => $adminCommission_ltc,
//         'status' => 0
//     );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_ETH);

          $sended_ETH = new TranstableEth;
          $sended_ETH->fromaddr = Crypt::encryptString($from_address_org);
          $sended_ETH->toaddr = Crypt::encryptString($to_address_org);
          $sended_ETH->addminaddr = Crypt::encryptString($admins_addres_eth);
          $sended_ETH->amount1 = $AmtRelased_ETH;
          $sended_ETH->amount2 = $adminCommission_eth;
          $sended_ETH->status = 0;
          $sended_ETH->save();

//$remaining    = $needed - $cal_amount->remaining;
        }
        $needed = $remaining;
        if($needed == 0){
          break;
        }   
      }                                      

    }                     


  }

  function sellBtcEthProcess($pair, $insertid, $amount, $volume)
  {

    $admins_addres = $this->btcAdminaddress();
    $admins_address = Crypt::decryptString($admins_addres->address);
    $admins_addres_eth = $this->ethAdminaddress();
    $admins_address_eth = Crypt::decryptString($admins_addres_eth->address);

// $trade = json_decode($myClass->dbSelectAll(BUY_TRADE_BTCETH." WHERE uid!=$uid  AND remaining!=0 AND (amount=$amount OR (stop_limit <= $amount AND amount >= $amount))",'ORDER BY amount ASC'));

    $trades = Buytrade::where([
      ['uid', \Auth::id()],
      ['remaining', '!=', 0],
      ['pair', $pair]                                        
    ])
    ->where('stop_limit', '<=',  $amount)
    ->where('price', '>=',  $amount) 
    ->orWhere('price', $amount)                    
    ->orderBy('price', 'ASC')->get();


    if($trades->count() > 0){         
      $recipients = array();
      foreach($trades as $cal_amount){
        $start_volume = $cal_amount->remaining;
        $start_volume = $start_volume;
        $close_price = $amount;
        $needed = $needed;
        if($start_volume >= $needed AND $needed!=0){
          $salesAmount = $needed;
          $salesBtc = bcmul($needed , $close_price, 8);         
// $insert = array(
//     'buy_id' => $cal_amount->id,
//     'sales_id' => $insertid,
//     'quantity' => $needed,
//     'amount' => $salesBtc,
//     'market_price'  => $close_price,
//     'created' => date('Y-m-d H:i:s', time())
//   );
// $insert = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $insert = new CompleteTrade;
          $insert->pair = $pair;
          $insert->buy_id = $cal_amount->id;
          $insert->sales_id = $insertid;
          $insert->quantity = $needed;
          $insert->amount = $salesBtc;
          $insert->market_price = $close_price;
          $insert->save();

          $close_remain = bcsub($cal_amount->remaining , $needed, 8);
          if($close_remain == 0)
          {

            $updatedata = Buytrade::where('id', $cal_amount->id)->first();
            $updatedata->remaining = $close_remain; 
            $updatedata->status = 1;
            $updatedata->save();

// $update1 = array('remaining' => $close_remain, 'status' => 1);
          } 
          else 
          {

            $updatedata = Buytrade::where('id', $cal_amount->id)->first();
            $updatedata->remaining = $close_remain; 
            $updatedata->save();
// $update1 = array('remaining' => $close_remain);
          }
// $where = array('id' => $cal_amount->id);
// $updatedata = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, $update1, $where);

// $limitcomplete = $myClass->dbRowUpdate(SELL_TRADE_BTCETH, array('remaining' => 0, 'status' => 1), array('id' => $insertid));

          $updatedata = Selltrade::where('id', $insertid)->first();
          $updatedata->remaining = 0; 
          $updatedata->status = 1;
          $updatedata->save();

          $limit_userid = $cal_amount->uid;
          $buyer_address_org = $this->getUserBtcAddress($limit_userid);
//BTC SEND
// $insert = array(
//     'fromaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($buyer_address_org, $setkey, $iv),
//     'addminaddr' => $myClass->encrypt($admins_address, $setkey, $iv),
//     'amount1' => $needed,
//     'amount2' => $adminCommission,
//     'status' => 0
//   );
// $sended = $myClass->dbInsert(TRANSTABLE_BTC, $insert);

          $sended = new TranstableBtc;
          $sended->fromaddr = Crypt::encryptString($user_address_btc);
          $sended->toaddr = Crypt::encryptString($buyer_address_org);
          $sended->addminaddr = Crypt::encryptString($admins_address);
          $sended->amount1 = $needed;
          $sended->amount2 = $adminCommission;
          $sended->status = 0;
          $sended->save();

//ETH SEND
//buyer address
          $from_address_org = $this->getUserEthAddress($limit_userid);
          $to_address_org = $this->getUserEthAddress($uid);
          $AmtRelased_ETH = bcmul($needed , $close_price,8);
          $adminCommission_eth = $this->adminCommissionEth($AmtRelased_ETH);
// $insert_eth = array(
//   'fromaddr'    => $myClass->encrypt($from_address_org, $setkey, $iv),
//   'toaddr'    => $myClass->encrypt($to_address_org, $setkey, $iv),
//   'addminaddr'  => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//   'amount1'     => $AmtRelased_ETH,
//   'amount2'     => $adminCommission_eth,
//   'status'    => 0
// );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_eth);

          $sendedETH = new TranstableEth;
          $sendedETH->fromaddr = Crypt::encryptString($from_address_org);
          $sendedETH->toaddr = Crypt::encryptString($to_address_org);
          $sendedETH->addminaddr = Crypt::encryptString($admins_address_eth);
          $sendedETH->amount1 = $AmtRelased_ETH;
          $sendedETH->amount2 = $adminCommission_eth;
          $sendedETH->status = 0;
          $sendedETH->save();


          if($sended){

//Remaining Updated
            $remaining = bcsub($needed , $needed);
          }

        } else if($start_volume < $needed AND $needed!=0){
          $remaining = bcsub($needed , $start_volume, 8);
          $buyAmount = bcmul($cal_amount->remaining , $close_price, 8);
// $insert = array(
//   'buy_id'  => $cal_amount->id,
//   'sales_id'  => $insertid,
//   'quantity'  => $start_volume,
//   'amount'  => $buyAmount,
//   'market_price'  => $close_price,
//   'created'   => date('Y-m-d H:i:s', time())
// );
// $insert = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $insert = new CompleteTrade;
          $insert->pair = $pair;
          $insert->buy_id = $cal_amount->id;
          $insert->sales_id = $insertid;
          $insert->quantity = $start_volume;
          $insert->amount = $buyAmount;
          $insert->market_price = $close_price;
          $insert->save();


          $limit_userid     = $cal_amount->uid;
          $buyer_address_org  = $this->getUserBtcAddress($limit_userid);

// $insert = array(
//     'fromaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($buyer_address_org, $setkey, $iv),
//     'addminaddr' => '',
//     'amount1' => $start_volume,
//     'amount2' => 0,
//     'status' => 0
//   );
// $sended = $myClass->dbInsert(TRANSTABLE_BTC, $insert);

          $sended = new TranstableBtc;
          $sended->fromaddr = Crypt::encryptString($user_address_btc);
          $sended->toaddr = Crypt::encryptString($buyer_address_org);
          $sended->addminaddr = '';
          $sended->amount1 = $start_volume;
          $sended->amount2 = 0;
          $sended->status = 0;
          $sended->save();


//ETH SEND
//buyer address
          $from_address_org = $this->getUserEthAddress($limit_userid);
          $to_address_org = $this->getUserEthAddress($uid);
          $AmtRelased_ETH = bcmul($start_volume , $close_price,8);
          $adminCommission_eth = $this->adminCommissionEth($AmtRelased_ETH);
// $insert_eth = array(
//   'fromaddr'    => $myClass->encrypt($from_address_org, $setkey, $iv),
//   'toaddr'    => $myClass->encrypt($to_address_org, $setkey, $iv),
//   'addminaddr'  => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//   'amount1'     => $AmtRelased_ETH,
//   'amount2'     => $adminCommission_eth,
//   'status'    => 0
// );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_eth);

          $sendedETH = new TranstableEth;
          $sendedETH->fromaddr = Crypt::encryptString($from_address_org);
          $sendedETH->toaddr = Crypt::encryptString($to_address_org);
          $sendedETH->addminaddr = Crypt::encryptString($admins_address_eth);
          $sendedETH->amount1 = $AmtRelased_ETH;
          $sendedETH->amount2 = $adminCommission_eth;
          $sendedETH->status = 0;
          $sendedETH->save();

          if($sended){  

            $updatedata = Buytrade::where('id', $cal_amount->id)->first();
            $updatedata->remaining = 0; 
            $updatedata->status = 1;
            $updatedata->save();
          }

          $updatedata = Selltrade::where('id', $insertid)->first();
          $updatedata->remaining =  $remaining; 
          $updatedata->status = 1;
          $updatedata->save();


        }
        $needed = $remaining;

      }
    }




  }

  function sellBtcLtcProcess($pair, $insertid, $amount, $volume)
  {
    $admins_addres = $this->btcAdminaddress();
    $admins_address = Crypt::decryptString($admins_addres->address);
    $admins_addres_ltc = $this->ltcAdminaddress();
    $admins_address_ltc = Crypt::decryptString($admins_addres_ltc->address);

// $trade = json_decode($myClass->dbSelectAll(SELL_TRADE_BTCETH." WHERE uid!=$uid  AND remaining!=0 AND (amount=$amount OR (stop_limit <= $amount AND amount >= $amount))",'ORDER BY amount ASC'));


    $trades = Buytrade::where([
      ['uid', \Auth::id()],
      ['remaining', '!=', 0],
      ['pair', $pair]                                        
    ])
    ->where('stop_limit', '<=',  $amount)
    ->where('price', '>=',  $amount) 
    ->orWhere('price', $amount)                    
    ->orderBy('price', 'ASC')->get();


    if($trades->count() > 0){         
      $recipients = array();
      foreach($trades as $cal_amount){
        $start_volume = $cal_amount->remaining;
        $start_volume = $start_volume;
        $close_price = $amount;
        $needed = $needed;
        if($start_volume >= $needed AND $needed!=0){
          $salesAmount = $needed;
          $salesBtc = bcmul($needed , $close_price, 8);         
// $insert = array(
//     'buy_id' => $cal_amount->id,
//     'sales_id' => $insertid,
//     'quantity' => $needed,
//     'amount' => $salesBtc,
//     'market_price'  => $close_price,
//     'created' => date('Y-m-d H:i:s', time())
//   );
// $insert = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $insert = new CompleteTrade;
          $insert->pair = $pair;
          $insert->buy_id = $cal_amount->id;
          $insert->sales_id = $insertid;
          $insert->quantity = $needed;
          $insert->amount = $salesBtc;
          $insert->market_price = $close_price;
          $insert->save();

          $close_remain = bcsub($cal_amount->remaining , $needed, 8);
          if($close_remain == 0)
          {

            $updatedata = Buytrade::where('id', $cal_amount->id)->first();
            $updatedata->remaining = $close_remain; 
            $updatedata->status = 1;
            $updatedata->save();

// $update1 = array('remaining' => $close_remain, 'status' => 1);
          } 
          else 
          {

            $updatedata = Buytrade::where('id', $cal_amount->id)->first();
            $updatedata->remaining = $close_remain; 
            $updatedata->save();
// $update1 = array('remaining' => $close_remain);
          }
// $where = array('id' => $cal_amount->id);
// $updatedata = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, $update1, $where);

// $limitcomplete = $myClass->dbRowUpdate(SELL_TRADE_BTCETH, array('remaining' => 0, 'status' => 1), array('id' => $insertid));

          $updatedata = Selltrade::where('id', $insertid)->first();
          $updatedata->remaining = 0; 
          $updatedata->status = 1;
          $updatedata->save();

          $limit_userid = $cal_amount->uid;
          $buyer_address_org = $this->getUserBtcAddress($limit_userid);
//BTC SEND
// $insert = array(
//     'fromaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($buyer_address_org, $setkey, $iv),
//     'addminaddr' => $myClass->encrypt($admins_address, $setkey, $iv),
//     'amount1' => $needed,
//     'amount2' => $adminCommission,
//     'status' => 0
//   );
// $sended = $myClass->dbInsert(TRANSTABLE_BTC, $insert);

          $sended = new TranstableLtc;
          $sended->fromaddr = Crypt::encryptString($user_address_btc);
          $sended->toaddr = Crypt::encryptString($buyer_address_org);
          $sended->addminaddr = Crypt::encryptString($admins_address);
          $sended->amount1 = $needed;
          $sended->amount2 = $adminCommission;
          $sended->status = 0;
          $sended->save();

//ETH SEND
//buyer address
          $from_address_org = $this->getUserLtcAddress($limit_userid);
          $to_address_org = $this->getUserLtcAddress($uid);
          $AmtRelased_LTC = bcmul($needed , $close_price,8);
          $adminCommission_ltc = $this->adminCommissionLtc($AmtRelased_LTC);
// $insert_eth = array(
//   'fromaddr'    => $myClass->encrypt($from_address_org, $setkey, $iv),
//   'toaddr'    => $myClass->encrypt($to_address_org, $setkey, $iv),
//   'addminaddr'  => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//   'amount1'     => $AmtRelased_ETH,
//   'amount2'     => $adminCommission_eth,
//   'status'    => 0
// );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_eth);

          $sendedLtc = new TranstableLtc;
          $sendedLtc->fromaddr = Crypt::encryptString($from_address_org);
          $sendedLtc->toaddr = Crypt::encryptString($to_address_org);
          $sendedLtc->addminaddr = Crypt::encryptString($admins_address_ltc);
          $sendedLtc->amount1 = $AmtRelased_LTC;
          $sendedLtc->amount2 = $adminCommission_ltc;
          $sendedLtc->status = 0;
          $sendedLtc->save();


          if($sended){

//Remaining Updated
            $remaining = bcsub($needed , $needed);
          }

        } else if($start_volume < $needed AND $needed!=0){
          $remaining = bcsub($needed , $start_volume, 8);
          $buyAmount = bcmul($cal_amount->remaining , $close_price, 8);
// $insert = array(
//   'buy_id'  => $cal_amount->id,
//   'sales_id'  => $insertid,
//   'quantity'  => $start_volume,
//   'amount'  => $buyAmount,
//   'market_price'  => $close_price,
//   'created'   => date('Y-m-d H:i:s', time())
// );
// $insert = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $insert = new CompleteTrade;
          $insert->pair = $pair;
          $insert->buy_id = $cal_amount->id;
          $insert->sales_id = $insertid;
          $insert->quantity = $start_volume;
          $insert->amount = $buyAmount;
          $insert->market_price = $close_price;
          $insert->save();


          $limit_userid     = $cal_amount->uid;
          $buyer_address_org  = $this->getUserBtcAddress($limit_userid);

// $insert = array(
//     'fromaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($buyer_address_org, $setkey, $iv),
//     'addminaddr' => '',
//     'amount1' => $start_volume,
//     'amount2' => 0,
//     'status' => 0
//   );
// $sended = $myClass->dbInsert(TRANSTABLE_BTC, $insert);

          $sended = new TranstableBtc;
          $sended->fromaddr = Crypt::encryptString($user_address_btc);
          $sended->toaddr = Crypt::encryptString($buyer_address_org);
          $sended->addminaddr = '';
          $sended->amount1 = $start_volume;
          $sended->amount2 = 0;
          $sended->status = 0;
          $sended->save();


//Ltc SEND
//buyer address
          $from_address_org = $this->getUserLtcAddress($limit_userid);
          $to_address_org = $this->getUserLtcAddress($uid);
          $AmtRelased_Ltc = bcmul($start_volume , $close_price,8);
          $adminCommission_ltc = $this->adminCommissionLtc($AmtRelased_Ltc);
// $insert_eth = array(
//   'fromaddr'    => $myClass->encrypt($from_address_org, $setkey, $iv),
//   'toaddr'    => $myClass->encrypt($to_address_org, $setkey, $iv),
//   'addminaddr'  => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//   'amount1'     => $AmtRelased_ETH,
//   'amount2'     => $adminCommission_eth,
//   'status'    => 0
// );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_eth);

          $sendedLtc = new TranstableLtc;
          $sendedLtc->fromaddr = Crypt::encryptString($from_address_org);
          $sendedLtc->toaddr = Crypt::encryptString($to_address_org);
          $sendedLtc->addminaddr = Crypt::encryptString($admins_address_ltc);
          $sendedLtc->amount1 = $AmtRelased_Ltc;
          $sendedLtc->amount2 = $adminCommission_ltc;
          $sendedLtc->status = 0;
          $sendedLtc->save();

          if($sended){  

            $updatedata = Buytrade::where('id', $cal_amount->id)->first();
            $updatedata->remaining = 0; 
            $updatedata->status = 1;
            $updatedata->save();
          }

          $updatedata = Selltrade::where('id', $insertid)->first();
          $updatedata->remaining =  $remaining; 
          $updatedata->status = 1;
          $updatedata->save();


        }
        $needed = $remaining;

      }
    }


  }

  function buyStopBtcEthProcess($pair, $insertid, $amount, $volume, $stop)
  {
    $needed   = $volume;
    $admins_addres = $this->btcAdminaddress();
    $admins_address = Crypt::decryptString($admins_addres->address);
    $admins_addres_eth = $this->ethAdminaddress();
    $admins_address_eth = Crypt::decryptString($admins_addres_eth->address);

    $trades = Selltrade::where([
      ['uid', \Auth::id()],
      ['remaining', '!=', 0],
      ['pair', $pair]                                        
    ])
    ->where('price', '<=',  $amount)
    ->where('price', '>',  $stop) 
    ->orderBy('price', 'DESC')->get();


// $trade = json_decode($myClass->dbSelectAll(SELL_TRADE_BTCETH." WHERE uid!=$uid  AND remaining!=0 AND (amount <= $amount AND amount >= $stop)",'ORDER BY amount DESC'));
    if($trades->count() > 0)
    {
      foreach($trades as $cal_amount){
        $start_volume = $cal_amount->remaining;
        $close_price = $cal_amount->amount;
        $needed = $needed;
        if($start_volume >= $needed AND $needed!=0)
        {
          $salesAmount = bcmul($needed , $cal_amount->amount,8);
// $insert = array(
//     'buy_id'  => $insertid,
//     'sales_id'  => $cal_amount->id,
//     'quantity'  => $needed,
//     'amount'  => $salesAmount,
//     'market_price'  => $close_price,
//     'created'   => date('Y-m-d H:i:s', time())
//   );
// $insert = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $insert = new CompleteTrade;
          $insert->pair = $pair;
          $insert->buy_id = $insertid;
          $insert->sales_id = $cal_amount->id;
          $insert->quantity = $needed;
          $insert->amount = $salesAmount;
          $insert->market_price = $close_price;                   
          $insert->save();

          $limit_btc    = $needed;
          $limit_userid   = $cal_amount->uid;

// update selltrade 
          $close_remain = bcsub($cal_amount->remaining ,$needed,8);
          $seller_address_org = $this->getUserBtcAddress($limit_userid);
          if($close_remain == 0)
          {
            $selltrade = Selltrade::where('id', $cal_amount->id)->first();
            $selltrade->remaining = $close_remain;
            $selltrade->status = 1;
            $selltrade->save();

            $adminCommission_btc = $this->adminCommissionBtc($cal_amount->volume);
            $totalBtc = bcadd(bcadd($adminCommission_btc , $limit_btc ,8), 0.0001,8);
//BTC SEND
//seller address 

// $insert = array(
//   'fromaddr' => $myClass->encrypt($seller_address_org, $setkey, $iv),
//   'toaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//   'addminaddr' => $myClass->encrypt($admins_address, $setkey, $iv),
//   'amount1' => $limit_btc,
//   'amount2' => $adminCommission_btc,
//   'status' => 0
// );
// $sended_BTC = $myClass->dbInsert(TRANSTABLE_BTC, $insert);

            $sendedBTC = new TranstableBtc;
            $sendedBTC->fromaddr = Crypt::encryptString($seller_address_org);
            $sendedBTC->toaddr = Crypt::encryptString($user_address_btc);
            $sendedBTC->addminaddr = Crypt::encryptString($admins_address);
            $sendedBTC->amount1 = $limit_btc;
            $sendedBTC->amount2 = $adminCommission_btc;
            $sendedBTC->status = 0;
            $sendedBTC->save();

//ETH SEND
//buyer address
            $from_address_org = $this->getUserEthAddress($uid);
            $to_address_org = $this->getUserEthAddress($limit_userid);
            $AmtRelased_ETH = bcmul($cal_amount->volume , $cal_amount->amount,8);
            $adminCommission_eth = $tradeCoin->adminCommission_eth($AmtRelased_ETH);
// $insert_eth = array(
//   'fromaddr' => $myClass->encrypt($from_address_org, $setkey, $iv),
//   'toaddr' => $myClass->encrypt($to_address_org, $setkey, $iv),
//   'addminaddr' => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//   'amount1' => $AmtRelased_ETH,
//   'amount2' => $adminCommission_eth,
//   'status' => 0
// );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_eth);

            $sendedEth = new TranstableEth;
            $sendedEth->fromaddr = Crypt::encryptString($from_address_org);
            $sendedEth->toaddr = Crypt::encryptString($to_address_org);
            $sendedEth->addminaddr = Crypt::encryptString($admins_address_eth);
            $sendedEth->amount1 = $AmtRelased_ETH;
            $sendedEth->amount2 = $adminCommission_eth;
            $sendedEth->status = 0;
            $sendedEth->save();
          }
          else 
          {

            $selltrade = Selltrade::where('id', $cal_amount->id)->first();
            $selltrade->remaining = $close_remain;
            $selltrade->save();

            $totalBtc = bcadd($limit_btc , 0.0001,8);
//$myClass->userbalanceupdatebtc($limit_userid,$totalBtc,'debit');
//BTC SEND
// $insert = array(
//   'fromaddr' => $myClass->encrypt($seller_address_org, $setkey, $iv),
//   'toaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//   'addminaddr' => '',
//   'amount1' => $limit_btc,
//   'amount2' => 0,
//   'status' => 0
// );
// $sended_BTC = $myClass->dbInsert(TRANSTABLE_BTC, $insert);

            $sendedBtc = new TranstableBtc;
            $sendedBtc->fromaddr = Crypt::encryptString($seller_address_org);
            $sendedBtc->toaddr = Crypt::encryptString($user_address_btc);
            $sendedBtc->addminaddr = '';
            $sendedBtc->amount1 = $limit_btc;
            $sendedBtc->amount2 = 0;
            $sendedBtc->status = 0;
            $sendedBtc->save();

//ETH SEND
            $from_address_org = $this->getUserEthAddress($uid);
            $to_address_org = $tradeCoin->getUserEthAddress($limit_userid);
            $AmtRelased_ETH = bcmul($limit_btc , $cal_amount->amount,8);
// $insert = array(
//   'fromaddr' => $myClass->encrypt($from_address_org, $setkey, $iv),
//   'toaddr' => $myClass->encrypt($to_address_org, $setkey, $iv),
//   'addminaddr' => '',
//   'amount1' => $AmtRelased_ETH,
//   'amount2' => 0,
//   'status' => 0
// );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert);

            $sendedEth = new TranstableBtc;
            $sendedEth->fromaddr = Crypt::encryptString($from_address_org);
            $sendedEth->toaddr = Crypt::encryptString($to_address_org);
            $sendedEth->addminaddr = '';
            $sendedEth->amount1 = $AmtRelased_ETH;
            $sendedEth->amount2 = 0;
            $sendedEth->status = 0;
            $sendedEth->save();


          }

          $limitcomplete = Buytrade:: where('id', $insertid)->first();
          $limitcomplete->remaining = 0;
          $limitcomplete->status = 1;
          $limitcomplete->save();

// $limitcomplete = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, array('remaining' => 0, 'status' => 1), array('id' => $insertid));

          $remaining  = $needed - $needed;
        }
        else if($start_volume < $needed AND $needed!=0) 
        {
          $remaining = bcsub($needed , $start_volume, 8);
          $buyAmount = bcmul($cal_amount->remaining , $cal_amount->amount,8);

// $insert = array(
//   'buy_id'  => $insertid,
//   'sales_id'  => $cal_amount->id,
//   'quantity'  => $cal_amount->remaining,
//   'amount'  => $buyAmount,
//   'market_price'  => $close_price,
//   'created'   => date('Y-m-d H:i:s', time())
// );
// $insert     = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $insert = new CompleteTrade;
          $insert->buy_id = $insertid;
          $insert->sales_id = $cal_amount->id;
          $insert->quantity = $cal_amount->remaining;
          $insert->amount = $buyAmount;
          $insert->market_price = $close_price;
          $insert->save();


// $update     = array('remaining' => 0, 'status' => 1);
// $where      = array('id' => $cal_amount->id); 
// $updatedata   = $myClass->dbRowUpdate(SELL_TRADE_BTCETH, $update, $where);

          $sellTrade = Selltrade::where('id', $cal_amount->id)->first();
          $sellTrade->remaining = 0; 
          $sellTrade->status = 0;
          $sellTrade->save();

// $limitcomplete = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, array('remaining' => $remaining), array('id' => $insertid));


          $limitcomplete = Buytrade::where('id', $insertid)->first();
          $limitcomplete->remaining = $remaining; 
          $limitcomplete->save();


          $limit_userid   = $cal_amount->uid;
          $limit_btc    = $cal_amount->remaining;
          $seller_address_org = $this->getUserBtcAddress($limit_userid);
          $adminCommission = $this->adminCommissionBtc($cal_amount->volume);
          $totalBtc = bcadd(bcadd($adminCommission , $limit_btc, 8) , 0.0001, 8);
//$myClass->userbalanceupdatebtc($limit_userid,$totalBtc,'debit');
//BTC SEND
// $insert_BTC = array(
//     'fromaddr' => $myClass->encrypt($seller_address_org, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//     'addminaddr' => $myClass->encrypt($admins_address, $setkey, $iv),
//     'amount1' => $limit_btc,
//     'amount2' => $adminCommission,
//     'status' => 0
//   );
// $sended_BTC = $myClass->dbInsert(TRANSTABLE_BTC, $insert_BTC);

          $sendedBtc = new TranstableBtc;
          $sendedBtc->fromaddr = Crypt::encryptString($seller_address_org);
          $sendedBtc->toaddr = Crypt::encryptString($user_address_btc);
          $sendedBtc->addminaddr = Crypt::encryptString($admins_address);
          $sendedBtc->amount1 = $limit_btc;
          $sendedBtc->amount2 = $adminCommission;
          $sendedBtc->status = 0;
          $sendedBtc->save();

//ETH SEND
          $from_address_org = $this->getUserEthAddress($uid);
          $to_address_org = $this->getUserEthAddress($limit_userid);
          $AmtRelased_ETH = bcmul($limit_btc , $cal_amount->amount,8);
          $adminCommission_eth = $this->adminCommissionEth($AmtRelased_ETH);

// $insert_ETH = array(
//     'fromaddr' => $myClass->encrypt($from_address_org, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($to_address_org, $setkey, $iv),
//     'addminaddr' => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//     'amount1' => $AmtRelased_ETH,
//     'amount2' => $adminCommission_eth,
//     'status' => 0
//   );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_ETH);

          $sendedEth = new TranstableBtc;
          $sendedEth->fromaddr = Crypt::encryptString($from_address_org);
          $sendedEth->toaddr = Crypt::encryptString($to_address_org);
          $sendedEth->addminaddr = Crypt::encryptString($admins_address_eth);
          $sendedEth->amount1 = $AmtRelased_ETH;
          $sendedEth->amount2 = $adminCommission_eth;
          $sendedEth->status = 0;
          $sendedEth->save();

//$remaining  = $needed - $cal_amount->remaining;
        }
        $needed = $remaining;
        if($needed == 0){
          break;
        } 
      }

    }           

  }


  function buyStopBtcLtcProcess($pair, $insertid, $amount, $volume, $stop)
  {
    $needed   = $volume;
    $admins_addres = $this->btcAdminaddress();
    $admins_address = Crypt::decryptString($admins_addres->address);
    $admins_addres_ltc = $this->ltcAdminaddress();
    $admins_address_ltc = Crypt::decryptString($admins_addres_ltc->address);

    $trades = Selltrade::where([
      ['uid', \Auth::id()],
      ['remaining', '!=', 0],
      ['pair', $pair]                                        
    ])
    ->where('price', '<=',  $amount)
    ->where('price', '>',  $stop) 
    ->orderBy('price', 'DESC')->get();


// $trade = json_decode($myClass->dbSelectAll(SELL_TRADE_BTCETH." WHERE uid!=$uid  AND remaining!=0 AND (amount <= $amount AND amount >= $stop)",'ORDER BY amount DESC'));
    if($trades->count() > 0)
    {
      foreach($trades as $cal_amount){
        $start_volume = $cal_amount->remaining;
        $close_price = $cal_amount->amount;
        $needed = $needed;
        if($start_volume >= $needed AND $needed!=0)
        {
          $salesAmount = bcmul($needed , $cal_amount->amount,8);
// $insert = array(
//     'buy_id'  => $insertid,
//     'sales_id'  => $cal_amount->id,
//     'quantity'  => $needed,
//     'amount'  => $salesAmount,
//     'market_price'  => $close_price,
//     'created'   => date('Y-m-d H:i:s', time())
//   );
// $insert = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $insert = new CompleteTrade;
          $insert->pair = $pair;
          $insert->buy_id = $insertid;
          $insert->sales_id = $cal_amount->id;
          $insert->quantity = $needed;
          $insert->amount = $salesAmount;
          $insert->market_price = $close_price;                   
          $insert->save();

          $limit_btc    = $needed;
          $limit_userid   = $cal_amount->uid;

// update selltrade 
          $close_remain = bcsub($cal_amount->remaining ,$needed,8);
          $seller_address_org = $this->getUserBtcAddress($limit_userid);
          if($close_remain == 0)
          {
            $selltrade = Selltrade::where('id', $cal_amount->id)->first();
            $selltrade->remaining = $close_remain;
            $selltrade->status = 1;
            $selltrade->save();

            $adminCommission_btc = $this->adminCommissionBtc($cal_amount->volume);
            $totalBtc = bcadd(bcadd($adminCommission_btc , $limit_btc ,8), 0.0001,8);
//BTC SEND
//seller address 

// $insert = array(
//   'fromaddr' => $myClass->encrypt($seller_address_org, $setkey, $iv),
//   'toaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//   'addminaddr' => $myClass->encrypt($admins_address, $setkey, $iv),
//   'amount1' => $limit_btc,
//   'amount2' => $adminCommission_btc,
//   'status' => 0
// );
// $sended_BTC = $myClass->dbInsert(TRANSTABLE_BTC, $insert);

            $sendedBTC = new TranstableBtc;
            $sendedBTC->fromaddr = Crypt::encryptString($seller_address_org);
            $sendedBTC->toaddr = Crypt::encryptString($user_address_btc);
            $sendedBTC->addminaddr = Crypt::encryptString($admins_address);
            $sendedBTC->amount1 = $limit_btc;
            $sendedBTC->amount2 = $adminCommission_btc;
            $sendedBTC->status = 0;
            $sendedBTC->save();

//LTC SEND
//buyer address
            $from_address_org = $this->getUserLtcAddress($uid);
            $to_address_org = $this->getUserLtcAddress($limit_userid);
            $AmtRelased_LTC = bcmul($cal_amount->volume , $cal_amount->amount,8);
            $adminCommission_ltc = $this->adminCommissionLtc($AmtRelased_LTC);
// $insert_eth = array(
//   'fromaddr' => $myClass->encrypt($from_address_org, $setkey, $iv),
//   'toaddr' => $myClass->encrypt($to_address_org, $setkey, $iv),
//   'addminaddr' => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//   'amount1' => $AmtRelased_ETH,
//   'amount2' => $adminCommission_eth,
//   'status' => 0
// );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_eth);

            $sendedLtc = new TranstableLtc;
            $sendedLtc->fromaddr = Crypt::encryptString($from_address_org);
            $sendedLtc->toaddr = Crypt::encryptString($to_address_org);
            $sendedLtc->addminaddr = Crypt::encryptString($admins_address_ltc);
            $sendedLtc->amount1 = $AmtRelased_LTC;
            $sendedLtc->amount2 = $adminCommission_ltc;
            $sendedLtc->status = 0;
            $sendedLtc->save();
          }
          else 
          {

            $selltrade = Selltrade::where('id', $cal_amount->id)->first();
            $selltrade->remaining = $close_remain;
            $selltrade->save();

            $totalBtc = bcadd($limit_btc , 0.0001,8);
//$myClass->userbalanceupdatebtc($limit_userid,$totalBtc,'debit');
//BTC SEND
// $insert = array(
//   'fromaddr' => $myClass->encrypt($seller_address_org, $setkey, $iv),
//   'toaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//   'addminaddr' => '',
//   'amount1' => $limit_btc,
//   'amount2' => 0,
//   'status' => 0
// );
// $sended_BTC = $myClass->dbInsert(TRANSTABLE_BTC, $insert);

            $sendedBtc = new TranstableBtc;
            $sendedBtc->fromaddr = Crypt::encryptString($seller_address_org);
            $sendedBtc->toaddr = Crypt::encryptString($user_address_btc);
            $sendedBtc->addminaddr = '';
            $sendedBtc->amount1 = $limit_btc;
            $sendedBtc->amount2 = 0;
            $sendedBtc->status = 0;
            $sendedBtc->save();

//LTC SEND
            $from_address_org = $this->getUserLtcAddress($uid);
            $to_address_org = $tradeCoin->getUserLtcAddress($limit_userid);
            $AmtRelased_LTC = bcmul($limit_btc , $cal_amount->amount,8);
// $insert = array(
//   'fromaddr' => $myClass->encrypt($from_address_org, $setkey, $iv),
//   'toaddr' => $myClass->encrypt($to_address_org, $setkey, $iv),
//   'addminaddr' => '',
//   'amount1' => $AmtRelased_ETH,
//   'amount2' => 0,
//   'status' => 0
// );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert);

            $sendedLtc = new TranstableBtc;
            $sendedLtc->fromaddr = Crypt::encryptString($from_address_org);
            $sendedLtc->toaddr = Crypt::encryptString($to_address_org);
            $sendedLtc->addminaddr = '';
            $sendedLtc->amount1 = $AmtRelased_LTC;
            $sendedLtc->amount2 = 0;
            $sendedLtc->status = 0;
            $sendedLtc->save();


          }

          $limitcomplete = Buytrade:: where('id', $insertid)->first();
          $limitcomplete->remaining = 0;
          $limitcomplete->status = 1;
          $limitcomplete->save();

// $limitcomplete = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, array('remaining' => 0, 'status' => 1), array('id' => $insertid));

          $remaining  = $needed - $needed;
        }
        else if($start_volume < $needed AND $needed!=0) 
        {
          $remaining = bcsub($needed , $start_volume, 8);
          $buyAmount = bcmul($cal_amount->remaining , $cal_amount->amount,8);

// $insert = array(
//   'buy_id'  => $insertid,
//   'sales_id'  => $cal_amount->id,
//   'quantity'  => $cal_amount->remaining,
//   'amount'  => $buyAmount,
//   'market_price'  => $close_price,
//   'created'   => date('Y-m-d H:i:s', time())
// );
// $insert     = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $insert = new CompleteTrade;
          $insert->buy_id = $insertid;
          $insert->sales_id = $cal_amount->id;
          $insert->quantity = $cal_amount->remaining;
          $insert->amount = $buyAmount;
          $insert->market_price = $close_price;
          $insert->save();


// $update     = array('remaining' => 0, 'status' => 1);
// $where      = array('id' => $cal_amount->id); 
// $updatedata   = $myClass->dbRowUpdate(SELL_TRADE_BTCETH, $update, $where);

          $sellTrade = Selltrade::where('id', $cal_amount->id)->first();
          $sellTrade->remaining = 0; 
          $sellTrade->status = 0;
          $sellTrade->save();

// $limitcomplete = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, array('remaining' => $remaining), array('id' => $insertid));


          $limitcomplete = Buytrade::where('id', $insertid)->first();
          $limitcomplete->remaining = $remaining; 
          $limitcomplete->save();


          $limit_userid   = $cal_amount->uid;
          $limit_btc    = $cal_amount->remaining;
          $seller_address_org = $this->getUserBtcAddress($limit_userid);
          $adminCommission = $this->adminCommissionBtc($cal_amount->volume);
          $totalBtc = bcadd(bcadd($adminCommission , $limit_btc, 8) , 0.0001, 8);
//$myClass->userbalanceupdatebtc($limit_userid,$totalBtc,'debit');
//BTC SEND
// $insert_BTC = array(
//     'fromaddr' => $myClass->encrypt($seller_address_org, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//     'addminaddr' => $myClass->encrypt($admins_address, $setkey, $iv),
//     'amount1' => $limit_btc,
//     'amount2' => $adminCommission,
//     'status' => 0
//   );
// $sended_BTC = $myClass->dbInsert(TRANSTABLE_BTC, $insert_BTC);

          $sendedBtc = new TranstableBtc;
          $sendedBtc->fromaddr = Crypt::encryptString($seller_address_org);
          $sendedBtc->toaddr = Crypt::encryptString($user_address_btc);
          $sendedBtc->addminaddr = Crypt::encryptString($admins_address);
          $sendedBtc->amount1 = $limit_btc;
          $sendedBtc->amount2 = $adminCommission;
          $sendedBtc->status = 0;
          $sendedBtc->save();

//ETH SEND
          $from_address_org = $this->getUserLtcAddress($uid);
          $to_address_org = $this->getUserLtcAddress($limit_userid);
          $AmtRelased_LTC = bcmul($limit_btc , $cal_amount->amount,8);
          $adminCommission_ltc = $this->adminCommissionLtc($AmtRelased_LTC);

// $insert_ETH = array(
//     'fromaddr' => $myClass->encrypt($from_address_org, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($to_address_org, $setkey, $iv),
//     'addminaddr' => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//     'amount1' => $AmtRelased_ETH,
//     'amount2' => $adminCommission_eth,
//     'status' => 0
//   );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_ETH);

          $sendedLtc = new TranstableBtc;
          $sendedLtc->fromaddr = Crypt::encryptString($from_address_org);
          $sendedLtc->toaddr = Crypt::encryptString($to_address_org);
          $sendedLtc->addminaddr = Crypt::encryptString($admins_address_ltc);
          $sendedLtc->amount1 = $AmtRelased_LTC;
          $sendedLtc->amount2 = $adminCommission_ltc;
          $sendedLtc->status = 0;
          $sendedLtc->save();

//$remaining  = $needed - $cal_amount->remaining;
        }
        $needed = $remaining;
        if($needed == 0){
          break;
        } 
      }

    }            

  }

  function sellStopBtcEthProcess($pair, $insertid, $amount, $volume, $stop)
  {

    $needed   = $volume;
    $admins_addres = $this->btcAdminaddress();
    $admins_address = Crypt::decryptString($admins_addres->address);
    $admins_addres_eth = $this->ethAdminaddress();
    $admins_address_eth = Crypt::decryptString($admins_addres_eth->address);
// $trade = json_decode($myClass->dbSelectAll(BUY_TRADE_BTCETH." WHERE uid!=$uid AND remaining!=0 AND amount <= $amount AND stop_limit >= $amount",'ORDER BY id ASC'));


    $trades = Buytrade::where([
      ['uid', \Auth::id()],
      ['remaining', '!=', 0],
      ['pair', $pair]                                        
    ])
    ->where('price', '<=',  $amount)
    ->where('stop_limit', '>=',  $amount) 
    ->orderBy('price', 'ASC')->get();

    if($trades->count() > 0){         
      $recipients = array();
      foreach($trades as $cal_amount){
        $start_volume = $cal_amount->remaining;
        $close_price = $cal_amount->amount;
        $start_volume = $start_volume;
        $needed = $needed;
        if($start_volume >= $needed AND $needed!=0){
          $salesAmount = $needed;
          $salesBtc = bcmul($needed , $close_price, 8);         
// $insert = array(
//     'buy_id' => $cal_amount->id,
//     'sales_id' => $insertid,
//     'quantity' => $needed,
//     'amount' => $salesBtc,
//     'market_price'  => $close_price,
//     'created' => date('Y-m-d H:i:s', time())
//   );
// $insert = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $completeTrade = new CompleteTrade;
          $completeTrade->pair = $pair; 
          $completeTrade->buy_id = $cal_amount->id; 
          $completeTrade->sales_id = $insertid;
          $completeTrade->quantity = $needed;
          $completeTrade->amount = $salesBtc;
          $completeTrade->market_price = $close_price;
          $completeTrade->save();

          $close_remain = bcsub($cal_amount->remaining , $needed, 8);
          if($close_remain == 0){

// $update1 = array('remaining' => $close_remain, 'status' => 1);
            $buyTrade = Buytrade::where('id', $cal_amount->id)->first();
            $buyTrade->remaining = $close_remain;
            $buyTrade->status = 1;
            $buyTrade->save();
          } else {
// $update1 = array('remaining' => $close_remain);

            $buyTrade = Buytrade::where('id', $cal_amount->id)->first();
            $buyTrade->remaining = $close_remain;
            $buyTrade->save();
          }
// $where = array('id' => $cal_amount->id);
// $updatedata = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, $update1, $where);
// $limitcomplete = $myClass->dbRowUpdate(SELL_TRADE_BTCETH, array('remaining' => 0, 'status' => 1), array('id' => $insertid));

          $limitcomplete = Selltrade::where('id', $insertid)->first();
          $limitcomplete->remaining = 0;
          $limitcomplete->status = 1;
          $limitcomplete->save();

          $limit_userid = $cal_amount->uid;
          $buyer_address_org = $this->getUserBtcAddress($limit_userid);
//BTC SEND
// $insert = array(
//     'fromaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($buyer_address_org, $setkey, $iv),
//     'addminaddr' => $myClass->encrypt($admins_address, $setkey, $iv),
//     'amount1' => $needed,
//     'amount2' => $adminCommission,
//     'status' => 0
//   );
// $sended = $myClass->dbInsert(TRANSTABLE_BTC, $insert);

          $sended = new TranstableBtc;
          $sended->fromaddr = Crypt::encryptString($user_address_btc);
          $sended->toaddr = Crypt::encryptString($buyer_address_org);
          $sended->addminaddr = Crypt::encryptString($admins_address);
          $sended->amount1 = $needed;
          $sended->amount2 = $adminCommission;
          $sended->status = 0;
          $sended->save();

//ETH SEND
//buyer address
          $from_address_org = $this->getUserEthAddress($limit_userid);
          $to_address_org = $this->getUserEthAddress($uid);
          $AmtRelased_ETH = bcmul($needed , $close_price,8);
          $adminCommission_eth = $this->adminCommissionEth($AmtRelased_ETH);
// $insert_eth = array(
//   'fromaddr'    => $myClass->encrypt($from_address_org, $setkey, $iv),
//   'toaddr'    => $myClass->encrypt($to_address_org, $setkey, $iv),
//   'addminaddr'  => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//   'amount1'     => $AmtRelased_ETH,
//   'amount2'     => $adminCommission_eth,
//   'status'    => 0
// );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_eth);

          $sendedETH = new TranstableEth;
          $sendedETH->fromaddr = Crypt::encryptString($from_address_org);
          $sendedETH->toaddr = Crypt::encryptString($to_address_org);
          $sendedETH->addminaddr = Crypt::encryptString($admins_address_eth);
          $sendedETH->amount1 = $AmtRelased_ETH;
          $sendedETH->amount2 = $adminCommission_eth;
          $sendedETH->status = 0;
          $sendedETH->save();

          if($sended){

//Remaining Updated
            $remaining = bcsub($needed , $needed);
          }

        } else if($start_volume < $needed AND $needed!=0){
          $remaining = bcsub($needed , $start_volume, 8);
          $buyAmount = bcmul($cal_amount->remaining , $close_price, 8);
// $insert = array(
//   'buy_id'  => $cal_amount->id,
//   'sales_id'  => $insertid,
//   'quantity'  => $start_volume,
//   'amount'  => $buyAmount,
//   'market_price'  => $close_price,
//   'created'   => date('Y-m-d H:i:s', time())
// );
// $insert = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $completeTrade = new CompleteTrade;
          $completeTrade->pair = $pair; 
          $completeTrade->buy_id = $cal_amount->id; 
          $completeTrade->sales_id = $insertid;
          $completeTrade->quantity = $start_volume;
          $completeTrade->amount = $buyAmount;
          $completeTrade->market_price = $close_price;
          $completeTrade->save();

          $limit_userid     = $cal_amount->uid;
          $buyer_address_org  = $this->getUserBtcAddress($limit_userid);

// $insert = array(
//     'fromaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($buyer_address_org, $setkey, $iv),
//     'addminaddr' => '',
//     'amount1' => $start_volume,
//     'amount2' => 0,
//     'status' => 0
//   );
// $sended = $myClass->dbInsert(TRANSTABLE_BTC, $insert);


          $sended = new TranstableBtc;
          $sended->fromaddr = Crypt::encryptString($user_address_btc);
          $sended->toaddr = Crypt::encryptString($buyer_address_org);
          $sended->addminaddr = '';
          $sended->amount1 = $start_volume;
          $sended->amount2 = 0;
          $sended->status = 0;
          $sended->save();


//ETH SEND
//buyer address
          $from_address_org = $this->getUserEthAddress($limit_userid);
          $to_address_org = $this->getUserEthAddress($uid);
          $AmtRelased_ETH = bcmul($start_volume , $close_price,8);
          $adminCommission_eth = $this->adminCommissionEth($AmtRelased_ETH);

// $insert_eth = array(
//   'fromaddr'    => $myClass->encrypt($from_address_org, $setkey, $iv),
//   'toaddr'    => $myClass->encrypt($to_address_org, $setkey, $iv),
//   'addminaddr'  => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//   'amount1'     => $AmtRelased_ETH,
//   'amount2'     => $adminCommission_eth,
//   'status'    => 0
// );

// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_eth);

          $sendedEth = new TranstableEth;
          $sendedEth->fromaddr = Crypt::encryptString($from_address_org);
          $sendedEth->toaddr = Crypt::encryptString($to_address_org);
          $sendedEth->addminaddr = Crypt::encryptString($admins_address_eth);
          $sendedEth->amount1 = $AmtRelased_ETH;
          $sendedEth->amount2 = $adminCommission_eth;
          $sendedEth->status = 0;
          $sendedEth->save();

          if($sended){                        
// $update = array('remaining' => 0, 'status' => 1);
// $where = array('id' => $cal_amount->id);
// $updatedata = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, $update, $where);

            $buyTrade = Buytrade::where('id', $cal_amount->id)->first();
            $buyTrade->remaining = 0;
            $buyTrade->status = 1;
            $buyTrade->save();
          }
// $limitcomplete = $myClass->dbRowUpdate(SELL_TRADE_BTCETH, array('remaining' => $remaining), array('id' => $insertid));

          $selltrade = Selltrade::where('id', $insertid)->first();
          $selltrade->remaining = $remaining;
          $selltrade->save();
        }
        $needed = $remaining;

      }
    }


  }

  function sellStopBtcLtcProcess($pair, $insertid, $amount, $volume, $stop)
  {
    $needed   = $volume;
    $admins_addres = $this->btcAdminaddress();
    $admins_address = Crypt::decryptString($admins_addres->address);
    $admins_addres_ltc = $this->ltcAdminaddress();
    $admins_address_ltc = Crypt::decryptString($admins_addres_ltc->address);
// $trade = json_decode($myClass->dbSelectAll(BUY_TRADE_BTCETH." WHERE uid!=$uid AND remaining!=0 AND amount <= $amount AND stop_limit >= $amount",'ORDER BY id ASC'));


    $trades = Buytrade::where([
      ['uid', \Auth::id()],
      ['remaining', '!=', 0],
      ['pair', $pair]                                        
    ])
    ->where('price', '<=', $amount)
    ->where('stop_limit', '>=', $amount) 
    ->orderBy('price', 'ASC')->get();


    if($trades->count() > 0){         
      $recipients = array();
      foreach($trades as $cal_amount){
        $start_volume = $cal_amount->remaining;
        $close_price = $cal_amount->amount;
        $start_volume = $start_volume;
        $needed = $needed;
        if($start_volume >= $needed AND $needed!=0){
          $salesAmount = $needed;
          $salesBtc = bcmul($needed , $close_price, 8);         
// $insert = array(
//     'buy_id' => $cal_amount->id,
//     'sales_id' => $insertid,
//     'quantity' => $needed,
//     'amount' => $salesBtc,
//     'market_price'  => $close_price,
//     'created' => date('Y-m-d H:i:s', time())
//   );
// $insert = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $completeTrade = new CompleteTrade;
          $completeTrade->pair = $pair; 
          $completeTrade->buy_id = $cal_amount->id; 
          $completeTrade->sales_id = $insertid;
          $completeTrade->quantity = $needed;
          $completeTrade->amount = $salesBtc;
          $completeTrade->market_price = $close_price;
          $completeTrade->save();

          $close_remain = bcsub($cal_amount->remaining , $needed, 8);
          if($close_remain == 0){

// $update1 = array('remaining' => $close_remain, 'status' => 1);
            $buyTrade = Buytrade::where('id', $cal_amount->id)->first();
            $buyTrade->remaining = $close_remain;
            $buyTrade->status = 1;
            $buyTrade->save();
          } else {
// $update1 = array('remaining' => $close_remain);

            $buyTrade = Buytrade::where('id', $cal_amount->id)->first();
            $buyTrade->remaining = $close_remain;
            $buyTrade->save();
          }
// $where = array('id' => $cal_amount->id);
// $updatedata = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, $update1, $where);
// $limitcomplete = $myClass->dbRowUpdate(SELL_TRADE_BTCETH, array('remaining' => 0, 'status' => 1), array('id' => $insertid));

          $limitcomplete = Selltrade::where('id', $insertid)->first();
          $limitcomplete->remaining = 0;
          $limitcomplete->status = 1;
          $limitcomplete->save();

          $limit_userid = $cal_amount->uid;
          $buyer_address_org = $this->getUserBtcAddress($limit_userid);
//BTC SEND
// $insert = array(
//     'fromaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($buyer_address_org, $setkey, $iv),
//     'addminaddr' => $myClass->encrypt($admins_address, $setkey, $iv),
//     'amount1' => $needed,
//     'amount2' => $adminCommission,
//     'status' => 0
//   );
// $sended = $myClass->dbInsert(TRANSTABLE_BTC, $insert);

          $sended = new TranstableBtc;
          $sended->fromaddr = Crypt::encryptString($user_address_btc);
          $sended->toaddr = Crypt::encryptString($buyer_address_org);
          $sended->addminaddr = Crypt::encryptString($admins_address);
          $sended->amount1 = $needed;
          $sended->amount2 = $adminCommission;
          $sended->status = 0;
          $sended->save();

//ETH SEND
//buyer address
          $from_address_org = $this->getUserLtcAddress($limit_userid);
          $to_address_org = $this->getUserLtcAddress($uid);
          $AmtRelased_LTC = bcmul($needed , $close_price,8);
          $adminCommission_ltc = $this->adminCommissionLtc($AmtRelased_LTC);
// $insert_eth = array(
//   'fromaddr'    => $myClass->encrypt($from_address_org, $setkey, $iv),
//   'toaddr'    => $myClass->encrypt($to_address_org, $setkey, $iv),
//   'addminaddr'  => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//   'amount1'     => $AmtRelased_ETH,
//   'amount2'     => $adminCommission_eth,
//   'status'    => 0
// );
// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_eth);

          $sendedETH = new TranstableEth;
          $sendedETH->fromaddr = Crypt::encryptString($from_address_org);
          $sendedETH->toaddr = Crypt::encryptString($to_address_org);
          $sendedETH->addminaddr = Crypt::encryptString($admins_address_ltc);
          $sendedETH->amount1 = $AmtRelased_LTC;
          $sendedETH->amount2 = $adminCommission_ltc;
          $sendedETH->status = 0;
          $sendedETH->save();

          if($sended){

//Remaining Updated
            $remaining = bcsub($needed , $needed);
          }

        } else if($start_volume < $needed AND $needed!=0){
          $remaining = bcsub($needed , $start_volume, 8);
          $buyAmount = bcmul($cal_amount->remaining , $close_price, 8);
// $insert = array(
//   'buy_id'  => $cal_amount->id,
//   'sales_id'  => $insertid,
//   'quantity'  => $start_volume,
//   'amount'  => $buyAmount,
//   'market_price'  => $close_price,
//   'created'   => date('Y-m-d H:i:s', time())
// );
// $insert = $myClass->dbInsert(COMPLETE_TRADE_BTCETH, $insert);

          $completeTrade = new CompleteTrade;
          $completeTrade->pair = $pair; 
          $completeTrade->buy_id = $cal_amount->id; 
          $completeTrade->sales_id = $insertid;
          $completeTrade->quantity = $start_volume;
          $completeTrade->amount = $buyAmount;
          $completeTrade->market_price = $close_price;
          $completeTrade->save();

          $limit_userid     = $cal_amount->uid;
          $buyer_address_org  = $this->getUserBtcAddress($limit_userid);

// $insert = array(
//     'fromaddr' => $myClass->encrypt($user_address_btc, $setkey, $iv),
//     'toaddr' => $myClass->encrypt($buyer_address_org, $setkey, $iv),
//     'addminaddr' => '',
//     'amount1' => $start_volume,
//     'amount2' => 0,
//     'status' => 0
//   );
// $sended = $myClass->dbInsert(TRANSTABLE_BTC, $insert);

          $sended = new TranstableBtc;
          $sended->fromaddr = Crypt::encryptString($user_address_btc);
          $sended->toaddr = Crypt::encryptString($buyer_address_org);
          $sended->addminaddr = '';
          $sended->amount1 = $start_volume;
          $sended->amount2 = 0;
          $sended->status = 0;
          $sended->save();


//LTC SEND
//buyer address
          $from_address_org = $this->getUserLtcAddress($limit_userid);
          $to_address_org = $this->getUserLtcAddress($uid);
          $AmtRelased_LTC = bcmul($start_volume , $close_price,8);
          $adminCommission_ltc = $this->adminCommissionEth($AmtRelased_LTC);

// $insert_eth = array(
//   'fromaddr'    => $myClass->encrypt($from_address_org, $setkey, $iv),
//   'toaddr'    => $myClass->encrypt($to_address_org, $setkey, $iv),
//   'addminaddr'  => $myClass->encrypt($admins_address_eth, $setkey, $iv),
//   'amount1'     => $AmtRelased_ETH,
//   'amount2'     => $adminCommission_eth,
//   'status'    => 0
// );

// $sended_ETH = $myClass->dbInsert(TRANSTABLE_ETH, $insert_eth);

          $sendedEth = new TranstableEth;
          $sendedEth->fromaddr = Crypt::encryptString($from_address_org);
          $sendedEth->toaddr = Crypt::encryptString($to_address_org);
          $sendedEth->addminaddr = Crypt::encryptString($admins_address_ltc);
          $sendedEth->amount1 = $AmtRelased_LTC;
          $sendedEth->amount2 = $adminCommission_ltc;
          $sendedEth->status = 0;
          $sendedEth->save();

          if($sended){                        
// $update = array('remaining' => 0, 'status' => 1);
// $where = array('id' => $cal_amount->id);
// $updatedata = $myClass->dbRowUpdate(BUY_TRADE_BTCETH, $update, $where);

            $buyTrade = Buytrade::where('id', $cal_amount->id)->first();
            $buyTrade->remaining = 0;
            $buyTrade->status = 1;
            $buyTrade->save();
          }
// $limitcomplete = $myClass->dbRowUpdate(SELL_TRADE_BTCETH, array('remaining' => $remaining), array('id' => $insertid));

          $selltrade = Selltrade::where('id', $insertid)->first();
          $selltrade->remaining = $remaining;
          $selltrade->save();
        }
        $needed = $remaining;

      }
    }


  }

  function filter($string) 
  {
    $val = htmlspecialchars(trim(strip_tags(addslashes($string))),ENT_QUOTES);
    return $val;
  }

  public function crexcUrls($currency)
  {
    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

//https://api.crex24.com/v2/account/balance
    $path = '/v2/account/balance?currency='.$currency.'&nonZeroOnly=false';
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

// echo $responseBody;
    return json_decode($responseBody);

  }


  public function crexorderstatuscUrls($currency)
  {

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

//https://api.crex24.com/v2/account/balance
    $path = '/v2/account/balance?currency='.$currency.'&nonZeroOnly=false';
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

// echo $responseBody;

    return json_decode($responseBody);

  }

}