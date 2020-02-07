<?php
namespace App\Traits;

trait Ethereum 
{	
	public function eth_user_address_create(){
		$url = "https://api.blockcypher.com/v1/eth/main/addrs";
		$result = $this->exec_addr_cUrl($url);
		if($result)
		{
			return $result;
		}
	}

	public function exec_addr_cUrl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
		if (curl_errno($ch)) {
			$result = 'Error:' . curl_error($ch);
		} else {
			$result = curl_exec($ch);
		}
		curl_close($ch);
		return json_decode($result, true);
	}
	
	public function cUrl($url){
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
		return json_decode($result, true);
	}

	
	public function exec_cUrls($url, $postfilds=null)
	{
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
	
	public function getEthBalance($address)
	{
		$url = "https://api.etherscan.io/api?module=account&action=balance&address=".$address;
		$balance = $this->cUrl($url);
		return $balance;
	}

	
	public function ethSendTransaction($fromaddress, $toaddress, $eth_amount, $pvk)
	{
	    $ch = curl_init();
		$params = array(
			"method" => "create_rawtx",
			"formaddr" => $fromaddress,
			"pvk" => $pvk,
			"toddr" => $toaddress,
			"amount" => $eth_amount,
			"url" => "https://mainnet.infura.io/YRMZb6DozOUKLJTO7hs"
		);
		curl_setopt($ch, CURLOPT_URL, "http://45.76.223.201:8545");
		// curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8545");
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
		return json_decode($result);
	}
	
	public function theSendTransaction($fromaddress, $toaddress, $eth_amount, $pvk)
	{
	    $ch = curl_init();
		$params = array(
			"method" => "create_rawthe",
			"formaddr" => $fromaddress,
			"pvk" => $pvk,
			"toddr" => $toaddress,
			"amount" => $eth_amount,
			"url" => "https://mainnet.infura.io/YRMZb6DozOUKLJTO7hs"
		);
		curl_setopt($ch, CURLOPT_URL, "http://45.76.223.201:8545");
		// curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8545");
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
				return json_decode($result);
	}

	public function thrSendTransaction($fromaddress, $toaddress, $eth_amount, $pvk)
	{
	    $ch = curl_init();
		$params = array(
			"method" => "create_rawthr",
			"formaddr" => $fromaddress,
			"pvk" => $pvk,
			"toddr" => $toaddress,
			"amount" => $eth_amount,
			"url" => "https://mainnet.infura.io/YRMZb6DozOUKLJTO7hs"
		);
		curl_setopt($ch, CURLOPT_URL, "http://45.76.223.201:8545");
		// curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8545");
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
		return json_decode($result);
	}

	public function thxSendTransaction($fromaddress, $toaddress, $eth_amount, $pvk)
	{
	    $ch = curl_init();
		$params = array(
			"method" => "create_rawthx",
			"formaddr" => $fromaddress,
			"pvk" => $pvk,
			"toddr" => $toaddress,
			"amount" => $eth_amount,
			"url" => "https://mainnet.infura.io/YRMZb6DozOUKLJTO7hs"
		);
		curl_setopt($ch, CURLOPT_URL, "http://45.76.223.201:8545");
		// curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8545");
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
		return json_decode($result);
	}

}
?>