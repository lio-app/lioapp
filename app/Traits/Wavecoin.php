<?php
namespace App\Traits;

trait Wavecoin 
{	
	private $chwave;
	private $paramswave;
	private $result_wave;
	private $url_wave = "https://insight.bitpay.com/api/";


	private function _callwave($params){	
		$this->chwave = curl_init();
		$this->paramswave = $params; 
		curl_setopt($this->chwave, CURLOPT_URL, "http://139.180.137.202:8091");
		// curl_setopt($this->chwave, CURLOPT_URL, "http://127.0.0.1:8091");
		curl_setopt($this->chwave, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->chwave, CURLOPT_POST, 1);
		curl_setopt($this->chwave, CURLOPT_POSTFIELDS, json_encode($this->paramswave));
		$headers = array();
		$headers[] = "Content-Type : application/json";
		curl_setopt($this->chwave, CURLOPT_HTTPHEADER, $headers);
		$this->result = curl_exec($this->chwave);
		if (curl_errno($this->chwave)) {
			echo 'Error:' . curl_error($this->chwave);
		}
		curl_close($this->chwave);
		return json_decode($this->result);
	}

		
	// create address
	public function createaddress_wave(){
	   
		$params = array("method" => "create_address");
		if(!empty($params)){
			return $this->_callwave($params);
		}
	}
	
	public function createmsigaddresswave(){
		$params = array("method" => "create_multisig_address");
		if(!empty($params)){
			return $this->_callwave($params);
		}
	}
// get transaction
		public function getTransactionswave($address){
		$params = array("method" => "gettransaction", 'address' => $address);
			if(!empty($params)){
			return $this->_callwave($params);
		}		
	}	
	
	// send bitcoin
	public function wavesend($amount,$to_address,$assetId,$pvtk,$from_address){
				$params = array("method" => "send_liquidity_admin",
			   'from_address' => $from_address,
			   'to_address' => $to_address,
			   'amount' => $amount,
			  // 'assetId' => $assetId,
			   'pvtk' => $pvtk
			);
			if(!empty($params)){
			return $this->_callwave($params);
		}		
	}
	
	private function sendwave($rawtx){
		if(!empty($rawtx)){
			$url = $this->url."tx/send";
			$ch = curl_init();
			$params = array("rawtx" => $rawtx);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
			curl_setopt($ch, CURLOPT_POST, 1);
			$headers = array();
			$headers[] = "Accept: application/json, text/plain";
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			if(curl_errno($ch)) {
				echo $result = 'Error:' . curl_error($ch);
			} else {
				$result = curl_exec($ch);
			}
			curl_close($ch);
			return json_decode($result);
		}
	}
	
	private function utxowave($address){
	    
		if(!empty($address)){
			$url = $this->url."addr/$address/utxo?noCache=1";
			return $this->cUrl1($url);
		}
	}
	
	public function txwave($txid){
		if(!empty($txid)){
			$url = $this->url."tx/$txid";
			return json_decode($this->cUrl1($url));
		}
	}

	// get wave balance
		public function getBalancewave($address,$assetId){
		$params = array("method" => "get_balance", 'address' => $address, 'assetId' => $assetId);
                // $params = array("method" => "get_balance", 'address' => $address);
			if(!empty($params)){
			return $this->_callwave($params);
		}		
	}

	
	public function totalReceivedwave($address){
		if(!empty($address)){
			$url = $this->url."addr/$address/totalReceived";
			$balance = $this->cUrl1($url);
			return $this->sathositobtc($balance);
		}
	}
	
	public function totalSentwave($address){
		if(!empty($address)){
			$url = $this->url."addr/$address/totalSent";
			$balance = $this->cUrl1($url);
			return $this->sathositobtc($balance);
		}
	}
	
	public function unconfirmedBalancewave($address){
		if(!empty($address)){
			$url = $this->url."addr/$address/unconfirmedBalance";
			$balance = $this->cUrl1($url);
			return $this->sathositobtc($balance);
		}
	}
	
	private function cUrlwave($url){
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
		return $result;
	}
	


public function cUrlswave($url, $postfilds=null){
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
	
}
?>