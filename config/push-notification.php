<?php

return array(

    'IOSUser'     => array(
        'environment' =>'development',
        'certificate' =>'/path/to/certificate.pem',
        'passPhrase'  =>'password',
        'service'     =>'apns'
    ),
    'AndroidUser' => array(
        'environment' =>'production',
        //'apiKey'      =>'yourAPIKey',
        'apiKey'      => env('ANDROID_USER_PUSH_KEY', 'yourAPIKey'),
        'service'     =>'gcm'
    )

);
