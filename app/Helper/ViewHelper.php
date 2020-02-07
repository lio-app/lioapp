<?php

use App\PromocodeUsage;

function currency($value = '')
{
	if($value == ""){
		return Setting::get('currency')."0.00";
	} else {
		return Setting::get('currency').$value;
	}
}

function balance($value = '')
{
	if($value == ""){
		return "0.00";
	} else {
		return $value;
	}
}

function img($img){
	if($img == ""){
		return asset('main/avatar.jpg');
	}else if (strpos($img, 'http') !== false) {
        return $img;
    }else{
		return asset('storage/'.$img);
	}
}

function image($img){
	if($img == ""){
		return asset('main/avatar.jpg');
	}else{
		return asset($img);
	}
}

function ico(){
	if(Setting::get('coin_symbol')) {
		return Setting::get('coin_symbol');
	} else {
		return 'Cointronix';
	}
}

function promo_used_count($promo_id)
{
	return PromocodeUsage::where('status','USED')->where('promocode_id',$promo_id)->count();
}