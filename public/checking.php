<?php

    //echo $result;

    $key = "YMoEtIgr#W&Ab7uu3mlZeanIMr";   

    function simple_crypt($key, $string, $action = 'encrypt'){
        $res = '';
        if($action !== 'encrypt'){
            $string = base64_decode($string);
        }
        for( $i = 0; $i < strlen($string); $i++){
            $c = ord(substr($string, $i));
            if($action == 'encrypt'){
                $c += ord(substr($key, (($i + 1) % strlen($key))));
                $res .= chr($c & 0xFF);
            }else{
                $c -= ord(substr($key, (($i + 1) % strlen($key))));
                $res .= chr(abs($c) & 0xFF);
            }
        }
        if($action == 'encrypt'){
            $res = base64_encode($res);
        }
        return $res;
    }


    $passkey = $_REQUEST["hashstring"];
    $uniquekey = $_REQUEST["uniquekey"];


    if($uniquekey=="check"){

        header('Content-Type: application/json');
        $decrypt =  simple_crypt($key,$passkey,"decrypt");
        
        $json = array();
        
        if (strpos($decrypt, '~') !== false) {
            $pieces = explode("~", $decrypt);
            
            
            //echo json_encode($result);
            $finalresult = $pieces[0];
        }else{

            $finalresult =  "false";
        }
        
        $jsonData = array(
            'key' => $finalresult
        );
        $jsonstring = json_encode($jsonData);
        echo $jsonstring;
        return $jsonstring;

    }

    /*$encrypt = simple_crypt($key,"1~luxerykey","encrypt");

    echo $encrypt."<br>";

    $decrypt = simple_crypt($key,$encrypt,"decrypt");

    $pieces = explode("~", $decrypt);*/

    //echo $pieces[0];

    //echo $decrypt."<br>";

?>