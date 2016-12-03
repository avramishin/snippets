<?php
/**
 * Send push notifications to IOS
 * To get pem certificate, password and device token - ask your IOS developer
 * Endpoint url is ssl://gateway.push.apple.com:2195 for production and ssl://gateway.sandbox.push.apple.com:2195 for sandbox
 */

$conf = [
    "url" => "ssl://gateway.push.apple.com:2195",
    "pemCertificate" => __DIR__ . "/certificate.pem",
    "pemPassword" => "",
    "deviceToken" => "your ios device token"
];

$msg = [
    "aps" => [
        "alert" => "Hello, new Iphone is coming soon!",
        "sound" => "default",
        "badge" => 1
    ]
];

if(!file_exists($conf["pemCertificate"])){
    die('PEM certificate not found!');
}

$ctx = stream_context_create();

stream_context_set_option($ctx, "ssl", "local_cert", $conf["pemCertificate"]);
stream_context_set_option($ctx, "ssl", "passphrase", $conf["pemPassword"]);

$errorNo = null;
$errorString = null;
$fp = stream_socket_client($conf['url'], $errorNo, $errorString, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

if ($fp) {
    $payload = json_encode($msg);
    $msg = chr(0) . pack("n", 32) . pack("H*", $conf["deviceToken"]) . pack("n", strlen($payload)) . $payload;
    $result = fwrite($fp, $msg, strlen($msg));
    fclose($fp);
    var_dump($result);
} else {
    die("Failed to connect to gateway: {$errorNo} {$errorString}");
}