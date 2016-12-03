<?php
/**
 * How to load multiple documents with CURL in the same time
 * This can significantly improve your network task performance
 */


$urls = array(
    'http://google.com',
    'http://stackoverflow.com',
    'http://bbc.co.uk'
);

$mh = curl_multi_init();
$cHandlers = array();

foreach($urls as $id => $url){
    $cHandlers[$id] = curl_init();
    curl_setopt($cHandlers[$id], CURLOPT_URL, $url);
    curl_setopt($cHandlers[$id], CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($cHandlers[$id], CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cHandlers[$id], CURLOPT_USERAGENT, '');
    curl_setopt($cHandlers[$id], CURLOPT_REFERER, $url);
    curl_setopt($cHandlers[$id], CURLOPT_TIMEOUT, 30);
    curl_setopt($cHandlers[$id], CURLOPT_MAXREDIRS, 10);
    curl_setopt($cHandlers[$id], CURLOPT_ENCODING, 'gzip');
    curl_setopt($cHandlers[$id], CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($cHandlers[$id], CURLOPT_SSL_VERIFYHOST, false);
    curl_multi_add_handle($mh, $cHandlers[$id]);

}

$running = NULL;

do {
    usleep(10000);
    curl_multi_exec($mh, $running);
} while ($running > 0);

$result = array();
foreach ($cHandlers as $id => $ch) {
    $result[$id] = curl_multi_getcontent($ch);
    curl_multi_remove_handle($mh, $ch);
}

curl_multi_close($mh);

var_dump($result);