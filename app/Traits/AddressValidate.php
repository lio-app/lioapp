<?php 

namespace App\Traits;
trait AddressValidate {

    function protect($string)
    {     
        $protection = htmlspecialchars(trim(strip_tags(addslashes($string))),ENT_QUOTES);
        return $protection;
    }
    
    public function validateEth($address)
    {
        if ($this->matchesPattern($address)) {
            return $this->isAllSameCaps($address) ?: $this->isValidChecksum($address);
        }

        return false;
    }

    protected function matchesPattern($address)
    {
        // (!/^(0x)?[0-9a-f]{40}$/i.test(address))
        return preg_match('/^(0x)?[0-9a-f]{40}$/i', $address);
    }

    protected function isAllSameCaps($address)
    {
        return preg_match('/^(0x)?[0-9a-f]{40}$/', $address) || preg_match('/^(0x)?[0-9A-F]{40}$/', $address);
    }

    public function isAddress($address)
    {
        // See: https://github.com/ethereum/web3.js/blob/7935e5f/lib/utils/utils.js#L415
        if ($this->matchesPattern($address)) {            
            return $this->isAllSameCaps($address) ?: $this->isValidChecksum($address);
        }
        return false;
    }


    protected function isValidChecksum($address)
    {
        $address = str_replace('0x', '', $address);
        // See: https://github.com/ethereum/web3.js/blob/b794007/lib/utils/sha3.js#L35
        $hash = Sha3::hash(strtolower($address), 256);
        // See: https://github.com/web3j/web3j/pull/134/files#diff-db8702981afff54d3de6a913f13b7be4R42
        for ($i = 0; $i < 40; $i++ ) {
            if (ctype_alpha($address{$i})) {
                // Each uppercase letter should correlate with a first bit of 1 in the hash char with the same index,
                // and each lowercase letter with a 0 bit.
                $charInt = intval($hash{$i}, 16);
                if ((ctype_upper($address{$i}) && $charInt <= 7) || (ctype_lower($address{$i}) && $charInt > 7)) {
                    return false;
                }
            }
        }

        return true;
    }


    public function validateBtc($btcAddress)
    {
        $message = "OK";
        try 
        {
            $this->validateBtcAddress($btcAddress);
        } 
        catch (\Exception $e){
         $message = $e->getMessage(); 
        } 
        return "$message";
    }


    public function validateBtcAddress($address)
    {
        $decoded = $this->decodeBase58($address);
        $d1 = hash("sha256", substr($decoded,0,21), true);
        $d2 = hash("sha256", $d1, true);
        if(substr_compare($decoded, $d2, 21, 4)){
            throw new \Exception("Invalid BTC Address");
        }
        return true;
    }
    
    
    public function decodeBase58($input) 
    {
        $alphabet = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";

        $out = array_fill(0, 25, 0);
        for($i=0;$i<strlen($input);$i++){
            if(($p=strpos($alphabet, $input[$i]))===false){
                throw new \Exception("Invalid BTC Address");
            }
            $c = $p;
            for ($j = 25; $j--; ) {
                $c += (int)(58 * $out[$j]);
                $out[$j] = (int)($c % 256);
                $c /= 256;
                $c = (int)$c;
            }
            if($c != 0){
                throw new \Exception("Invalid BTC Address");
            }
        }

        $result = "";
        foreach($out as $val){
            $result .= chr($val);
        }
        return $result;
    }
 }