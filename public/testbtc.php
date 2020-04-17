<?php
require_once('easybitcoin.php');
$bitcoin = new Bitcoin('because','because','127.0.0.1','8332');
//echo "<pre>";
var_dump($bitcoin->getbalance('jagan.palaninadar@gmail.com'));
var_dump($bitcoin->getaddressesbyaccount('jagan.palaninadar@gmail.com'));
print_r($bitcoin->getbalance());
var_dump($bitcoin->getaccount('3PTe8YPQ3WqziPCKDQ7EPvQDnq68evCQEf'));
print_r($accounts=$bitcoin->listaccounts());
echo count($accounts);
//echo "Available total balance".array_sum($accounts);
var_dump($bitcoin->listaddressgroupings());

