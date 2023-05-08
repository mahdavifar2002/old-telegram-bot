<?php
/*
@datisnetwork
https://t.me/datisnetwork
*/
require 'index.php';

$MerchantID='885f6df6-3435-11e9-9c74-005056a205be';

$g=$_GET;
$amount=$g['amount'];
$authority=$g['Authority'];
$from=$g['from'];

if(($amount==1000 or $amount==2000 or $amount==10000 or $amount==20000 or $amount==5000){
if($g['Status']=='OK'){
	$client=new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);
	$result=$client->PaymentVerification(
		[
			'MerchantID' => $MerchantID,
			'Authority' => $Authority,
			'Amount' => $Amount,
		]
	);
	if($result->Status==100){
		$sql=mysqli_fetch_array(mysqli_query($ne,"select * from members where id='$from'"));
		mysqli_query("update members set coin=coin+{$sql['coin']} where id='$from'");
		sendmessage($from,"بسته $amount خریداری شد.");
		sendmessage($admin,"خرید انجام شده از $from به مبلغ $amount تومان");
	}else
		exit('Error Status : '.$result->Status);
}else
	exit('پرداخت انجام نشده');

}else
	exit('ERROR, AMOUNT HAS NOT TRUE VALUE');
/*
@datisnetwork
https://t.me/datisnetwork
*/
