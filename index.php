<?php

// WeSMS Endpoint 
define ("endpoint", "https://rest.wesms.ly/api/v2/messages/send");
// Access Token Key 
define ("access_token", "your_access_token_here");

// function to send single sms 
function sendSingleSMS($recipient,$body){
    // recipient : the recipient phone number : STRING
    // body : message to send : STRING

    // make data of body request
    $data = array(
        'recipient' => $recipient,
        'body' => $body
    );

    // convert to json
    $post_data = json_encode($data);

    // Prepare end point URL resource
    $crl = curl_init(endpoint);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($crl, CURLINFO_HEADER_OUT, true);
    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
    
    // Set HTTP Header for POST request 
    curl_setopt($crl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'access_token: '.access_token )
    );
    
    // Submit the POST request
    $result = curl_exec($crl);
    
    // // handle curl error
    // if ($result === false) {
    //     // throw new Exception('Curl error: ' . curl_error($crl));
    //     //print_r('Curl error: ' . curl_error($crl));
    //     $result_noti = 0; die();
    // } else {
    //     $result_noti = 1; die();
    // }
    // Close cURL session handle
    curl_close($crl);

    return $result;
}

// function to send multi sms 


function sendMultiSMS($recipients,$body){
    // recipients : the recipients phone number : Array
    // body : message to send : STRING

    $status = false;

    for($i = 0; $i < count($recipients); $i++){
        $send = sendSingleSMS($recipients[$i] ,$body);
        $json_decode = json_decode($send); // decode json
        $json_decode->status == "error" ? $result = false : $status = true;
    }

    return $status;
}

// test send single sms
$single = sendSingleSMS("910025444","this is single recipient message");
echo $single;


// test send multi sms
// first define a list of numbers 
$numbers = [
    "910025444",
    "916649811",
    "927390520"
];
// second call sendMultiSMS
$multi = sendMultiSMS($numbers, "this is mulit recipients message");
echo "<br />";
var_dump($multi);
