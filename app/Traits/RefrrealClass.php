<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;
use App\Modals\User;
use App\Modals\Wallet;
use App\Modals\Tradepair;
use App\Modals\BonusTransaction;
use App\Modals\Referral;
use App\Modals\ReferralDeposit;
use App\Modals\BonusWallet;
use Illuminate\Support\Str;

trait RefrrealClass
{
	public function limitbuycoin1($buypair,$buyprice,$buyvolume,$uid)
	{    
		$pair = Tradepair::where(['id' => $buypair])->first();
		$c1=$pair->coinone;
		$c2=$pair->cointwo;
		$referral=Referral::where('id',1)->first();
		$commission1=($referral->level1  / 100 ) * ($buyprice * $buyvolume);
		$commission2=($referral->level2  / 100 ) * ($buyprice * $buyvolume);
		$commission3=($referral->level3  / 100 ) * ($buyprice * $buyvolume);
		$bonusamount1=$commission1;
		$bonusamount2=$commission2;
		$bonusamount3=$commission3;
		$user=User::where('id',$uid)->first();
		$details = User::where('client_id',$user->affil_id)->get();
		$gen2=$gen1=$gen3='';
		$g1=$g2=$g3='';
		if(count($details) > 0){
			foreach ($details as $k1 => $v1) {
				$g1[]=$v1;
				$gen1[]=$v1->client_id;  
			}
			if($gen1 != ''){
				foreach ($gen1 as $k2 => $v2) {
					$g1_details=User::where('client_id',$v2)->first();
					$g2_details=User::where('client_id',$g1_details->affil_id)->get();
					if(count($g2_details)>0){
						foreach ($g2_details as $key2 => $value2) {
							$g2[]=$value2;
							$gen2[] =$value2->client_id;                            
						}
					}                        
				}
			}
			if($gen2 != ''){
				foreach ($gen2 as $k3 => $v3) {
					$g2_details=User::where('client_id',$v3)->first();
					$g3_details=User::where('client_id',$g2_details->affil_id)->get();
					if(count($g3_details)>0){
						foreach ($g3_details as $key3 => $value3) {
							$g3[]=$value3;                    
							$gen3[] =$value3->client_id;    
						}
					}                        
				}            
			}
		} 
		if($g1 != null){
			foreach ($g1 as $k1 => $v1) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v1->id;
				$bonus->sender_id = $uid;
				$bonus->type = '2';
				$bonus->currency= $c1;
				$bonus->bonus_amt = $bonusamount1;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v1->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c1 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount1:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c1 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount1:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c1 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount1:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c1 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount1:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c1 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount1:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c1 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount1:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c1 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount1:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount1:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount1:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c1 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount1:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c1 == 'BTC')?$bonusamount1:'0.00';
					$bonus->eth_bonus = ($c1 == 'ETH')?$bonusamount1:'0.00';
					$bonus->thr_bonus = ($c1 == 'THR')?$bonusamount1:'0.00';
					$bonus->the_bonus = ($c1 == 'THE')?$bonusamount1:'0.00';
					$bonus->tch_bonus = ($c1 == 'TCH')?$bonusamount1:'0.00';
					$bonus->thx_bonus = ($c1 == 'THX')?$bonusamount1:'0.00';
					$bonus->usd_bonus = ($c1 == 'USD')?$bonusamount1:'0.00';
					$bonus->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonusamount1:'0.00';
					$bonus->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonusamount1:'0.00';
					$bonus->thex_bonus = ($c1 == 'THEX')?$bonusamount1:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
//generation 2
		if(count($g2) != 0 && $g2 != null){
			foreach ($g2 as $k2 => $v2) {            
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v2->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '2';
				$bonus->currency= $c1;
				$bonus->bonus_amt = $bonusamount2;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v2->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c1 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount2:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c1 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount2:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c1 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount2:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c1 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount2:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c1 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount2:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c1 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount2:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c1 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount2:$bonus_wallet_details->usd_bonus;	
					$bonus_wallet_details->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount2:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount2:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c1 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount2:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c1 == 'BTC')?$bonusamount2:'0.00';
					$bonus->eth_bonus = ($c1 == 'ETH')?$bonusamount2:'0.00';
					$bonus->thr_bonus = ($c1 == 'THR')?$bonusamount2:'0.00';
					$bonus->the_bonus = ($c1 == 'THE')?$bonusamount2:'0.00';
					$bonus->tch_bonus = ($c1 == 'TCH')?$bonusamount2:'0.00';
					$bonus->thx_bonus = ($c1 == 'THX')?$bonusamount2:'0.00';
					$bonus->usd_bonus = ($c1 == 'USD')?$bonusamount2:'0.00';
					$bonus->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonusamount2:'0.00';
					$bonus->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonusamount2:'0.00';
					$bonus->thex_bonus = ($c1 == 'THEX')?$bonusamount2:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
//generation 3
		if(count($g3)>0 && $g3 != null){
			foreach ($g3 as $k3 => $v3) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v3->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '2';
				$bonus->currency= $c1;
				$bonus->bonus_amt = $bonusamount3;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v3->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c1 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount3:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c1 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount3:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c1 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount3:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c1 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount3:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c1 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount3:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c1 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount3:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c1 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount3:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonus_wallet_details->tchtrx+$bonusamount3:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount3:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c1 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount3:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c1 == 'BTC')?$bonusamount3:'0.00';
					$bonus->eth_bonus = ($c1 == 'ETH')?$bonusamount3:'0.00';
					$bonus->thr_bonus = ($c1 == 'THR')?$bonusamount3:'0.00';
					$bonus->the_bonus = ($c1 == 'THE')?$bonusamount3:'0.00';
					$bonus->tch_bonus = ($c1 == 'TCH')?$bonusamount3:'0.00';
					$bonus->thx_bonus = ($c1 == 'THX')?$bonusamount3:'0.00';
					$bonus->usd_bonus = ($c1 == 'USD')?$bonusamount3:'0.00';
					$bonus->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonusamount3:'0.00';
					$bonus->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonusamount3:'0.00';
					$bonus->thex_bonus = ($c1 == 'THEX')?$bonusamount3:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
// end refreal
	}
	public function limitbuycoin2($buypair,$buyprice,$buyvolume,$uid)
	{
		$pair = Tradepair::where(['id' => $buypair])->first();
		$c1=$pair->coinone;
		$c2=$pair->cointwo;
		$referral=Referral::where('id',1)->first();
// echo $referral->level1/100;
		$commission1=($referral->level1  / 100 ) * ($buyprice * $buyvolume);
		$commission2=($referral->level2  / 100 ) * ($buyprice * $buyvolume);
		$commission3=($referral->level3  / 100 ) * ($buyprice * $buyvolume);
		$bonusamount1=$commission1;
		$bonusamount2=$commission2;
		$bonusamount3=$commission3;
		$user=User::where('id',$uid)->first();
		$details = User::where('client_id',$user->affil_id)->get();
		$gen2=$gen1=$gen3='';
		$g1=$g2=$g3='';
		if(count($details) > 0){
			foreach ($details as $k1 => $v1) {
				$g1[]=$v1;
				$gen1[]=$v1->client_id;  
			}
			if($gen1 != ''){
				foreach ($gen1 as $k2 => $v2) {
					$g1_details=User::where('client_id',$v2)->first();
					$g2_details=User::where('client_id',$g1_details->affil_id)->get();
					if(count($g2_details)>0){
						foreach ($g2_details as $key2 => $value2) {
							$g2[]=$value2;
							$gen2[] =$value2->client_id;                            
						}
					}                        
				}
			}
			if($gen2 != ''){
				foreach ($gen2 as $k3 => $v3) {
					$g2_details=User::where('client_id',$v3)->first();
					$g3_details=User::where('client_id',$g2_details->affil_id)->get();
					if(count($g3_details)>0){
						foreach ($g3_details as $key3 => $value3) {
							$g3[]=$value3;                    
							$gen3[] =$value3->client_id;    
						}
					}                        
				}            
			}
		} 
		if($g1 != null){
			foreach ($g1 as $k1 => $v1) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v1->id;
				$bonus->sender_id = $uid;
				$bonus->type = '2';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount1;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v1->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount1:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount1:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount1:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount1:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount1:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount1:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount1:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount1:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount1:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount1:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount1:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount1:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount1:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount1:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount1:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount1:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount1:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount1:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount1:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount1:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
//generation 2
		if(count($g2) != 0 && $g2 != null){
			foreach ($g2 as $k2 => $v2) {            
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v2->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '2';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount2;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v2->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount2:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount2:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount2:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount2:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount2:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount2:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount2:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount2:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount2:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount2:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount2:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount2:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount2:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount2:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount2:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount2:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount2:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount2:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount2:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount2:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
//generation 3
		if(count($g3)>0 && $g3 != null){
			foreach ($g3 as $k3 => $v3) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v3->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '2';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount3;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v3->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount3:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount3:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount3:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount3:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount3:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount3:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount3:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount3:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount3:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount3:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount3:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount3:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount3:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount3:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount3:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount3:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount3:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount3:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount3:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount3:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
// end refreal
	}
	public function limitsellcoin1($sellpair,$sellprice,$sellvolume,$uid)
	{
//referal start buy ku bonus
		$pair = Tradepair::where(['id' => $sellpair])->first();
		$c1=$pair->coinone;
		$c2=$pair->cointwo;
		$referral=Referral::where('id',1)->first();
		$commission1=($referral->level1  / 100 ) * ($sellprice * $sellvolume);
		$commission2=($referral->level2  / 100 ) * ($sellprice * $sellvolume);
		$commission3=($referral->level3  / 100 ) * ($sellprice * $sellvolume);
		$bonusamount1=$commission1;
		$bonusamount2=$commission2;
		$bonusamount3=$commission3;
		$user=User::where('id',$uid)->first();
		$details = User::where('client_id',$user->affil_id)->get();
		$gen2=$gen1=$gen3='';
		$g1=$g2=$g3='';
		if(count($details) > 0){
			foreach ($details as $k1 => $v1) {
				$g1[]=$v1;
				$gen1[]=$v1->client_id;  
			}
			if($gen1 != ''){
				foreach ($gen1 as $k2 => $v2) {
					$g1_details=User::where('client_id',$v2)->first();
					$g2_details=User::where('client_id',$g1_details->affil_id)->get();
					if(count($g2_details)>0){
						foreach ($g2_details as $key2 => $value2) {
							$g2[]=$value2;
							$gen2[] =$value2->client_id;                            
						}
					}                        
				}
			}
			if($gen2 != ''){
				foreach ($gen2 as $k3 => $v3) {
					$g2_details=User::where('client_id',$v3)->first();
					$g3_details=User::where('client_id',$g2_details->affil_id)->get();
					if(count($g3_details)>0){
						foreach ($g3_details as $key3 => $value3) {
							$g3[]=$value3;                    
							$gen3[] =$value3->client_id;    
						}
					}                        
				}            
			}
		}
		if($g1 != null){
			foreach ($g1 as $k1 => $v1) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v1->id;
				$bonus->sender_id = $uid;
				$bonus->type = '3';
				$bonus->currency= $c1;
				$bonus->bonus_amt = $bonusamount1;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v1->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c1 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount1:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c1 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount1:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c1 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount1:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c1 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount1:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c1 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount1:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c1 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount1:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c1 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount1:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount1:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount1:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c1 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount1:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();

				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c1 == 'BTC')?$bonusamount1:'0.00';
					$bonus->eth_bonus = ($c1 == 'ETH')?$bonusamount1:'0.00';
					$bonus->thr_bonus = ($c1 == 'THR')?$bonusamount1:'0.00';
					$bonus->the_bonus = ($c1 == 'THE')?$bonusamount1:'0.00';
					$bonus->tch_bonus = ($c1 == 'TCH')?$bonusamount1:'0.00';
					$bonus->thx_bonus = ($c1 == 'THX')?$bonusamount1:'0.00';
					$bonus->usd_bonus = ($c1 == 'USD')?$bonusamount1:'0.00';
					$bonus->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonusamount1:'0.00';
					$bonus->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonusamount1:'0.00';
					$bonus->thex_bonus = ($c1 == 'THEX')?$bonusamount1:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
//generation 2
		if(count($g2) != 0 && $g2 != null){
			foreach ($g2 as $k2 => $v2) {            
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v2->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '3';
				$bonus->currency= $c1;
				$bonus->bonus_amt = $bonusamount2;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v2->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c1 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount2:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c1 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount2:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c1 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount2:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c1 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount2:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c1 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount2:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c1 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount2:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c1 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount2:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount2:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount2:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c1 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount2:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c1 == 'BTC')?$bonusamount2:'0.00';
					$bonus->eth_bonus = ($c1 == 'ETH')?$bonusamount2:'0.00';
					$bonus->thr_bonus = ($c1 == 'THR')?$bonusamount2:'0.00';
					$bonus->the_bonus = ($c1 == 'THE')?$bonusamount2:'0.00';
					$bonus->tch_bonus = ($c1 == 'TCH')?$bonusamount2:'0.00';
					$bonus->thx_bonus = ($c1 == 'THX')?$bonusamount2:'0.00';
					$bonus->usd_bonus = ($c1 == 'USD')?$bonusamount2:'0.00';
					$bonus->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonusamount2:'0.00';
					$bonus->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonusamount2:'0.00';
					$bonus->thex_bonus = ($c1 == 'THEX')?$bonusamount2:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
//generation 3

		if(count($g3)>0 && $g3 != null){
			foreach ($g3 as $k3 => $v3) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v3->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '3';
				$bonus->currency= $c1;
				$bonus->bonus_amt = $bonusamount3;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v3->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();

				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c1 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount3:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c1 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount3:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c1 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount3:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c1 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount3:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c1 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount3:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c1 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount3:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c1 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount3:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount3:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount3:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c1 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount3:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c1 == 'BTC')?$bonusamount3:'0.00';
					$bonus->eth_bonus = ($c1 == 'ETH')?$bonusamount3:'0.00';
					$bonus->thr_bonus = ($c1 == 'THR')?$bonusamount3:'0.00';
					$bonus->the_bonus = ($c1 == 'THE')?$bonusamount3:'0.00';
					$bonus->tch_bonus = ($c1 == 'TCH')?$bonusamount3:'0.00';
					$bonus->thx_bonus = ($c1 == 'THX')?$bonusamount3:'0.00';
					$bonus->usd_bonus = ($c1 == 'USD')?$bonusamount3:'0.00';
					$bonus->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonusamount3:'0.00';
					$bonus->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonusamount3:'0.00';
					$bonus->thex_bonus = ($c1 == 'THEX')?$bonusamount3:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
// end refreal   
	}

	public function limitsellcoin2($sellpair,$sellprice,$sellvolume,$uid)
	{
//referal start buy ku bonus
		$pair = Tradepair::where(['id' => $sellpair])->first();
		$c1=$pair->coinone;
		$c2=$pair->cointwo;
		$referral=Referral::where('id',1)->first();
		$commission1=($referral->level1  / 100 ) * ($sellprice * $sellvolume);
		$commission2=($referral->level2  / 100 ) * ($sellprice * $sellvolume);
		$commission3=($referral->level3  / 100 ) * ($sellprice * $sellvolume);
		$bonusamount1=$commission1;
		$bonusamount2=$commission2;
		$bonusamount3=$commission3;
		$user=User::where('id',$uid)->first();
		$details = User::where('client_id',$user->affil_id)->get();
		$gen2=$gen1=$gen3='';
		$g1=$g2=$g3='';
		if(count($details) > 0){
			foreach ($details as $k1 => $v1) {
				$g1[]=$v1;
				$gen1[]=$v1->client_id;  
			}
			if($gen1 != ''){
				foreach ($gen1 as $k2 => $v2) {
					$g1_details=User::where('client_id',$v2)->first();
					$g2_details=User::where('client_id',$g1_details->affil_id)->get();
					if(count($g2_details)>0){
						foreach ($g2_details as $key2 => $value2) {
							$g2[]=$value2;
							$gen2[] =$value2->client_id;                            
						}
					}                        
				}
			}
			if($gen2 != ''){
				foreach ($gen2 as $k3 => $v3) {
					$g2_details=User::where('client_id',$v3)->first();
					$g3_details=User::where('client_id',$g2_details->affil_id)->get();
					if(count($g3_details)>0){
						foreach ($g3_details as $key3 => $value3) {
							$g3[]=$value3;                    
							$gen3[] =$value3->client_id;    
						}
					}                        
				}            
			}
		} 

		if($g1 != null){
			foreach ($g1 as $k1 => $v1) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v1->id;
				$bonus->sender_id = $uid;
				$bonus->type = '3';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount1;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v1->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount1:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount1:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount1:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount1:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount1:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount1:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount1:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount1:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount1:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount1:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount1:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount1:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount1:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount1:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount1:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount1:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount1:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount1:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount1:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount1:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
//generation 2
		if(count($g2) != 0 && $g2 != null){
			foreach ($g2 as $k2 => $v2) {            
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v2->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '3';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount2;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v2->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount2:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount2:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount2:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount2:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount2:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount2:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount2:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount2:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount2:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount2:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount2:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount2:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount2:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount2:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount2:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount2:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount2:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount2:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount2:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount2:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
//generation 3
		if(count($g3)>0 && $g3 != null){
			foreach ($g3 as $k3 => $v3) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v3->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '3';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount3;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v3->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount3:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount3:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount3:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount3:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount3:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount3:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount3:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount3:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount3:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount3:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount3:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount3:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount3:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount3:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount3:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount3:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount3:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount3:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount3:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount3:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
// end refreal   
	}
//market
	public function marketbuycoin1($buypair,$buyprice,$buyvolume)
	{
		$pair = Tradepair::where(['id' => $buypair])->first();
		$c1=$pair->coinone;
		$c2=$pair->cointwo;
		$referral=Referral::where('id',1)->first();
		$commission1=($referral->level1  / 100 ) * ($buyprice * $buyvolume);
		$commission2=($referral->level2  / 100 ) * ($buyprice * $buyvolume);
		$commission3=($referral->level3  / 100 ) * ($buyprice * $buyvolume);
		$bonusamount1=$commission1;
		$bonusamount2=$commission2;
		$bonusamount3=$commission3;
		$details = User::where('client_id',Auth::user()->affil_id)->get();
		$gen2=$gen1=$gen3='';
		$g1=$g2=$g3='';
		if(count($details) > 0){
			foreach ($details as $k1 => $v1) {
				$g1[]=$v1;
				$gen1[]=$v1->client_id;  
			}
			if($gen1 != ''){
				foreach ($gen1 as $k2 => $v2) {
					$g1_details=User::where('client_id',$v2)->first();
					$g2_details=User::where('client_id',$g1_details->affil_id)->get();
					if(count($g2_details)>0){
						foreach ($g2_details as $key2 => $value2) {
							$g2[]=$value2;
							$gen2[] =$value2->client_id;                            
						}
					}                        
				}
			}
			if($gen2 != ''){
				foreach ($gen2 as $k3 => $v3) {
					$g2_details=User::where('client_id',$v3)->first();
					$g3_details=User::where('client_id',$g2_details->affil_id)->get();
					if(count($g3_details)>0){
						foreach ($g3_details as $key3 => $value3) {
							$g3[]=$value3;                    
							$gen3[] =$value3->client_id;    
						}
					}                        
				}            
			}
		} 
		if($g1 != null){
			foreach ($g1 as $k1 => $v1) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v1->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '1';
				$bonus->currency= $c1;
				$bonus->bonus_amt = $bonusamount1;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v1->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c1 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount1:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c1 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount1:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c1 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount1:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c1 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount1:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c1 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount1:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c1 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount1:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c1 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount1:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount1:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount1:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c1 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount1:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c1 == 'BTC')?$bonusamount1:'0.00';
					$bonus->eth_bonus = ($c1 == 'ETH')?$bonusamount1:'0.00';
					$bonus->thr_bonus = ($c1 == 'THR')?$bonusamount1:'0.00';
					$bonus->the_bonus = ($c1 == 'THE')?$bonusamount1:'0.00';
					$bonus->tch_bonus = ($c1 == 'TCH')?$bonusamount1:'0.00';
					$bonus->thx_bonus = ($c1 == 'THX')?$bonusamount1:'0.00';
					$bonus->usd_bonus = ($c1 == 'USD')?$bonusamount1:'0.00';
					$bonus->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonusamount1:'0.00';
					$bonus->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonusamount1:'0.00';
					$bonus->thex_bonus = ($c1 == 'THEX')?$bonusamount1:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
//generation 2
		if(count($g2) != 0 && $g2 != null){
			foreach ($g2 as $k2 => $v2) {            
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v2->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '1';
				$bonus->currency= $c1;
				$bonus->bonus_amt = $bonusamount2;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v2->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c1 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount2:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c1 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount2:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c1 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount2:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c1 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount2:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c1 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount2:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c1 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount2:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c1 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount2:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount2:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount2:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c1 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount2:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();

				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c1 == 'BTC')?$bonusamount2:'0.00';
					$bonus->eth_bonus = ($c1 == 'ETH')?$bonusamount2:'0.00';
					$bonus->thr_bonus = ($c1 == 'THR')?$bonusamount2:'0.00';
					$bonus->the_bonus = ($c1 == 'THE')?$bonusamount2:'0.00';
					$bonus->tch_bonus = ($c1 == 'TCH')?$bonusamount2:'0.00';
					$bonus->thx_bonus = ($c1 == 'THX')?$bonusamount2:'0.00';
					$bonus->usd_bonus = ($c1 == 'USD')?$bonusamount2:'0.00';
					$bonus->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonusamount2:'0.00';
					$bonus->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonusamount2:'0.00';
					$bonus->thex_bonus = ($c1 == 'THEX')?$bonusamount2:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
//generation 3

		if(count($g3)>0 && $g3 != null){

			foreach ($g3 as $k3 => $v3) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v3->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '1';
				$bonus->currency= $c1;
				$bonus->bonus_amt = $bonusamount3;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v3->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();

				if(isset($bonus_wallet_details)){

					$bonus_wallet_details->btc_bonus = ($c1 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount3:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c1 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount3:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c1 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount3:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c1 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount3:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c1 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount3:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c1 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount3:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c1 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount3:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount3:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount3:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c1 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount3:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();

				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c1 == 'BTC')?$bonusamount3:'0.00';
					$bonus->eth_bonus = ($c1 == 'ETH')?$bonusamount3:'0.00';
					$bonus->thr_bonus = ($c1 == 'THR')?$bonusamount3:'0.00';
					$bonus->the_bonus = ($c1 == 'THE')?$bonusamount3:'0.00';
					$bonus->tch_bonus = ($c1 == 'TCH')?$bonusamount3:'0.00';
					$bonus->thx_bonus = ($c1 == 'THX')?$bonusamount3:'0.00';
					$bonus->usd_bonus = ($c1 == 'USD')?$bonusamount3:'0.00';
					$bonus->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonusamount3:'0.00';
					$bonus->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonusamount3:'0.00';
					$bonus->thex_bonus = ($c1 == 'THEX')?$bonusamount3:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
// end refreal
	}

	public function marketbuycoin2($buypair,$buyprice,$buyvolume)
	{
		$pair = Tradepair::where(['id' => $buypair])->first();

		$c1=$pair->coinone;
		$c2=$pair->cointwo;
		$referral=Referral::where('id',1)->first();
		$commission1=($referral->level1  / 100 ) * ($buyprice * $buyvolume);
		$commission2=($referral->level2  / 100 ) * ($buyprice * $buyvolume);
		$commission3=($referral->level3  / 100 ) * ($buyprice * $buyvolume);
		$bonusamount1=$commission1;
		$bonusamount2=$commission2;
		$bonusamount3=$commission3;

		$details = User::where('client_id',Auth::user()->affil_id)->get();

		$gen2=$gen1=$gen3='';
		$g1=$g2=$g3='';

		if(count($details) > 0){
			foreach ($details as $k1 => $v1) {
				$g1[]=$v1;
				$gen1[]=$v1->client_id;  
			}
			if($gen1 != ''){
				foreach ($gen1 as $k2 => $v2) {
					$g1_details=User::where('client_id',$v2)->first();
					$g2_details=User::where('client_id',$g1_details->affil_id)->get();
					if(count($g2_details)>0){
						foreach ($g2_details as $key2 => $value2) {
							$g2[]=$value2;
							$gen2[] =$value2->client_id;                            
						}
					}                        
				}
			}
			if($gen2 != ''){
				foreach ($gen2 as $k3 => $v3) {
					$g2_details=User::where('client_id',$v3)->first();
					$g3_details=User::where('client_id',$g2_details->affil_id)->get();
					if(count($g3_details)>0){
						foreach ($g3_details as $key3 => $value3) {
							$g3[]=$value3;                    
							$gen3[] =$value3->client_id;    
						}
					}                        
				}            
			}
		} 

		if($g1 != null){

			foreach ($g1 as $k1 => $v1) {

//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v1->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '1';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount1;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v1->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();

				if(isset($bonus_wallet_details)){

					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount1:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount1:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount1:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount1:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount1:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount1:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount1:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount1:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount1:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount1:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();

				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount1:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount1:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount1:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount1:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount1:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount1:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount1:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount1:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount1:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount1:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}

//generation 2

		if(count($g2) != 0 && $g2 != null){
			foreach ($g2 as $k2 => $v2) {            
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v2->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '1';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount2;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction

				$client_id=User::where('client_id',$v2->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();

				if(isset($bonus_wallet_details)){

					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount2:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount2:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount2:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount2:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount2:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount2:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount2:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount2:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount2:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount2:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();

				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount2:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount2:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount2:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount2:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount2:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount2:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount2:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount2:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount2:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount2:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}

//generation 3

		if(count($g3)>0 && $g3 != null){
			foreach ($g3 as $k3 => $v3) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v3->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '1';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount3;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v3->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount3:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount3:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount3:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount3:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount3:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount3:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount3:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount3:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount3:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount3:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();

				}else{

//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount3:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount3:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount3:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount3:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount3:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount3:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount3:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount3:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount3:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount3:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
// end refreal
	}

	public function marketsellcoin1($sellpair,$sellprice,$sellvolume)
	{
//referal start buy ku bonus
		$pair = Tradepair::where(['id' => $sellpair])->first();
		$c1=$pair->coinone;
		$c2=$pair->cointwo;
		$referral=Referral::where('id',1)->first();
		$commission1=($referral->level1  / 100 ) * ($sellprice * $sellvolume);
		$commission2=($referral->level2  / 100 ) * ($sellprice * $sellvolume);
		$commission3=($referral->level3  / 100 ) * ($sellprice * $sellvolume);
		$bonusamount1=$commission1;
		$bonusamount2=$commission2;
		$bonusamount3=$commission3;
		$details = User::where('client_id',Auth::user()->affil_id)->get();
		$gen2=$gen1=$gen3='';
		$g1=$g2=$g3='';
		if(count($details) > 0){
			foreach ($details as $k1 => $v1) {
				$g1[]=$v1;
				$gen1[]=$v1->client_id;  
			}
			if($gen1 != ''){
				foreach ($gen1 as $k2 => $v2) {
					$g1_details=User::where('client_id',$v2)->first();
					$g2_details=User::where('client_id',$g1_details->affil_id)->get();
					if(count($g2_details)>0){
						foreach ($g2_details as $key2 => $value2) {
							$g2[]=$value2;
							$gen2[] =$value2->client_id;                            
						}
					}                        
				}
			}
			if($gen2 != ''){
				foreach ($gen2 as $k3 => $v3) {
					$g2_details=User::where('client_id',$v3)->first();
					$g3_details=User::where('client_id',$g2_details->affil_id)->get();
					if(count($g3_details)>0){
						foreach ($g3_details as $key3 => $value3) {
							$g3[]=$value3;                    
							$gen3[] =$value3->client_id;    
						}
					}                        
				}            
			}
		} 

		if($g1 != null){
			foreach ($g1 as $k1 => $v1) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v1->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '4';
				$bonus->currency= $c1;
				$bonus->bonus_amt = $bonusamount1;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v1->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c1 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount1:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c1 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount1:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c1 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount1:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c1 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount1:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c1 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount1:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c1 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount1:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c1 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount1:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount1:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount1:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c1 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount1:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c1 == 'BTC')?$bonusamount1:'0.00';
					$bonus->eth_bonus = ($c1 == 'ETH')?$bonusamount1:'0.00';
					$bonus->thr_bonus = ($c1 == 'THR')?$bonusamount1:'0.00';
					$bonus->the_bonus = ($c1 == 'THE')?$bonusamount1:'0.00';
					$bonus->tch_bonus = ($c1 == 'TCH')?$bonusamount1:'0.00';
					$bonus->thx_bonus = ($c1 == 'THX')?$bonusamount1:'0.00';
					$bonus->usd_bonus = ($c1 == 'USD')?$bonusamount1:'0.00';
					$bonus->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonusamount1:'0.00';
					$bonus->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonusamount1:'0.00';
					$bonus->thex_bonus = ($c1 == 'THEX')?$bonusamount1:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
//generation 2
		if(count($g2) != 0 && $g2 != null){
			foreach ($g2 as $k2 => $v2) {            
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v2->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '4';
				$bonus->currency= $c1;
				$bonus->bonus_amt = $bonusamount2;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v2->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c1 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount2:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c1 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount2:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c1 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount2:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c1 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount2:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c1 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount2:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c1 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount2:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c1 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount2:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount2:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount2:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c1 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount2:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c1 == 'BTC')?$bonusamount2:'0.00';
					$bonus->eth_bonus = ($c1 == 'ETH')?$bonusamount2:'0.00';
					$bonus->thr_bonus = ($c1 == 'THR')?$bonusamount2:'0.00';
					$bonus->the_bonus = ($c1 == 'THE')?$bonusamount2:'0.00';
					$bonus->tch_bonus = ($c1 == 'TCH')?$bonusamount2:'0.00';
					$bonus->thx_bonus = ($c1 == 'THX')?$bonusamount2:'0.00';
					$bonus->usd_bonus = ($c1 == 'USD')?$bonusamount2:'0.00';
					$bonus->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonusamount2:'0.00';
					$bonus->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonusamount2:'0.00';
					$bonus->thex_bonus = ($c1 == 'THEX')?$bonusamount2:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
//generation 3
		if(count($g3)>0 && $g3 != null){
			foreach ($g3 as $k3 => $v3) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v3->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '4';
				$bonus->currency= $c1;
				$bonus->bonus_amt = $bonusamount3;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v3->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){

					$bonus_wallet_details->btc_bonus = ($c1 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount3:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c1 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount3:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c1 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount3:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c1 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount3:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c1 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount3:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c1 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount3:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c1 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount3:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount3:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount3:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c1 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount3:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c1 == 'BTC')?$bonusamount3:'0.00';
					$bonus->eth_bonus = ($c1 == 'ETH')?$bonusamount3:'0.00';
					$bonus->thr_bonus = ($c1 == 'THR')?$bonusamount3:'0.00';
					$bonus->the_bonus = ($c1 == 'THE')?$bonusamount3:'0.00';
					$bonus->tch_bonus = ($c1 == 'TCH')?$bonusamount3:'0.00';
					$bonus->thx_bonus = ($c1 == 'THX')?$bonusamount3:'0.00';
					$bonus->usd_bonus = ($c1 == 'USD')?$bonusamount3:'0.00';
					$bonus->tchtrx_bonus = ($c1 == 'TCHTRX')?$bonusamount3:'0.00';
					$bonus->thrwaves_bonus = ($c1 == 'THRWAVES')?$bonusamount3:'0.00';
					$bonus->thex_bonus = ($c1 == 'THEX')?$bonusamount3:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
// end refreal   
	}
	public function marketsellcoin2($sellpair,$sellprice,$sellvolume)
	{
//referal start buy ku bonus
		$pair = Tradepair::where(['id' => $sellpair])->first();
		$c1=$pair->coinone;
		$c2=$pair->cointwo;
		$referral=Referral::where('id',1)->first();
		$commission1=($referral->level1  / 100 ) * ($sellprice * $sellvolume);
		$commission2=($referral->level2  / 100 ) * ($sellprice * $sellvolume);
		$commission3=($referral->level3  / 100 ) * ($sellprice * $sellvolume);
		$bonusamount1=$commission1;
		$bonusamount2=$commission2;
		$bonusamount3=$commission3;
		$details = User::where('client_id',Auth::user()->affil_id)->get();
		$gen2=$gen1=$gen3='';
		$g1=$g2=$g3='';
		if(count($details) > 0){
			foreach ($details as $k1 => $v1) {
				$g1[]=$v1;  
				$gen1[]=$v1->client_id;  
			}
			if($gen1 != ''){
				foreach ($gen1 as $k2 => $v2) {
					$g1_details=User::where('client_id',$v2)->first();
					$g2_details=User::where('client_id',$g1_details->affil_id)->get();
					if(count($g2_details)>0){
						foreach ($g2_details as $key2 => $value2) {
							$g2[]=$value2;
							$gen2[] =$value2->client_id;                            
						}
					}                        
				}
			}
			if($gen2 != ''){
				foreach ($gen2 as $k3 => $v3) {
					$g2_details=User::where('client_id',$v3)->first();
					$g3_details=User::where('client_id',$g2_details->affil_id)->get();
					if(count($g3_details)>0){
						foreach ($g3_details as $key3 => $value3) {
							$g3[]=$value3;                    
							$gen3[] =$value3->client_id;    
						}
					}                        
				}            
			}
		}

		if($g1 != null){
			foreach ($g1 as $k1 => $v1) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v1->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '4';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount1;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v1->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount1:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount1:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount1:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount1:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount1:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount1:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount1:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount1:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount1:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount1:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount1:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount1:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount1:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount1:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount1:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount1:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount1:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount1:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount1:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount1:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}

//generation 2

		if(count($g2) != 0 && $g2 != null){
			foreach ($g2 as $k2 => $v2) {            
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v2->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '4';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount2;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v2->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount2:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount2:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount2:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount2:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount2:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount2:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount2:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount2:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount2:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount2:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount2:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount2:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount2:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount2:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount2:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount2:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount2:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount2:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount2:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount2:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}

//generation 3

		if(count($g3)>0 && $g3 != null){
			foreach ($g3 as $k3 => $v3) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->user_id   = $v3->id;
				$bonus->sender_id = Auth::user()->id;
				$bonus->type = '4';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount3;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::where('client_id',$v3->client_id)->first();
				$bonus_wallet_details=BonusWallet::where('user_id',$client_id->id)->first();

				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount3:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount3:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount3:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount3:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount3:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount3:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount3:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount3:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount3:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount3:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount3:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount3:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount3:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount3:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount3:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount3:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount3:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount3:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount3:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THORECOIN')?$bonusamount3:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount3:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}
// end refreal   
	}

// usd deposit

	public function depositrefrrels($amount,$uid,$currency)
	{
		$refreal_deposit_commission=ReferralDeposit::on('mysqluser')->where('id',1)->first();
		$commission1=($refreal_deposit_commission->level1  / 100 ) * ($amount);
		$commission2=($refreal_deposit_commission->level2  / 100 ) * ($amount);
		$commission3=($refreal_deposit_commission->level3  / 100 ) * ($amount);
		$bonusamount1=$commission1;
		$bonusamount2=$commission2;
		$bonusamount3=$commission3;
		
		$bonus_amount=bcdiv($amount,2,8);
		$c2=$currency;
		$user_get=User::on('mysqluser')->where('id',$uid)->first();
		$details = User::on('mysqluser')->where('client_id',$user_get->affil_id)->get();
		$gen2=$gen1=$gen3='';
		$g1=$g2=$g3='';
		if(count($details) > 0){
			foreach ($details as $k1 => $v1) {
				$g1[]=$v1;  
				$gen1[]=$v1->client_id;  
			}
			if($gen1 != ''){
				foreach ($gen1 as $k2 => $v2) {
					$g1_details=User::on('mysqluser')->where('client_id',$v2)->first();
					$g2_details=User::on('mysqluser')->where('client_id',$g1_details->affil_id)->get();
					if(count($g2_details)>0){
						foreach ($g2_details as $key2 => $value2) {
							$g2[]=$value2;
							$gen2[] =$value2->client_id;                            
						}
					}                        
				}
			}
			if($gen2 != ''){
				foreach ($gen2 as $k3 => $v3) {
					$g2_details=User::on('mysqluser')->where('client_id',$v3)->first();
					$g3_details=User::on('mysqluser')->where('client_id',$g2_details->affil_id)->get();
					if(count($g3_details)>0){
						foreach ($g3_details as $key3 => $value3) {
							$g3[]=$value3;                    
							$gen3[] =$value3->client_id;    
						}
					}                        
				}            
			}
		} 

		if($g1 != null){
			foreach ($g1 as $k1 => $v1) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->setConnection('mysqluser');
				$bonus->user_id   = $v1->id;
				$bonus->sender_id = $uid;
				$bonus->type = '5';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount1;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::on('mysqluser')->where('client_id',$v1->client_id)->first();
				$bonus_wallet_details=BonusWallet::on('mysqluser')->where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount1:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount1:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount1:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount1:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount1:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount1:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount1:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount1:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount1:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THORECOIN')?$bonus_wallet_details->thrwaves_bonus+$bonusamount1:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount1:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->btt_bonus = ($c2 == 'BTT')?$bonus_wallet_details->btt_bonus+$bonusamount1:$bonus_wallet_details->btt_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet   
					$bonus = new BonusWallet;
					$bonus->setConnection('mysqluser');
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount1:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount1:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount1:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount1:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount1:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount1:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount1:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount1:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount1:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THORECOIN')?$bonusamount1:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount1:'0.00';
					$bonus->btt_bonus = ($c2 == 'BTT')?$bonusamount1:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}

//generation 2
		if(count($g2) != 0 && $g2 != null){
			foreach ($g2 as $k2 => $v2) {            
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->setConnection('mysqluser');
				$bonus->user_id   = $v2->id;
				$bonus->sender_id = $uid;
				$bonus->type = '5';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount2;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::on('mysqluser')->where('client_id',$v2->client_id)->first();
				$bonus_wallet_details=BonusWallet::on('mysqluser')->where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount2:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount2:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount2:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount2:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount2:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount2:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount2:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount2:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount2:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THORECOIN')?$bonus_wallet_details->thrwaves_bonus+$bonusamount2:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount2:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->btt_bonus = ($c2 == 'BTT')?$bonus_wallet_details->btt_bonus+$bonusamount2:$bonus_wallet_details->btt_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->setConnection('mysqluser');
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount2:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount2:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount2:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount2:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount2:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount2:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount2:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount2:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount2:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THORECOIN')?$bonusamount2:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount2:'0.00';
					$bonus->btt_bonus = ($c2 == 'BTT')?$bonusamount2:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}

//generation 3

		if(count($g3)>0 && $g3 != null){
			foreach ($g3 as $k3 => $v3) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->setConnection('mysqluser');
				$bonus->user_id   = $v3->id;
				$bonus->sender_id = $uid;
				$bonus->type = '5';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount3;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::on('mysqluser')->where('client_id',$v3->client_id)->first();
				$bonus_wallet_details=BonusWallet::on('mysqluser')->where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount3:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount3:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount3:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount3:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount3:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount3:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount3:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount3:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount3:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THORECOIN')?$bonus_wallet_details->thrwaves_bonus+$bonusamount3:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount3:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->btt_bonus = ($c2 == 'BTT')?$bonus_wallet_details->btt_bonus+$bonusamount3:$bonus_wallet_details->btt_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->setConnection('mysqluser');
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount3:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount3:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount3:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount3:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount3:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount3:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount3:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount3:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount3:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THORECOIN')?$bonusamount3:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount3:'0.00';
					$bonus->btt_bonus = ($c2 == 'BTT')?$bonusamount3:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}       
	}
	public function depositrefrrel($amount,$uid)
	{
		$refreal_deposit_commission=ReferralDeposit::on('mysqluser')->where('id',1)->first();
		$commission1=($refreal_deposit_commission->level1  / 100 ) * ($amount);
		$commission2=($refreal_deposit_commission->level2  / 100 ) * ($amount);
		$commission3=($refreal_deposit_commission->level3  / 100 ) * ($amount);
		$bonusamount1=$commission1;
		$bonusamount2=$commission2;
		$bonusamount3=$commission3;
		
		$bonus_amount=bcdiv($amount,2,8);
		$c2=$currency;
		$user_get=User::on('mysqluser')->where('id',$uid)->first();
		$details = User::on('mysqluser')->where('client_id',$user_get->affil_id)->get();
		$gen2=$gen1=$gen3='';
		$g1=$g2=$g3='';
		if(count($details) > 0){
			foreach ($details as $k1 => $v1) {
				$g1[]=$v1;  
				$gen1[]=$v1->client_id;  
			}
			if($gen1 != ''){
				foreach ($gen1 as $k2 => $v2) {
					$g1_details=User::on('mysqluser')->where('client_id',$v2)->first();
					$g2_details=User::on('mysqluser')->where('client_id',$g1_details->affil_id)->get();
					if(count($g2_details)>0){
						foreach ($g2_details as $key2 => $value2) {
							$g2[]=$value2;
							$gen2[] =$value2->client_id;                            
						}
					}                        
				}
			}
			if($gen2 != ''){
				foreach ($gen2 as $k3 => $v3) {
					$g2_details=User::on('mysqluser')->where('client_id',$v3)->first();
					$g3_details=User::on('mysqluser')->where('client_id',$g2_details->affil_id)->get();
					if(count($g3_details)>0){
						foreach ($g3_details as $key3 => $value3) {
							$g3[]=$value3;                    
							$gen3[] =$value3->client_id;    
						}
					}                        
				}            
			}
		} 

		if($g1 != null){
			foreach ($g1 as $k1 => $v1) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->setConnection('mysqluser');
				$bonus->user_id   = $v1->id;
				$bonus->sender_id = $uid;
				$bonus->type = '5';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount1;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::on('mysqluser')->where('client_id',$v1->client_id)->first();
				$bonus_wallet_details=BonusWallet::on('mysqluser')->where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount1:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount1:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount1:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount1:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount1:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount1:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount1:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount1:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount1:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THORECOIN')?$bonus_wallet_details->thrwaves_bonus+$bonusamount1:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount1:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->btt_bonus = ($c2 == 'BTT')?$bonus_wallet_details->btt_bonus+$bonusamount1:$bonus_wallet_details->btt_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet   
					$bonus = new BonusWallet;
					$bonus->setConnection('mysqluser');
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount1:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount1:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount1:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount1:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount1:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount1:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount1:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount1:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount1:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THORECOIN')?$bonusamount1:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount1:'0.00';
					$bonus->btt_bonus = ($c2 == 'BTT')?$bonusamount1:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}

//generation 2
		if(count($g2) != 0 && $g2 != null){
			foreach ($g2 as $k2 => $v2) {            
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->setConnection('mysqluser');
				$bonus->user_id   = $v2->id;
				$bonus->sender_id = $uid;
				$bonus->type = '5';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount2;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::on('mysqluser')->where('client_id',$v2->client_id)->first();
				$bonus_wallet_details=BonusWallet::on('mysqluser')->where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount2:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount2:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount2:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount2:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount2:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount2:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount2:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount2:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount2:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THORECOIN')?$bonus_wallet_details->thrwaves_bonus+$bonusamount2:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount2:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->btt_bonus = ($c2 == 'BTT')?$bonus_wallet_details->btt_bonus+$bonusamount2:$bonus_wallet_details->btt_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->setConnection('mysqluser');
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount2:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount2:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount2:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount2:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount2:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount2:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount2:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount2:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount2:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THORECOIN')?$bonusamount2:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount2:'0.00';
					$bonus->btt_bonus = ($c2 == 'BTT')?$bonusamount2:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}

//generation 3

		if(count($g3)>0 && $g3 != null){
			foreach ($g3 as $k3 => $v3) {
//bonus transaction
				$bonus = new BonusTransaction;
				$bonus->setConnection('mysqluser');
				$bonus->user_id   = $v3->id;
				$bonus->sender_id = $uid;
				$bonus->type = '5';
				$bonus->currency= $c2;
				$bonus->bonus_amt = $bonusamount3;
				$bonus->txn_id = Str::random(12);
				$bonus->save();
//end bonus transaction
				$client_id=User::on('mysqluser')->where('client_id',$v3->client_id)->first();
				$bonus_wallet_details=BonusWallet::on('mysqluser')->where('user_id',$client_id->id)->first();
				if(isset($bonus_wallet_details)){
					$bonus_wallet_details->btc_bonus = ($c2 == 'BTC')?$bonus_wallet_details->btc_bonus+$bonusamount3:$bonus_wallet_details->btc_bonus;
					$bonus_wallet_details->eth_bonus = ($c2 == 'ETH')?$bonus_wallet_details->eth_bonus+$bonusamount3:$bonus_wallet_details->eth_bonus;
					$bonus_wallet_details->thr_bonus = ($c2 == 'THR')?$bonus_wallet_details->thr_bonus+$bonusamount3:$bonus_wallet_details->thr_bonus;
					$bonus_wallet_details->the_bonus = ($c2 == 'THE')?$bonus_wallet_details->the_bonus+$bonusamount3:$bonus_wallet_details->the_bonus;
					$bonus_wallet_details->tch_bonus = ($c2 == 'TCH')?$bonus_wallet_details->tch_bonus+$bonusamount3:$bonus_wallet_details->tch_bonus;
					$bonus_wallet_details->thx_bonus = ($c2 == 'THX')?$bonus_wallet_details->thx_bonus+$bonusamount3:$bonus_wallet_details->thx_bonus;
					$bonus_wallet_details->usd_bonus = ($c2 == 'USD')?$bonus_wallet_details->usd_bonus+$bonusamount3:$bonus_wallet_details->usd_bonus;
					$bonus_wallet_details->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonus_wallet_details->tchtrx_bonus+$bonusamount3:$bonus_wallet_details->tchtrx_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonus_wallet_details->thrwaves_bonus+$bonusamount3:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thrwaves_bonus = ($c2 == 'THORECOIN')?$bonus_wallet_details->thrwaves_bonus+$bonusamount3:$bonus_wallet_details->thrwaves_bonus;
					$bonus_wallet_details->thex_bonus = ($c2 == 'THEX')?$bonus_wallet_details->thex_bonus+$bonusamount3:$bonus_wallet_details->thex_bonus;
					$bonus_wallet_details->btt_bonus = ($c2 == 'BTT')?$bonus_wallet_details->btt_bonus+$bonusamount3:$bonus_wallet_details->btt_bonus;
					$bonus_wallet_details->save();
				}else{
//bonus wallet
					$bonus = new BonusWallet;
					$bonus->setConnection('mysqluser');
					$bonus->user_id   = $client_id->id;
					$bonus->btc_bonus = ($c2 == 'BTC')?$bonusamount3:'0.00';
					$bonus->eth_bonus = ($c2 == 'ETH')?$bonusamount3:'0.00';
					$bonus->thr_bonus = ($c2 == 'THR')?$bonusamount3:'0.00';
					$bonus->the_bonus = ($c2 == 'THE')?$bonusamount3:'0.00';
					$bonus->tch_bonus = ($c2 == 'TCH')?$bonusamount3:'0.00';
					$bonus->thx_bonus = ($c2 == 'THX')?$bonusamount3:'0.00';
					$bonus->usd_bonus = ($c2 == 'USD')?$bonusamount3:'0.00';
					$bonus->tchtrx_bonus = ($c2 == 'TCHTRX')?$bonusamount3:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THRWAVES')?$bonusamount3:'0.00';
					$bonus->thrwaves_bonus = ($c2 == 'THORECOIN')?$bonusamount3:'0.00';
					$bonus->thex_bonus = ($c2 == 'THEX')?$bonusamount3:'0.00';
					$bonus->btt_bonus = ($c2 == 'BTT')?$bonusamount3:'0.00';
					$bonus->save();
//end bonus wallet
				}            
			}
		}       
	}
}

?>