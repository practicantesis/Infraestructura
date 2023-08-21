<?php


function QueryToVeloAPI($tipo,$val) {
	$token='eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tlblV1aWQiOiI5NmE4M2YwMS02OWYzLTRhM2UtOTlhMi00MTdiMTYyNmJkMTQiLCJleHAiOjE3MjAxMzY1NTEsInV1aWQiOiI5YTVhOWJhNi01ODEwLTExZWEtYmZhZi0wYTA0Nzg0NTQzYzMiLCJpYXQiOjE2ODkwOTM0ODB9.X79fSY06AwYXNKCSNbvGHLYXYWOvo04jcx1EGR6rSqc';

	$ch = curl_init();

    $url = 'https://vco129-usvi1.velocloud.net/portal/rest/enterprise/getEnterpriseEdges';
    //$url = 'http://192.168.120.179/smart.txt';
    //$headers = ['Authorization: Bearer '.$token,'Content-Type:application/json'];
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   'Content-Type: application/json',
   'Authorization: Token ' . $token
   ));

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	echo "<pre>";
    $ch_result = curl_exec($ch);
    $infos = curl_getinfo($ch);

	echo $eljson = json_decode ($ch_result, true);

    
    print_r($eljson);
    //echo "</pre>";
    curl_close($ch);    
}

echo $XXX = QueryToVeloAPI('tipo','val');
echo "xxx";
?>