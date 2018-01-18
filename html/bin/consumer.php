<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;
require '../vendor/autoload.php';
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('hello', false, false, false, false);
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
$callback = function($msg) {
    echo " [x] Received ", $msg->body, "\n";

    $YOUR_API_KEY = 'AAAAIR12adw:APA91bEq7iaKXpobw83uTpPfqjLbzF8f50R6EAwjDzldOBvyaN16VivZcXv0e9v0MxcBIWHXHvBH5U_0bIkum5z0vw_-hhBjwFosbWa35DOP7lJKbWJwK7qvwtpyWcqspzATMIxNfwqJ'; // Server key
    $YOUR_TOKEN_ID = 'fp9R9V_mN3o:APA91bEQFzyGVAy36OQG9PXR4nuryTz-i7GddBm-5YSaKBawmu2qUp-tMnP0lPbuCmWHvGYMlhtpIRMsGhvqutbaXXDxbxj3YqvMBOVbd889PA16ifIj-DYIN6JGZMnGLl-6_WOctNE2'; // Client token id
    $url = 'https://fcm.googleapis.com/fcm/send';
    $request_body =array(
        'to' => $YOUR_TOKEN_ID,
        'notification' => array(
            'title' => 'notice',
            'body' => sprintf('Начало в %s.', date('H:i')),
        ),
    );
    $fields = json_encode($request_body);

    $request_headers = array(
        'Content-Type: application/json',
        'Authorization: key='. $YOUR_API_KEY,
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
};

//
$channel->basic_consume('hello', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();

}
$connection->close();
$channel->close();