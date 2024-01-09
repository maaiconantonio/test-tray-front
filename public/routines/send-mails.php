<?php
$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => 'nginx/sale/today/true',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
$resApi = json_decode($response, true);

$mailTo = "maaicon.antonio@gmail.com";
$mailHeader = [
	'From' => 'maicon_antonio@msn.com',
    'Reply-To' => 'maicon_antonio@msn.com',
    'X-Mailer' => 'PHP/'.phpversion()
];

$sumSales = 0;
foreach($resApi as $salesToday) {
	$sumSales += $salesToday["sale_value"];
}

$sumSalesFormat = number_format($sumSales, 2, ",", ".");

$subject = "Total de vendas de hoje: ".date("d/m/Y");
$mailMsg = "Segue o valor total de vendas de hoje: R$ {$sumSalesFormat}.<br />Tenha uma ótima noite!";

mail($mailTo, $subject, $mailMsg, $mailHeader);