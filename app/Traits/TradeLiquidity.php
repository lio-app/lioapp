<?php
namespace App\Traits;
use App\TradeChart;
use App\Buytrade;
use App\Selltrade;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Auth;
use App\Commission
;use App\Wallet;

trait TradeLiquidity
{
    public function liveprice()
    {
        $url = "https://api.crex24.com/v2/public/tickers"; 
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data);
    }

    public function btc_usd_ticker()
    { 
        $url='https://www.bitstamp.net/api/v2/ticker/btcusd';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $result_get=json_decode($data); 
        if(isset($result_get->error)){
            return '0.00';
        }
        elseif(isset($result_get)){
            return $result_get->last;        
        }
        else{
            return '0.00';    
        }
    }

    public function btc_usd_marketcapticker()
    { 
        $url = 'https://api.coinmarketcap.com/v2/ticker/?convert=BTC';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $result_get=json_decode($data); 

        foreach($result_get->data as $liveprice){
            if($liveprice->symbol == 'BTC'){
                return $liveprice->quotes->USD->price;
            }
        }
        return 3467.6235997;
    }

    public function eth_usd__marketcapticker()
    { 
        $url = 'https://api.coinmarketcap.com/v2/ticker/?convert=ETH';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $result_get=json_decode($data); 

        foreach($result_get->data as $liveprice){
            if($liveprice->symbol == 'ETH'){
                return $liveprice->quotes->USD->price;
            }
        }
        return 106.11712689;
    }


    public function eth_usd_ticker()
    {   
        $url='https://www.bitstamp.net/api/v2/ticker/ethusd';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $result_get=json_decode($data); 
        if(isset($result_get->errorDescription)){
            return '0.00';
        }
        elseif(isset($result_get)){
            return $result_get->last;
        }
        else{
            return '0.00';
        }
    }

    public function the_btc_ticker()
    {   
        $url='https://api.crex24.com/v2/public/tickers?instrument=THE-BTC';        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $result_get=json_decode($data); 
        if(isset($result_get->errorDescription)){
            return '0.00';
        }
        elseif(isset($result_get)){
            return $result_get[0]->last;
        }
        else{
            return '0.00';
        }
    }

    public function thr_btc_ticker()
    { 
        $url='https://api.crex24.com/v2/public/tickers?instrument=THR-BTC';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $result_get=json_decode($data);  
        if(isset($result_get->errorDescription)){
            return '0.00';
        }
        elseif(isset($result_get)){
            return $result_get[0]->last;    
        }
        else{
            return '0.00';
        }
    }

    public function tch_btc_ticker()
    {   
        $url='https://api.crex24.com/v2/public/tickers?instrument=TCH-BTC';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $result_get=json_decode($data); 
        if(isset($result_get->errorDescription)){
            return '0.00';
        }
        elseif(sizeof($result_get)>1){
            return '0.00';    
        }
        elseif(isset($result_get[0]->last)){
            return number_format($result_get[0]->last,8);    
        }
    }

    public function thx_btc_ticker()
    { 
        $url='https://api.crex24.com/v2/public/tickers?instrument=THX-BTC'; 
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $result_get=json_decode($data); 
        if(isset($result_get->errorDescription)){
            return '0.0000047';
        }
        elseif(isset($result_get)){
            return $result_get[0]->last;    
        }
        else{
            return '0.0000047';    
        }
    }

    public function thr_eth_ticker()
    {   
        $url='https://bitebtc.com/api/v1/ticker?market=thr_eth';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $result_get=json_decode($data); 
        if(isset($result_get->error)){
            return '0.00';
        }
        elseif(isset($result_get)){
            return $result_get->result->price;
        }
        else{
            return '0.00';    
        }
    }

    public function tch_eth_ticker()
    {   
        $url='https://bitebtc.com/api/v1/ticker?market=tch_eth';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $result_get=json_decode($data); 
        if(isset($result_get->error)){
            return '0.00';
        }
        elseif(isset($result_get)){
            return $result_get->result->price;
        }
        else{
            return '0.00';    
        }
    }

    public function thex_eth_ticker()
    {   
        $url='https://www.bitstamp.net/api/v2/ticker/ethusd';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $result_get=json_decode($data); 
        if(isset($result_get->error)){
            return '0.00';
        }
        elseif(isset($result_get)){
            $one_eth=$result_get->last;
$one_thex=0.67; // Dollar 
$liveprice =bcdiv(sprintf('%.10f',$one_thex),sprintf('%.10f',$one_eth),8);
return $liveprice;
}
else{
    return '0.00';    
}
}

public function thrwaves_btc_ticker()
{ 
    $url = 'https://api.coinmarketcap.com/v2/ticker/?convert=BTC';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    $result_get=json_decode($data); 

    foreach($result_get->data as $liveprice){
        if($liveprice->symbol == 'BTC'){
            $live_price=221/$liveprice->quotes->USD->price;
            return $live_price;
        }
    }
    return 767142.77013179;
}



// order book
public function ETH_USD(){

    $buyresult='';
    $sellresult='';
    $buy_max=$sell_min=$min=$max=0;

    $pair = 2; 
    $ordertype=1;
    $user = Auth::user();
    $buy = array();
    $sell = array();
// Completed Trade 
    $completedtrade = \DB::table('completedtrades')->where(['pair' => $pair])->orderBy('id', 'desc')->limit(5)->get();
// end
// Buy Trade
    $buytrades = \App\Buytrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'desc')->limit(5)->get();
// end
// Sell Trade
    $selltrades = \App\Selltrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'asc')->limit(5)->get();
// end
// Trade Pair
    $tradespair = \App\Tradepair::where(['id' => $pair])->first();      
// end 

    if(count($buytrades) > 0){
        $min=$buytrades[0]->price;
    }else{
        $min=0;
    }

    if(count($selltrades) > 0){
        $max=$selltrades[0]->price;
    }else{
        $max=0;
    }
    return ['max'=>$max,'min'=>$min];

//  ********   Liquidity  Concepts   *************** //

/*

$client = new \GuzzleHttp\Client();		
$response = $client->request('GET', 'https://bitebtc.com/api/v1/orders?market=eth_usd&count=200');
$result_get = json_decode($response->getBody());
$buyresult='';
$sellresult='';
$buy_max=$sell_min=$min=$max='';

if($result_get->result->buy){
foreach($result_get->result->buy as $k => $btc_ust_buy){

$buyresult[$k]['price'] =$btc_ust_buy->price;	
$buyresult[$k]['volume'] =$btc_ust_buy->amount;	
}
}
if($result_get->result->sell){
foreach($result_get->result->sell as $k => $btc_ust_sell){   
$sellresult[$k]['price'] =$btc_ust_sell->price;	
$sellresult[$k]['volume'] =$btc_ust_sell->amount;		
}

}

$url = "https://www.bitstamp.net/api/v2/ticker/ethusd/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result=curl_exec($ch);
curl_close($ch);
$data = json_decode($result, true);
$coin = $data['last'];

$max= $data['ask'];
$min= $data['bid'];

return ['buy'=>$buyresult,'sell'=>$sellresult,'max'=>$max,'min'=>$min];*/
}


public function TCHTRX_BTC(){

    $buyresult='';
    $sellresult='';
    $buy_max=$sell_min=$min=$max=0;

    $pair = 10; 
    $ordertype=1;
    $user = Auth::user();
    $uid = Auth::user()->id; 
    $buy = array();
    $sell = array();
// Completed Trade 
    $completedtrade = \DB::table('completedtrades')->where(['pair' => $pair])->orderBy('id', 'desc')->limit(5)->get();
// end
// Buy Trade
    $buytrades = \App\Buytrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'desc')->limit(5)->get();
// end
// Sell Trade
    $selltrades = \App\Selltrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'asc')->limit(5)->get();
// end
// Trade Pair
    $tradespair = \App\Tradepair::where(['id' => $pair])->first();      
// end 

    if(count($buytrades) > 0){
        $min=$buytrades[0]->price;
    }else{
        $min=0;
    }

    if(count($selltrades) > 0){
        $max=$selltrades[0]->price;
    }else{
        $max=0;
    }
    return ['buy'=>$buyresult,'sell'=>$sellresult,'max'=>$max,'min'=>$min];


//  ************   Liquidity Format  ****************  


/*
$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://v1-1.api.token.store/orderbook/THE_ETH');
$result_get = json_decode($response->getBody());

$buyresult='';
$sellresult='';
$buy_max=$sell_min=$min=$max='';

if($result_get->asks){
foreach($result_get->asks as $k => $btc_ust_buy){   

$buy_max[] = $btc_ust_buy->price;
$buyresult[$k]['price'] =$btc_ust_buy->price;   
$buyresult[$k]['volume'] =$btc_ust_buy->volume; 
}
$min= max($buy_max);
}
if($result_get->bids){
foreach($result_get->bids as $k => $btc_ust_sell){
$sell_min[] = $btc_ust_sell->price;
$sellresult[$k]['price'] =$btc_ust_sell->price; 
$sellresult[$k]['volume'] =$btc_ust_sell->volume;       
}
$max= min($sell_min);
}       return ['buy'=>$buyresult,'sell'=>$sellresult,'max'=>$max,'min'=>$min];*/
}




public function THX_BTC(){
    $client = new \GuzzleHttp\Client();     
    $response = $client->request('GET', 'https://api.crex24.com/v2/public/orderBook?instrument=THX-BTC');
    $result_get = json_decode($response->getBody());
    $buy_max=$sell_min=$min=$max='';
    foreach($result_get->buyLevels as $k => $btc_ust_buy){  
        $buy_max[] = $btc_ust_buy->price;
        $buyresult[$k]['price'] =$btc_ust_buy->price;   
        $buyresult[$k]['volume'] =$btc_ust_buy->volume; 
    }
    $min= max($buy_max);
    foreach($result_get->sellLevels as $k => $btc_ust_sell){
        $sell_min[] = $btc_ust_sell->price;
        $sellresult[$k]['price'] =$btc_ust_sell->price; 
        $sellresult[$k]['volume'] =$btc_ust_sell->volume;       
    }
    $max= min($sell_min);
    return ['buy'=>$buyresult,'sell'=>$sellresult,'max'=>$max,'min'=>$min];

/*if($result_get->result->buy){
foreach($result_get->result->buy as $k => $btc_ust_buy){    
$buyresult[$k]['price'] =$btc_ust_buy->price;   
$buyresult[$k]['volume'] =$btc_ust_buy->amount; 
}
}
if($result_get->result->sell){
foreach($result_get->result->sell as $k => $btc_ust_sell){
$sellresult[$k]['price'] =$btc_ust_sell->price; 
$sellresult[$k]['volume'] =$btc_ust_sell->amount;       
}
}
*/
return ['buy'=>$buyresult,'sell'=>$sellresult,'max'=>$max,'min'=>$min];
}


public function TCH_ETH(){

    $buyresult='';
    $sellresult='';
    $buy_max=$sell_min=$min=$max=0;

    $pair = 8; 
    $ordertype=1;
    $user = Auth::user();
    $buy = array();
    $sell = array();
// Completed Trade 
    $completedtrade = \DB::table('completedtrades')->where(['pair' => $pair])->orderBy('id', 'desc')->limit(5)->get();
// end
// Buy Trade
    $buytrades = \App\Buytrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'desc')->limit(5)->get();
// end
// Sell Trade
    $selltrades = \App\Selltrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'asc')->limit(5)->get();
// end
// Trade Pair
    $tradespair = \App\Tradepair::where(['id' => $pair])->first();      
// end 

    if(count($buytrades) > 0){
        $min=$buytrades[0]->price;
    }else{
        $min=0;
    }

    if(count($selltrades) > 0){
        $max=$selltrades[0]->price;
    }else{
        $max=0;
    }
    return ['max'=>$max,'min'=>$min];

///   ******************* Liquidy   Concepts  ********************** 


/*$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'https://bitebtc.com/api/v1/orders?market=tch_eth&count=200');
$result_get = json_decode($response->getBody());
$buyresult='';
$sellresult='';
$buy_max=$sell_min=$min=$max='';
if($result_get->result->buy){
foreach($result_get->result->buy as $k => $btc_ust_buy){	
$buy_max[] = $btc_ust_buy->price;
$buyresult[$k]['price'] =$btc_ust_buy->price;	
$buyresult[$k]['volume'] =$btc_ust_buy->amount;	
}
$min= max($buy_max);
}
if($result_get->result->sell){
foreach($result_get->result->sell as $k => $btc_ust_sell){
$sell_min[] = $btc_ust_sell->price;
$sellresult[$k]['price'] =$btc_ust_sell->price;	
$sellresult[$k]['volume'] =$btc_ust_sell->amount;		
}
$max= min($sell_min);
}
return ['buy'=>$buyresult,'sell'=>$sellresult,'max'=>$max,'min'=>$min];*/
}

public function THR_ETH(){

    $buyresult='';
    $sellresult='';
    $buy_max=$sell_min=$min=$max=0;

    $pair = 6; 
    $ordertype=1;
    $user = Auth::user();
    $buy = array();
    $sell = array();
// Completed Trade 
    $completedtrade = \DB::table('completedtrades')->where(['pair' => $pair])->orderBy('id', 'desc')->limit(5)->get();
// end
// Buy Trade
    $buytrades = \App\Buytrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'desc')->limit(5)->get();
// end
// Sell Trade
    $selltrades = \App\Selltrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'asc')->limit(5)->get();
// end
// Trade Pair
    $tradespair = \App\Tradepair::where(['id' => $pair])->first();      
// end 

    if(count($buytrades) > 0){
        $min=$buytrades[0]->price;
    }else{
        $min=0;
    }

    if(count($selltrades) > 0){
        $max=$selltrades[0]->price;
    }else{
        $max=0;
    }
    return ['max'=>$max,'min'=>$min];

//  ************   Liquidity Format  ****************  

/*$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'https://bitebtc.com/api/v1/orders?market=thr_eth&count=200');
$result_get = json_decode($response->getBody());
$buyresult='';
$sellresult='';
$buy_max=$sell_min=$min=$max='';

if($result_get->result->buy){
foreach($result_get->result->buy as $k => $btc_ust_buy){
$buy_max[] = $btc_ust_buy->price;
$buyresult[$k]['price'] =$btc_ust_buy->price;	
$buyresult[$k]['volume'] =$btc_ust_buy->amount;	
}
$min= max($buy_max);
}
if($result_get->result->sell){
foreach($result_get->result->sell as $k => $btc_ust_sell){
$sell_min[] = $btc_ust_sell->price;
$sellresult[$k]['price'] =$btc_ust_sell->price;	
$sellresult[$k]['volume'] =$btc_ust_sell->amount;		
}
$max= min($sell_min);
}
*/

}


public function THE_ETH(){

    $buyresult='';
    $sellresult='';
    $buy_max=$sell_min=$min=$max=0;

    $pair = 4; 
    $ordertype=1;
    $user = Auth::user();
    $buy = array();
    $sell = array();
// Completed Trade 
    $completedtrade = \DB::table('completedtrades')->where(['pair' => $pair])->orderBy('id', 'desc')->limit(5)->get();
// end
// Buy Trade
    $buytrades = \App\Buytrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'desc')->limit(5)->get();
// end
// Sell Trade
    $selltrades = \App\Selltrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'asc')->limit(5)->get();
// end
// Trade Pair
    $tradespair = \App\Tradepair::where(['id' => $pair])->first();      
// end 

    if(count($buytrades) > 0){
        $min=$buytrades[0]->price;
    }else{
        $min=0;
    }

    if(count($selltrades) > 0){
        $max=$selltrades[0]->price;
    }else{
        $max=0;
    }
    return ['max'=>$max,'min'=>$min];

//  ************   Liquidity Format  ****************  


/*
$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://v1-1.api.token.store/orderbook/THE_ETH');
$result_get = json_decode($response->getBody());

$buyresult='';
$sellresult='';
$buy_max=$sell_min=$min=$max='';

if($result_get->asks){
foreach($result_get->asks as $k => $btc_ust_buy){	

$buy_max[] = $btc_ust_buy->price;
$buyresult[$k]['price'] =$btc_ust_buy->price;	
$buyresult[$k]['volume'] =$btc_ust_buy->volume;	
}
$min= max($buy_max);
}
if($result_get->bids){
foreach($result_get->bids as $k => $btc_ust_sell){
$sell_min[] = $btc_ust_sell->price;
$sellresult[$k]['price'] =$btc_ust_sell->price;	
$sellresult[$k]['volume'] =$btc_ust_sell->volume;		
}
$max= min($sell_min);
}		return ['buy'=>$buyresult,'sell'=>$sellresult,'max'=>$max,'min'=>$min];*/
}


public function BTC_USD(){
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', 'https://api.crex24.com/v2/public/orderBook?instrument=BTC-USD');
    $result_get = json_decode($response->getBody());
    $buy_max=$sell_min=$min=$max='';

    foreach($result_get->buyLevels as $k => $btc_ust_buy){
        $buyresult[$k]['price'] =$btc_ust_buy->price;	
        $buyresult[$k]['volume'] =$btc_ust_buy->volume;	
    }

    foreach($result_get->sellLevels as $k => $btc_ust_sell){   
        $sellresult[$k]['price'] =$btc_ust_sell->price; 
        $sellresult[$k]['volume'] =$btc_ust_sell->volume;       
    }

    $url = "https://www.bitstamp.net/api/v2/ticker/btcusd/";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result=curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, true);
    $coin = $data['last'];

    $max= $data['ask'];
    $min= $data['bid'];


    return ['buy'=>$buyresult,'sell'=>$sellresult,'max'=>$max,'min'=>$min];
}

public function THE_BTC(){

    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', 'https://api.crex24.com/v2/public/orderBook?instrument=THE-BTC');

    $result_get = json_decode($response->getBody());

    $buy_max=$sell_min=$min=$max='';

    foreach($result_get->buyLevels as $k => $btc_ust_buy){	
        $buy_max[] = $btc_ust_buy->price;
        $buyresult[$k]['price'] =$btc_ust_buy->price;	
        $buyresult[$k]['volume'] =$btc_ust_buy->volume;	
    }
    $min= max($buy_max);
    foreach($result_get->sellLevels as $k => $btc_ust_sell){
        $sell_min[] = $btc_ust_sell->price;
        $sellresult[$k]['price'] =$btc_ust_sell->price;	
        $sellresult[$k]['volume'] =$btc_ust_sell->volume;		
    }
    $max= min($sell_min);
    return ['buy'=>$buyresult,'sell'=>$sellresult,'max'=>$max,'min'=>$min];

}

public function THR_BTC(){

    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', 'https://api.crex24.com/v2/public/orderBook?instrument=THR-BTC');


    $result_get = json_decode($response->getBody());

    $buy_max=$sell_min=$min=$max='';

    foreach($result_get->buyLevels as $k => $btc_ust_buy){	
        $buy_max[] = $btc_ust_buy->price;
        $buyresult[$k]['price'] =$btc_ust_buy->price;	
        $buyresult[$k]['volume'] =$btc_ust_buy->volume;	
    }
    $min= max($buy_max);
    foreach($result_get->sellLevels as $k => $btc_ust_sell){
        $sell_min[] = $btc_ust_sell->price;
        $sellresult[$k]['price'] =$btc_ust_sell->price;	
        $sellresult[$k]['volume'] =$btc_ust_sell->volume;		
    }
    $max= min($sell_min);
    return ['buy'=>$buyresult,'sell'=>$sellresult,'max'=>$max,'min'=>$min];

}
public function TCH_BTC(){

    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET','https://api.crex24.com/v2/public/orderBook?instrument=TCH-BTC');

    $result_get = json_decode($response->getBody());

    $buy_max=$sell_min=$min=$max='';

    foreach($result_get->buyLevels as $k => $btc_ust_buy){	
        $buy_max[] = $btc_ust_buy->price;
        $buyresult[$k]['price'] =$btc_ust_buy->price;	
        $buyresult[$k]['volume'] =$btc_ust_buy->volume;	
    }
    $min= max($buy_max);
    foreach($result_get->sellLevels as $k => $btc_ust_sell){
        $sell_min[] = $btc_ust_sell->price;
        $sellresult[$k]['price'] =$btc_ust_sell->price;	
        $sellresult[$k]['volume'] =$btc_ust_sell->volume;		
    }
    $max= min($sell_min);
    return ['buy'=>$buyresult,'sell'=>$sellresult,'max'=>$max,'min'=>$min];
}
//end order bokk

//end trading pair

public function THORECOIN_BTC(){        
    $buyresult='';
    $sellresult='';
    $buy_max=$sell_min=$min=$max=0;

    $pair = 11; 
    $ordertype=1;
    $user = Auth::user();
// $uid = Auth::user()->id; 
    $buy = array();
    $sell = array();
    $completedtrade = \DB::table('completedtrades')->where(['pair' => $pair])->orderBy('id', 'desc')->limit(5)->get();
    $buytrades = \App\Buytrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'desc')->limit(5)->get();
    $selltrades = \App\Selltrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'asc')->limit(5)->get();
    $tradespair = \App\Tradepair::where(['id' => $pair])->first();      
    if(count($buytrades) > 0){
        $min=$buytrades[0]->price;
    }else{
        $min=0;
    }
    if(count($selltrades) > 0){
        $max=$selltrades[0]->price;
    }else{
        $max=0;
    }
    return ['buy'=>$buyresult,'sell'=>$sellresult,'max'=>$max,'min'=>$min];
}


public function THEX_ETH(){        
    $buyresult='';
    $sellresult='';
    $buy_max=$sell_min=$min=$max=0;

    $pair = 12; 
    $ordertype=1;
    $user = Auth::user();
// $uid = Auth::user()->id; 
    $buy = array();
    $sell = array();
    $completedtrade = \DB::table('completedtrades')->where(['pair' => $pair])->orderBy('id', 'desc')->limit(5)->get();
    $buytrades = \App\Buytrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'desc')->limit(5)->get();
    $selltrades = \App\Selltrade::where(['order_type' => $ordertype, 'pair' => $pair,'status' => 0])->orderBy('price', 'asc')->limit(5)->get();
    $tradespair = \App\Tradepair::where(['id' => $pair])->first();      

    if(count($buytrades) > 0){
        $min=$buytrades[0]->price;
    }else{
        $min=0;
    }
    if(count($selltrades) > 0){
        $max=$selltrades[0]->price;
    }else{
        $max=0;
    }
    return ['buy'=>$buyresult,'sell'=>$sellresult,'max'=>$max,'min'=>$min];
}

// buy place order 

public function BTC_USD_buyplaceorder($buy_price,$buy_volume){

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "BTC-USD",    
        "volume":"'. $buy_volume.'",
        "price":"'. $buy_price.'",
        "side": "buy"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);
}



public function THX_BTC_buyplaceorder($buy_price,$buy_volume){

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "THX-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"'. $buy_price.'",
        "side": "buy"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 
    return json_decode($responseBody);
}


public function ETH_USD_buyplaceorder(){}


public function TCH_BTC_buyplaceorder($buy_price,$buy_volume){

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "TCH-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"'. $buy_price.'",
        "side": "buy"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);

}

public function THE_BTC_buyplaceorder($buy_price,$buy_volume){

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "THE-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"'. $buy_price.'",
        "side": "buy"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);
}

public function THR_BTC_buyplaceorder($buy_price,$buy_volume){

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "THR-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"'. $buy_price.'",
        "side": "buy"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);

}

public function TCH_ETH_buyplaceorder(){}

public function THE_ETH_buyplaceorder(){}

public function THR_ETH_buyplaceorder(){}


// sell place order

public function BTC_USD_sellplaceorder($buy_price,$buy_volume){

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "BTC-USD",    
        "volume":"'. $buy_volume.'",
        "price":"'. $buy_price.'",
        "side": "sell"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);
}



public function THX_BTC_sellplaceorder($buy_price,$buy_volume){

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "THX-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"'. $buy_price.'",
        "side": "sell"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);
}


public function ETH_USD_sellplaceorder(){}


public function TCH_BTC_sellplaceorder($buy_price,$buy_volume){

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "TCH-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"'. $buy_price.'",
        "side": "sell"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);

}

public function THE_BTC_sellplaceorder($buy_price,$buy_volume){

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "THE-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"'. $buy_price.'",
        "side": "sell"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);
}

public function THR_BTC_sellplaceorder($buy_price,$buy_volume){

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "THR-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"'. $buy_price.'",
        "side": "sell"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);

}

public function TCH_ETH_sellplaceorder(){}

public function THE_ETH_sellplaceorder(){}

public function THR_ETH_sellplaceorder(){}


// market
public function BTC_USD_buy_markerplaceorder($buy_volume){
    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "BTC-USD",    
        "volume":"'. $buy_volume.'",
        "price":"1",
        "side": "buy",
        "type":"market"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);
}


public function THR_BTC_buy_markerplaceorder($buy_volume){
    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "THR-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"1",
        "side": "buy",
        "type":"market"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);
}

public function THE_BTC_buy_markerplaceorder($buy_volume){
    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "THE-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"1",
        "side": "buy",
        "type":"market"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);
}
public function TCH_BTC_buy_markerplaceorder($buy_volume){

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "TCH-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"1",
        "side": "buy",
        "type":"market"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);

}

public function ETH_USD_buy_markerplaceorder(){}
public function THR_ETH_buy_markerplaceorder(){}
public function THE_ETH_buy_markerplaceorder(){}
public function TCH_ETH_buy_markerplaceorder(){}


public function BTC_USD_sell_markerplaceorder($buy_volume){
    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "BTC-USD",    
        "volume":"'. $buy_volume.'",
        "price":"1",
        "side": "sell",
        "type":"market"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);
}

public function THR_BTC_sell_markerplaceorder($buy_volume){

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';
    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "THR-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"1",
        "side": "sell",
        "type":"market"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);

}
public function THE_BTC_sell_markerplaceorder($buy_volume){
    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "THE-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"1",
        "side": "sell",
        "type":"market"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);
}
public function TCH_BTC_sell_markerplaceorder($buy_volume){

    $baseUrl = 'https://api.crex24.com';
    $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
    $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

    $path = '/v2/trading/placeOrder';
    $body = '{
        "instrument": "TCH-BTC",    
        "volume":"'. $buy_volume.'",
        "price":"1",
        "side": "sell",
        "type":"market"
    }';
    $nonce = round(microtime(true) * 1000);

    $key = base64_decode($secret);
    $message = $path . $nonce . $body;
    $signature = base64_encode(hash_hmac('sha512', $message, $key, true));
//$signature = base64_encode(HMAC-SHA512('sha512', $message, $key, true));

    $curl = curl_init($baseUrl . $path);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Length:' . strlen($body),
        'X-CREX24-API-KEY:' . $apiKey,
        'X-CREX24-API-NONCE:' . $nonce,
        'X-CREX24-API-SIGN:' . $signature
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $responseBody = curl_exec($curl);
    $responseStatusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl); 

    return json_decode($responseBody);

}

public function ETH_USD_sell_markerplaceorder(){}
public function THR_ETH_sell_markerplaceorder(){}
public function THE_ETH_sell_markerplaceorder(){}
public function TCH_ETH_sell_markerplaceorder(){}

//end market


//trade history


function getChartData($pair)
{

    $select_chart = TradeChart::where('pair', $pair)->get();

    if(count($select_chart) > 0)
    {
        $i = 1;
        $data=array();
        foreach($select_chart as $list)
        {
            $date = strtotime($list->created_at).'000'; 
            $data[]=[(double)$date,$list->open,$list->high,$list->low,$list->close];
        }
        return json_encode($data);
    }else{
        $data=0;
        return json_encode($data);
    }

}


}