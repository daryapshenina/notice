<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17.11.17
 * Time: 19:11
 */
$token='czsQEAkuKGI:APA91bGAW6fAoxHhputjAByiXAnX_Niem2hQlH6UWi-AX304N4nzdQZJe5gQc4XV6fB40p_WvTGvqhODnb2P4aIWB0iXer0PzmaoaQl7nisUXSzvq8sQ6QvICpoDTxkh3WQ8pQq0ndBa';
$url = 'https://fcm.googleapis.com/fcm/send';
$YOUR_API_KEY = 'AAAAIR12adw:APA91bEq7iaKXpobw83uTpPfqjLbzF8f50R6EAwjDzldOBvyaN16VivZcXv0e9v0MxcBIWHXHvBH5U_0bIkum5z0vw_-hhBjwFosbWa35DOP7lJKbWJwK7qvwtpyWcqspzATMIxNfwqJ'; // Server key
$YOUR_TOKEN_ID = 'fp9R9V_mN3o:APA91bEQFzyGVAy36OQG9PXR4nuryTz-i7GddBm-5YSaKBawmu2qUp-tMnP0lPbuCmWHvGYMlhtpIRMsGhvqutbaXXDxbxj3YqvMBOVbd889PA16ifIj-DYIN6JGZMnGLl-6_WOctNE2'; // Client token id

$request_body =array(
    'to' => $YOUR_TOKEN_ID,
    'notification' => array(
        'title' => 'Ералаш',
        'body' => sprintf('Начало в %s.', date('H:i')),
        'icon' => 'https://eralash.ru.rsz.io/sites/all/themes/eralash_v5/logo.png?width=192&height=192',
        'click_action' => 'http://eralash.ru/',
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