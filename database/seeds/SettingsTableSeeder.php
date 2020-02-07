<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->delete();
        DB::table('settings')->insert([
            [
                'key' => 'site_title',
                'value' => 'Liocoin'
            ],
            [
                'key' => 'site_logo',
                'value' => asset('logo.png'),
            ],
            [
                'key' => 'site_email_logo',
                'value' => asset('logo.png'),
            ],
            [
                'key' => 'site_icon',
                'value' => asset('favicon.ico'),
            ],
            [
                'key' => 'site_copyright',
                'value' => '&copy; '.date('Y').' Cointronix'
            ],
            [
                'key' => 'currency',
                'value' => "$"
            ],[
                'key' => 'currency_symbol',
                'value' => "$"
            ],[
                'key' => 'currency_value',
                'value' => "0"
            ],
            [
                'key' => 'contact_no',
                'value' => "123456"
            ],
            [
                'key' => 'contact_email',
                'value' => ""
            ],
            [
                'key' => 'contact_website',
                'value' => ""
            ],
       
        ]);
    }
}
