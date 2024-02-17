<?php
		function QueryToAirwatchAPI23($tipo,$val) {
			$myfile = fopen("/var/www/html/Infraestructura/.awapi", "r") or die("Unable to open file!");
			$apipw=fgets($myfile);
			fclose($myfile);
			$pass="infra:".$apipw;
			echo "xxxx".trim($pass)."yyy";
			$basic_auth = base64_encode(trim($pass));
  			$headers = ['aw-tenant-code: '.$api_key,'Authorization: Basic '.$basic_auth,'Accept: application/json'];
		    $api_key='Zbh2S+e0ejNOibdtwlFDFssflXSeCniu2oh1/7lVg5A=';
		    $ch = curl_init();
		    $baseurl="https://as257.awmdm.com";
		    if ($tipo == "LGID") {
		        $endpoint="/API/mdm/devices/search?lgid=".$val;
		    }
		    if ($tipo == "ALLDEVS") {
		        $endpoint="/API/mdm/devices/search";
		    }
		    if ($tipo == "DEVICE") {
		        $endpoint="/api/mdm/devices/?searchby=Serialnumber&id=".$val;
		    }
		    if ($tipo == "DEVICEperIMEI") {
		        $endpoint="/api/mdm/devices/?searchby=ImeiNumber&id=".$val;
		    }
		    if ($tipo == "DeleteDEVICEperIMEI") {
		        $endpoint="/api/mdm/devices/?searchby=ImeiNumber&id=".$val;
		    }
		    if ($tipo == "NOTES") {
		        $endpoint="/api/mdm/devices/notes?searchby=SerialNumber&id=320615670110";
		    } 
	        $headers = ['aw-tenant-code: '.$api_key,'Authorization: Basic '.$basic_auth,'Accept: application/json'];

		    echo $url = $baseurl.$endpoint;
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		    if ($tipo == "DeleteDEVICEperIMEI") {
		        curl_setopt($curl, CURLOPT_DELETE, true);
		    }
		    curl_setopt($ch, CURLOPT_VERBOSE, 1);
		    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		    if ($tipo == "DeleteDEVICEperIMEI") {
		        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		    } else {
		        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		    }
		    echo $ch;
		    echo $ch_result = curl_exec($ch);
		    echo $infos = curl_getinfo($ch);
		    print_r($infos);
			//echo "UNAUTH!!!!!!!!!!!!!!!!!!!!!!!!!!".$infos['http_code'];
    		if ($infos['http_code'] == 401) {
	        return "UNAUTH";
		    }
    		//If http_code is not 200, then there's an error
    		if ($infos['http_code'] != 200) {
        		$result['status'] = AIRWATCH_API_RESULT_ERROR;
        		$result['error']  = $infos['http_code'];
    		} else {
        		$result['status'] = AIRWATCH_API_RESULT_OK;
        		$result['data'] = $ch_result;
    		}
    		if ($result['status'] == "AIRWATCH_API_RESULT_ERROR") {
        		return $result['error'];
    		} else {
        		return $result['data'];
    		}
		    curl_close($ch);
		}


//LGID   25901 - cho was   6707 venfrdores
   	$xx=QueryToAirwatchAPI23("LGID","6707");
//R9YT90DP37W
   	//$xx=QueryToAirwatchAPI23("LGID","25901");
		//$xx=QueryToAirwatchAPI23("DEVICE","R9HT902YJZW");
   	
   	echo "<pre>";
	print_r($xx);
	echo "</pre>";
?>
