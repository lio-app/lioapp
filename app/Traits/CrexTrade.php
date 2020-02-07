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


trait CrexTrade {


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

        return json_decode($responseBody);

    }


    public function crexorderstatuscUrls($orderid)
    {
        $baseUrl = 'https://api.crex24.com';
        $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
        $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

//https://api.crex24.com/v2/account/balance
        $path = '/v2/trading/orderStatus?id='.$orderid;
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

        return json_decode($responseBody);

    }



    public function crexordercancelcUrls($orderid)
    {

        $baseUrl = 'https://api.crex24.com';
        $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
        $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';
//    "ids": [orderid]
        $path = '/v2/trading/cancelOrdersById';
        $body = '{
            "ids": ['. $orderid.']
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



    public function crextradehistorycUrls()
    {
        $baseUrl = 'https://api.crex24.com';
        $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
        $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';
//https://api.crex24.com/v2/account/balance
        $path = '/v2/trading/tradeHistory';
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

        return json_decode($responseBody);

    }



    public function crexwithdrawcUrls($address,$amount,$currency)
    {

        $baseUrl = 'https://api.crex24.com';
        $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
        $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

        $path = '/v2/account/withdraw';
        $body = '{  
            "currency":"'. $currency.'",
            "amount":"'. $amount.'",
            "address": "'. $address.'"
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

    public function crexwithdrawstatuscUrls()
    {
        $baseUrl = 'https://api.crex24.com';
        $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
        $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

//https://api.crex24.com/v2/account/balance
        $path = '/v2/account/moneyTransfers';
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


        return json_decode($responseBody);
    }

    public function get_crex_balance($currency)
    {
        $baseUrl = 'https://api.crex24.com';
        $apiKey = '46504171-631c-4559-b72e-8d8cb010ea9a';
        $secret = '1eix5sKvu6H+FNkDkLgMQTWyWElVGhoI4Nvr5zVIBY76fmU4pRDlRm2cRD6v2h/rrBEak74ZzFq8KCyhqxm1tQ==';

        $currency = $currency;
        $path = '/v2/account/balance?currency='.$currency;
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
        return json_decode($responseBody);

    }


}