<?php

//---------------------step1--------------
//-------------------Access token---------
$consumerKey = ''; //Fill with your app Consumer Key
 $consumerSecret = ''; // Fill with your app Secret

//initiae curl
$headers = ['Content-Type:application/json; charset=utf8'];

$url = '';//Enter your sandbox app url

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_HEADER, FALSE);
curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
$result = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$result = json_decode($result);

$access_token = $result->access_token;

echo $access_token;

curl_close($curl);

//---------------------step2--------------
//-------------------Register-------------


?>
