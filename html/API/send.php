<?php
//use HttpClient;
require 'HttpClient.php';
require 'PgConn.php';
require 'Chat.php';
define('TIME_TO_LIVE', 60);
$connect = new PgConn();
$sql = "Select * from push_notification  where browser = 'firefox' or browser = 'chrome' ";
$subscribers = pg_fetch_all(pg_query($connect->dbconnect, $sql));


foreach ($subscribers as $subscribers_list) {
    $result = send_push_message($subscribers_list['browser'], $subscribers_list['id_device']);
    echo $result;
}
function send_push_message($browser, $subscriber_id)
{
    switch ($browser) {

        case 'chrome':

            $key = 'AAAAIR12adw:APA91bEq7iaKXpobw83uTpPfqjLbzF8f50R6EAwjDzldOBvyaN16VivZcXv0e9v0MxcBIWHXHvBH5U_0bIkum5z0vw_-hhBjwFosbWa35DOP7lJKbWJwK7qvwtpyWcqspzATMIxNfwqJ';


            $headers = array(
                'content-type' => 'application/json',
                'Authorization' => 'key=' . $key

            );
            $postBody = array(
                'to' => $subscriber_id,
                'data' => array('message' => 'send'),
                'time_to_live' => TIME_TO_LIVE,
                'collapse_key' => 'test'
            );
            $postBody = json_encode($postBody);
            $url = 'https://fcm.googleapis.com/fcm/send';
            break;

        case 'firefox':

            $url = 'https://updates.push.services.mozilla.com/wpush/v1/'.$subscriber_id;
            $headers = array('TTL' => '60');
            $postBody = null;
            break;

    }

    $httpClient = new HttpClient();
    $result = $httpClient->post($url,
        $headers, $postBody)->getBody();
    return $result;
}