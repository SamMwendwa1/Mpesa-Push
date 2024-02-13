<?php
  date_default_timezone_set('Africa/Nairobi');

  $consumerKey = ''; //Fill with your app Consumer Key
  $consumerSecret = ''; // Fill with your app Secr

  # define the variales
  # provide the following details, this part is found on your test credentials on the developer account
  $BusinessShortCode = '';
  $Passkey = '';  
  
  /*
    This are your info, for
    $PartyA should be the ACTUAL clients phone number or your phone number, format 2547********
    $AccountRefference, it maybe invoice number, account number etc on production systems, but for test just put anything
    TransactionDesc can be anything, probably a better description of or the transaction
    $Amount this is the total invoiced amount, Any amount here will be 
    actually deducted from a clients side/your test phone number once the PIN has been entered to authorize the transaction. 
    for developer/test accounts, this money will be reversed automatically by midnight.
  */
  
  $PartyA ='254113047464'; #$_POST['phone']; // This is your phone number, 
  $AccountReference ='Food On Wheels';
  $TransactionDesc = 'test';
  $Amount = '1'; #$_POST['item_total'];
 
  # Get the timestamp, format YYYYmmddhms -> 
  $Timestamp = date('YmdHis');    
  
  # Get the base64 encoded string -> $password. The passkey is the M-PESA Public Key
  $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);

  # header for access token
  $headers = ['Content-Type:application/json; charset=utf8'];

    # M-PESA endpoint urls
  $access_token_url = '';
  $initiate_url = '';

  # callback url
  $CallBackURL = '';  

  $curl = curl_init($access_token_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_HEADER, FALSE);
  curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
  $result = curl_exec($curl);
  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  $result = json_decode($result);
  $access_token = $result->access_token;  
  curl_close($curl);

#start of register

$url = '';

	/* This two files are provided in the project. */
	$confirmationUrl = ''; // path to your confirmation url. can be IP address that is publicly accessible or a url
	$validationUrl = ''; // path to your validation url. can be IP address that is publicly accessible or a url

       
 $curl = curl_init();
 curl_setopt($curl, CURLOPT_URL, $url);
 curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header


 $curl_post_data = array(
    //fill in the response parameters with valid values
         'ShortCode' => '600981',
         'ResponseType' => 'Confirmed',
         'ConfirmationURL' => $confirmationUrl,
         'ValidationURL' => $validationUrl
         );
        
        
         $data_string = json_encode($curl_post_data);

         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($curl, CURLOPT_POST, true);
         curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
 
         $curl_response = curl_exec($curl);
         print_r($curl_response);
 
         echo $curl_response;



  #end of register

  # header for stk push
  $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];

  # initiating the transaction
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $initiate_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader); //setting custom header

  $curl_post_data = array(
    //Fill in the request parameters with valid values
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $Amount,
    'PartyA' => $PartyA,
    'PartyB' => $BusinessShortCode,
    'PhoneNumber' => $PartyA,
    'CallBackURL' => $CallBackURL,
    'AccountReference' => $AccountReference,
    'TransactionDesc' => $TransactionDesc
  );

  $data_string = json_encode($curl_post_data);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
  $curl_response = curl_exec($curl);
  print_r($curl_response);
  echo $curl_response;


  header("Content-Type: application/json");

  $response = '{ "ResultCode": 0, "ResultDesc": "Confirmation Received Successfully" }';

  // Save the M-PESA input stream. 
  $mpesaResponse = file_get_contents('php://input');

  /* If we have any validation, we will do it here then change the $response if we reject the transaction */
  // Your Validation
  // $response = '{  "ResultCode": 1, "ResultDesc": "Transaction Rejected."  }';
  /* Ofcourse we will be checking for amount, account number(incase of paybill), invoice number and inventory.
  But we reserve this for future tutorials*/

  // log the response
  $logFile = "validationResponse.txt";

  // will be used when we want to save the response to database for our reference
  $jsonMpesaResponse = json_decode($mpesaResponse, true); 

  // write the M-PESA Response to file
  $log = fopen($logFile, "a");
  fwrite($log, $mpesaResponse);
  fclose($log);

  echo $response;
  

    
 if ($response['ResultCode']==0){
    //You need to redirect
    header(""); /* Redirect browser */
    exit();
   }
  else{
    header("");
  }
  
  echo 
  $status;
  
  #"ResultCode":1032,"ResultDesc":"Request cancelled by user"
  #"ResultCode":0,"ResultDesc":"The service request is processed successfully.
  #"ResultCode":1032,"ResultDesc":"Request cancelled by user"


  {"Body":
    {
        "stkCallback": #key
        {
                "MerchantRequestID":"20431-10944643-1", 
                "CheckoutRequestID":"ws_CO_24062022140929823728785868",
                "ResultCode":1032,
                "ResultDesc":"Request cancelled by user"
        }
    }
}
$resData= $response->Body->stkCallback->ResultCode;
 ?>