<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\EcpaySend;
use Exception;
use GuzzleHttp\Client;

class sendECpay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:ecpay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send ecpay for whom alredy fee sent from fee wallet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info("Ecpay cron executed at ".time());
        $pending = EcpaySend::where('sent_status',0)->get();
        if(count($pending)>0) {
            foreach($pending as $ecpay){
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ];
                $body = ["jsonrpc" => "2.0", "method" => "getPrivateKey", "params" => array("method" => "getPrivateKey", "address" => $ecpay->from_address, "password" => $ecpay->user_email)];
                $url = "http://localhost:8084/getKey";
                $res = $client->post($url, [
                    'headers' => $headers,
                    'body' => json_encode($body),
                ]);

                $details = json_decode($res->getBody(), true);
                if (isset($details['privateKey'])) {
                    $ch = curl_init();
                    $params = array(
                        "method" => "create_rawecpaytoken",
                        "formaddr" => $ecpay->from_address,
                        "pvk" => $details['privateKey'],
                        "toddr" => $ecpay->to_address,
                        "amount" => $ecpay->amount * 1000000000000,
                        "url" => "https://mainnet.infura.io/v3/9a362ef8feb14299943089c1f563077e",
                        "gasprice"=> $ecpay->gasprice,
                    );
                    curl_setopt($ch, CURLOPT_URL, "http://localhost:8110");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                    $headers = array();
                    $headers[] = "Content-Type : application/json";
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                    curl_close($ch);
                    $result = json_decode($result);
                    if (isset($result->error)) {
                    	print_r($result->error);
                        echo "Transaction Failed Low ETH Balance.";
                    } else {
                        /// Update sendeth table after success
                        $ecpay->sent_status =1;
                        $ecpay->txid = $result->txid;
                        $ecpay->save();
                        $curldata['result'] = $result->txid;  //txid
                    }
                } 
            }
        } else {
            echo "No pending ecpay requests.";
        }
    }
}
