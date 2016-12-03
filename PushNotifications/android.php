<?php
/**
 * Send push notifications to Android
 * To get device token - ask your Android developer
 * Api access key can be generated in https://console.developers.google.com/apis/
 */

$conf = [
    "deviceToken" => "Your Android Device Token",
    "apiAccessKey" => "Your Api Access Key",
    "url" => "https://android.googleapis.com/gcm/send",
    "text" => "NVIDIA Geforce GTX is coming soon!",
    "data" => [
        /* put any data you need */
    ]
];

$fields = [
    "to" => $conf["deviceToken"],
    "notification" => [
        "body" => $conf["text"],
        "priority" => "high",
        "icon" => "myicon",
        "data" => $conf["data"]
    ]
];

$headers = [
    "Authorization: key=" . $conf["apiAccessKey"],
    "Content-Type: application/json"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $conf["url"]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
$result = curl_exec($ch);
curl_close($ch);

var_dump($result);