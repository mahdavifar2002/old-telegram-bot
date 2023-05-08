<?php
/*
@datisnetwork
https://t.me/datisnetwork
*/
$g=$_GET;
$amount=$g['amount'];
$callback=$g['callback'].'&amount='.$amount;
$MerchantID='885f6df6-3435-11e9-9c74-005056a205be';

if(($amount==1000 or $amount==2000 or $amount==10000 or $amount==20000 or $amount==5000) and $callback){
	$client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

$result = $client->PaymentRequest(
	[
		'MerchantID' => $MerchantID,
		'Amount' => $amount,
		'Description' => 'خرید اشتراک ویژه در ربات چت گرام',
		'Email' => '',
		'Mobile' => '',
		'CallbackURL' => $callback,
	]
);

	if($result->Status==100)
		header('Location: https://www.zarinpal.com/pg/StartPay/'.$result->Authority);
	else
		exit('Error Status : '.$result->Status);


}else{
	echo ':(';
	header('location:https://t.me/datisnetwork');
}
/*
@datisnetwork
https://t.me/datisnetwork
*/