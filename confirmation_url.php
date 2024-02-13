<?php
require 'config.php';

$headers = ['Content-Type:application/json'];

$response = '{
            "ResultCode":0,
            "ResultDesc": "confirmation received successfully"

}';

//DATA
$mpesaResponse=file_get_contents('php://input');

//log the response
$logFile="M_PESAResponse.txt";
#"M_PESAConfirmationResponse.txt"
$jssonMpesaResponse=json_decode($mpesaResponse, true);

$transaction=array(
    `:TransactionType` => $jssonMpesaResponse['TransactionType'] ,
    `:TransID` => $jssonMpesaResponse['TransID'],
    `:TransTime` => $jssonMpesaResponse['TransTime'], 
    `:TransAmount` => $jssonMpesaResponse['TransAmount'], 
    `:BusinessShortCode` => $jssonMpesaResponse[BusinessShortCode],
    `:BillRefNumber` => $jssonMpesaResponse['BillRefNumber'],
    `:InvoiceNumber`=> $jssonMpesaResponse['InvoiceNumber'], 
    `:OrgAccountBalance` => $jssonMpesaResponse['OrgAccountBalance'], 
    `:ThirdPartyTransID` => $jssonMpesaResponse['ThirdPartyTransID'], 
    `:MSISDN` => $jssonMpesaResponse[':MSISDN'], 
    `:FirstName` => $jssonMpesaResponse['FirstName'], 
    `:MiddleName` => $jssonMpesaResponse['MiddleName'], 
    `:LastName`=> $jssonMpesaResponse['LastName']

);

//Write to file
$log =fopen($logFile, "a");
fwrite($log, $mpesaResponse);
fclose($log);

echo $response;
insert_response($transaction);

?>