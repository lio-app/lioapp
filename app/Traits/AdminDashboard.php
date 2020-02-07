<?php 
namespace App\Traits;

use App\Modals\User;
use App\Modals\Kyc;
use App\Modals\Ticket;
use Carbon\Carbon;
use App\Modals\Buytrade;
use App\Modals\Tradepair;
use App\Modals\Selltrade;
use App\Modals\Completedtrade;

trait AdminDashboard {

    function getUsercount()
    {
        $count = User::on('mysqluser')->count();
        return $count;
    }

    function getAdminApprovedUsercount()
    {
        $count = User::on('mysqluser')->where('admin_status',1)->count();
        return $count;
    }

    function getKycCount()
    {
        $kycCount = Kyc::on('mysqluser')->count();
        return $kycCount;  
    }

    function getTicketsCount()
    {
        $kycCount = Ticket::on('mysqluser')->count();
        return $kycCount;  
    }

    function getTodayUsercount()
    {
        $today = Carbon::now()->format('Y-m-d').'%';

        $todatUserCount = User::on('mysqluser')->where('created_at', 'like', $today)->count();
        return $todatUserCount;  
    }

    function getTotalBuy()
    {
        $buyTradeCount = Buytrade::on('mysqluser')->where('status',0)->count();
       
        return $buyTradeCount;  
    }

    function getTotalSell()
    {
        $sellTradeCount = Selltrade::on('mysqluser')->where('status',0)->count();
       
        return $sellTradeCount;  
    }

    function getCompletedTrade()
    {
        $sellTradeCount = Completedtrade::on('mysqluser')->count();
       
        return $sellTradeCount;  
    }

    function getLastFiveTrades()
    {
        $open_order  = array();
        $open_order1 = array();
        $open_order2 = array();
        $mybuytrades = Buytrade::on('mysqluser')->orderBy('id', 'DESC')->limit(5)->get();

        $i=0;
        if($mybuytrades->count())
        {
            foreach($mybuytrades as $key => $value) 
            {
                $user = User::on('mysqluser')->where('id',$value->uid)->first();
                    $tradepair = Tradepair::on('mysqluser')->where('id',$value->pair)->first();
                    
                $open_order[$i]['uid']=$value->uid;
                $open_order[$i]['username']=$user->name;
                $open_order[$i]['pair']=$tradepair->coinone.'/'.$tradepair->cointwo;
                $open_order[$i]['order_type']=$value->order_type;
                $open_order[$i]['type']='Buy';
                $open_order[$i]['price']=$value->price;
                $open_order[$i]['volume']=$value->volume;
                $open_order[$i]['value']=$value->value;
                $open_order[$i]['fees']=$value->fees;
                $open_order[$i]['remaining']=$value->remaining;
                $open_order[$i]['commission']=$value->commission;
                $open_order[$i]['status']=$value->status;
                $open_order[$i]['created_at']=$value->created_at;
                $i++;
            }
        }

        $myselltrades = Selltrade::on('mysqluser')->orderBy('id', 'DESC')->limit(5)->get();
        if($myselltrades->count())
        {
            foreach($myselltrades as $key => $value) 
            {
                $user = User::on('mysqluser')->where('id',$value->uid)->first();
                $tradepair = Tradepair::on('mysqluser')->where('id',$value->pair)->first();
                $open_order[$i]['uid']=$value->uid;
                $open_order[$i]['username']=$user->name;
                $open_order[$i]['pair']=$tradepair->coinone.'/'.$tradepair->cointwo;
                $open_order[$i]['order_type']=$value->order_type;
                $open_order[$i]['type']='Sell';
                $open_order[$i]['price']=$value->price;
                $open_order[$i]['volume']=$value->volume;
                $open_order[$i]['value']=$value->value;
                $open_order[$i]['fees']=$value->fees;
                $open_order[$i]['remaining']=$value->remaining;
                $open_order[$i]['commission']=$value->commission;
                $open_order[$i]['status']=$value->status;
                $open_order[$i]['created_at']=$value->created_at;
                $i++;
            }
        }
        
        $sortedArr = collect($open_order)->sortBy('created_at')->all();
        $result =array_reverse($sortedArr);
        
        return $result;
    }
}