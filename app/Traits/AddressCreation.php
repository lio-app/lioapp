<?php 
namespace App\Traits;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\BtcClass;
use App\Traits\EthClass;
use App\Traits\LtcClass;

trait AddressCreation {

	use BtcClass,EthClass,LtcClass;

	public function userAddressCreation($id)
	{
		
		$btcAddress = $this->create_user_btc($id);
		$ethAddress = $this->create_user_eth($id);
		$ltcAddress = $this->create_user_ltc($id);
		if(isset($btcAddress) && isset($ethAddress) && isset($ltcAddress))
		{
			return 1;
		}
		else
		{
			return 0;
		}
		
	}
}