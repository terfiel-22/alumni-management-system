<?php

function send_sms($recipientNumber, $message, $sender_name)
{
    // Semaphore API key
    include "../config.php";

    $ch = curl_init();
    $parameters = array(
        'apikey' => $semaphore_api, //Your API KEY
        'number' => $recipientNumber,
        'message' => $message,
        'sendername' => $sender_name
    );
    curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, 1);

    //Send the parameters set above with the request
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));

    // Receive response from server
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
}
