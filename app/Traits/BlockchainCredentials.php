<?php
namespace App\Traits;

trait BlockchainCredentials
{
	public function BtcCredentials(){
		$data['api_code'] = '85c98987-284a-496f-a396-66bcdfb084b4';
		$data['guid'] = '1e368e63-22d1-4419-9680-7923cb3de042';
		$data['password'] = 'Thorexchange@$%!55';
		$data['url'] = 'http://localhost:3000';
		return $data; 
	}

    // 85c98987-284a-496f-a396-66bcdfb084b4

	public function btcvalidate($address){
        $decoded = $this->decodeBase58($address); 
        $d1 = hash("sha256", substr($decoded,0,21), true);
        $d2 = hash("sha256", $d1, true);

        if(substr_compare($decoded, $d2, 21, 4)){
            return "Invalid BTC Address" ;
        }
        return true;
    }
/*
     function decodeBase58($input) {
        $alphabet = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";

        $out = array_fill(0, 25, 0);
        for($i=0;$i<strlen($input);$i++){
            if(($p=strpos($alphabet, $input[$i]))===false){
                return "Invalid BTC Address" ;
            }
            $c = $p;
            for ($j = 25; $j--; ) {
                $c += (int)(58 * $out[$j]);
                $out[$j] = (int)($c % 256);
                $c /= 256;
                $c = (int)$c;
            }
            if($c != 0){
                return "Invalid BTC Address" ;
            }
        }

        $result = "";
        foreach($out as $val){
            $result .= chr($val);
        }
        return $result;
    }*/

    public function withdraw_commission($pair)
    {
       $settings =  \App\Settings::where('pair',$pair)->first();
       $withdraw_comm = $settings->withdraw_commission;
       return ($withdraw_comm/100);
    }
}


?>