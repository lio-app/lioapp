<?php
namespace App\Traits;

trait Troncoin 
{	


	private function _calltron($params){	


		$this->chwave = curl_init();
		$this->paramswave = $params; 
		// curl_setopt($this->chwave, CURLOPT_URL, "http://139.180.137.202:8090");
		curl_setopt($this->chwave, CURLOPT_URL, "http://127.0.0.1:8090");
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

// 		$chwave = curl_init();
// 		$paramswave = $params; 
// 		// curl_setopt($chwave, CURLOPT_URL, "http://tron.thoreexchange.com/api.php");
// 		curl_setopt($chwave, CURLOPT_URL, "http://127.0.0.1:8090");
// 		curl_setopt($chwave, CURLOPT_RETURNTRANSFER, 1);
// 		curl_setopt($chwave, CURLOPT_POST, 1);
// 		curl_setopt($chwave, CURLOPT_POSTFIELDS, json_encode($this->paramswave));
// 		curl_setopt($chwave, CURLOPT_SSL_VERIFYPEER, FALSE);
// 		$headers = array();
// 		curl_setopt($chwave, CURLOPT_HTTPHEADER, $headers);
// 		$result = curl_exec($chwave);

// 		if (curl_errno($chwave)) {
// 			echo 'Error:' . curl_error($chwave);
// 		}
// 		curl_close($chwave);
// // var_dump(json_decode($result));
// 		return json_decode($result);


}

public function createaddress_tron(){	   
	$params = array("method" => "create_address");
	if(!empty($params)){
		return $this->_calltron($params);
	}
}


public function getBalanceTron($address){
	if(!empty($address)){
		$params = array("method" => "getbalance", 'address' => $address);
		if(!empty($params)){
			return $this->_calltron($params);
		}

	}else{
		return 0;
	}
}

// get transaction
public function getTransactionstron(){
	$params = array("method" => "gettransaction");
	if(!empty($params)){
		$cc= $this->_calltron($params);
		return $cc;
	}		
}

// get transaction using txid
public function getTransactionstronId(){
	$params = array("method" => "getTronTransactionId");
	if(!empty($params)){
		return $this->_calltron($params);
	}		
}

public function tronsend($to_address,$amount,$from_address,$pvtk){		
	$params = array("method" => "withdraw",
		'from_address' => $from_address,
		'to_address' => $to_address,
		'amount' => $amount,
		'pvtk' => $pvtk
	);
	if(!empty($params)){
		return $this->_calltron($params);
	}		
}

public function bttsend($to_address,$amount,$from_address,$pvtk){
	$params = array("method" => "btt_withdraw",
		'from_address' => $from_address,
		'to_address' => $to_address,
		'amount' => $amount,
		'pvtk' => $pvtk
	);
	if(!empty($params)){
		return $this->_calltron($params);
	}		
}

public function getAdminBalanceTron($address){
	$params = array("method" => "get_admin_balance", 'address' => $address);
	if(!empty($params)){
		return $this->_calltron($params);
	}		
}
}
?>