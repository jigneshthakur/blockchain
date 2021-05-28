<?php
/*
* import checksum generation utility
* You can get this utility from https://developer.paytm.com/docs/checksum/
*/
require_once("PaytmChecksum.php");

$paytmParams = array();

$paytmParams["subwalletGuid"]      = "28054249-XXXX-XXXX-af8f-fa163e429e83";
$paytmParams["orderId"]            = "ORDERID_98765";
$paytmParams["beneficiaryAccount"] = "918008484891";
$paytmParams["beneficiaryIFSC"]    = "PYTM0123456";
$paytmParams["amount"]             = "1.00";
$paytmParams["purpose"]            = "SALARY_DISBURSEMENT";
$paytmParams["date"]               = "2020-06-01";

$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

/*
* Generate checksum by parameters we have in body
* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
*/
$checksum = PaytmChecksum::generateSignature($post_data, "YOUR_MERCHANT_KEY");

$x_mid      = "YOUR_MID_HERE";
$x_checksum = $checksum;

/* for Staging */
$url = "https://staging-dashboard.paytm.com/bpay/api/v1/disburse/order/bank";

/* for Production */
// $url = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/bank";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$response = curl_exec($ch);
print_r($response);

