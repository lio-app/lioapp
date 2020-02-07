<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\HomeController;
use App\Currency;

class CustomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitcoin:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bitcoin Confirmation Check';

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
	    \Log::info("Cron Runs:");

        $currency_name='USD';

        $currency=Currency::where('currency','=',$currency_name)->first();
        
        if($currency){ 

            $client = new Client;   
            $lio_usd = $client->get('https://btc-alpha.com/api/v1/ticker/?format=json&pair=LIO_USD');
            $lio_usd_details = json_decode($lio_usd->getBody(),true);
            $coin_value = $lio_usd_details['last'];

            //$currency->currency=$currency_name;
            $currency->coin_value=$coin_value;
            $currency->save();
        }

    }
}
