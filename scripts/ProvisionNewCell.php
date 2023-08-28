<?php

require('/var/www/html/INFRAESTRUCTURA-DESARROLLO/jferia/Infraestructura/php/funciones.php');

$celdap=GetCellsFromLDAP("poractivar");

$tags=array_keys($celdap);

$total=sizeof($tags);

if ($total == 0) {
        print "nada que procesar\n\n";
        return false;
}


$awdevs=QueryToAirwatchAPI("ALLDEVS","ALLDEVS");
$awdevsa=json_decode($awdevs, true);
$con=ConectaLDAP();


if ($awdevs == "UNAUTH") {
$debs="SIN AUTORIZACION PARA API DE AW";
EnviaTelegram($debs,"jferia");  
EnviaTelegram($debs,"acota");  
EnviaTelegram($debs,"eresendiz");  
EnviaTelegram($debs,"gsalazar");  
}


//print_r($celdap);
//print_r($awdevsa);
$a=array();
$b=array();


//EnviaTelegram("hi","jferia");

echo "----------------------------------------  \n";
echo "DISPOSITIVOS EN LDAP CON IMEI POR ASIGNAR \n";
echo "----------------------------------------  \n";
$debs="DISPOSITIVOS EN LDAP CON IMEI POR ASIGNAR\n";



foreach ($tags as &$value) {
    $note="";
    if ($celdap[$value]['deviceassignedto'] == "PORDEFINIR") {
        $note = " (IGNORADO)";
    }
	if (strlen($celdap[$value]['devicetag']) == 9 ) {
		echo $celdap[$value]['devicetag']." -> ".$celdap[$value]['deviceassignedto'].$note."\n";
        $debs.=$celdap[$value]['devicetag']." -> ".$celdap[$value]['deviceassignedto'].$note."\n";
		array_push($a,$celdap[$value]['devicetag']);
		$b[$celdap[$value]['devicetag']]=$celdap[$value]['deviceassignedto'];
		//print_r($celdap[$value]);
	} else {
		echo "Invalid Tag ".$celdap[$value]['devicetag']."\n";	
	}
	
}
//echo "xx".$debs;

//print_r($a);
//print_r($b);


echo "-------------------------------------------------------  \n";
echo "DISPOSITIVOS DESDE AW Y ANALIZANDO LOS QUE ESTAN CREADOS \n";
echo "-------------------------------------------------------  \n";

$processed="DUNNO";
foreach ($awdevsa['Devices'] as &$valuex) {
	$tag="DUNNO";
    	$skipthis="DUNNO";
	//echo $valuex['DeviceFriendlyName']." ---- \n";
	//echo "//////// ".$b[$valuex['DeviceFriendlyName']];
	//if (in_array($valuex['DeviceFriendlyName'], $a)) {
    	if ( (in_array($valuex['DeviceFriendlyName'], $a)) and ($b[$valuex['DeviceFriendlyName']] != "PORDEFINIR") and ($b[$valuex['DeviceFriendlyName']] != "BAJA") ) {    
    	//and ($b[$valuex['DeviceFriendlyName']] != "SINASIGNAR")
		$tg = $valuex['DeviceFriendlyName'];
echo "777777777777777777777777777".$b[$valuex['DeviceFriendlyName']];
		echo "Validando parametos locales para ".$valuex['DeviceFriendlyName']."\n";
		echo "Validando usuario ".$b[$valuex['DeviceFriendlyName']]."\n";
		$user=GetDeviceUserInfoFromLDAP($b[$valuex['DeviceFriendlyName']]);
		print_r($user);
		if ($user[count] == 1) {
			echo "OK! EXISTE EL USUARIO DE DEVICE ".$b[$valuex['DeviceFriendlyName']]." UNA SOLA VEZ\n";
		} else {
			if ($user[count] == 0) {
				echo "ERROR NO EXISTE EL USUARIO DE DEVICE ".$b[$valuex['DeviceFriendlyName']]."\n";
				$debs.="\n ERROR NO EXISTE EL USUARIO DE DEVICE ".$b[$valuex['DeviceFriendlyName']]." \n";
				$skipthis="YES";
				//exit;
			} else {
				echo "ERROR HAY MAS DE UN USUARIO DE DEVICES CON EL MISMO USERNAME";
				exit;
			}
			
		}
		echo "VALIDANDO QUE HAYA UN SOLO TELEFONO ASIGNADO AL USUARIO\n";
        $tagz=GetDeviceTagInfoFromAssignedUserLDAP($b[$valuex['DeviceFriendlyName']],"count");
        if ($tagz['count'] == 1) {
            echo  "OK! \n";
        } 
        if ($tagz['count'] == 0) {
            echo  "No existe! \n";
            exit;
        } 
        if ($tagz['count'] > 1) {
        	if ($b[$valuex['DeviceFriendlyName']] != "BAJA") {
            	$tagzname=GetDeviceTagInfoFromAssignedUserLDAP($b[$valuex['DeviceFriendlyName']],"tags");
            	echo  ": Hay mas de un device asignado al usuario ".$b[$valuex['DeviceFriendlyName']].": ".$tagzname."\n";
            	$debs.="\n ERROR!!! Hay mas de un device asignado al usuario ".$b[$valuex['DeviceFriendlyName']].": ".$tagzname." \n";
            } else {
            	echo  "PIDIENDO ALTA DE TELEFONO CON USUARIO DADO DE BAJA\n";
            	$debs.="\n ERR: PIDIENDO ALTA DE TELEFONO CON USUARIO DADO DE BAJA PARA ".$tagzname."\n";
            }
            $skipthis="YES";
            //TelegramATelefonia($debs);
            //exit;
        } 
        echo "VALIDACIONES TERMINADAS OBTENIENDO INFO DESDE AIRWATCH PARA ".$b[$valuex['DeviceFriendlyName']]."\n";
        //print_r($tagz);
        if ($skipthis == "DUNNO") {
            $sn=$valuex['SerialNumber'];
    		echo $dn="DeviceTAG=".$tg.",ou=Celulares,ou=Devices,dc=transportespitic,dc=com";
    		$brand=GetBrandFromModel($valuex[ModelId][Name]);
echo "6666666666666666666666666666".$valuex[ModelId][Name];
    		$entry = array();
    		$entry['devicebrand']=$brand;
    		$entry['deviceimei']=$valuex['Imei'];
    		$entry['devicelastenrolledon']=$valuex['LastEnrolledOn'];
    		$entry['devicelastseen']=$valuex['LastSeen'];
    		$entry['devicemac']=$valuex['MacAddress'];
    		$entry['devicemodel']=$valuex[ModelId][Name];
    		$entry['deviceserial']=$valuex['SerialNumber'];
    		$entry['objectClass'][0] = "top";
    		$entry['objectClass'][1] = "DeviceInfo";
    		print_r($entry);  
    	   	$ldm=ldap_modify($con, $dn, $entry);
        	echo $ldm;
        	echo $RES=ldap_error($con);
            if ($processed != "DUNNO") {
                $processed .= "IMPORTED ".$b[$valuex['DeviceFriendlyName']]." FROM AW TO LDAP\n";
            } else {
                $processed = "IMPORTED ".$b[$valuex['DeviceFriendlyName']]." FROM AW TO LDAP\n";
            }
        }    
	} else {

	}
}	

if ($processed != "DUNNO") {
    $debs .= "\nOBTENIENDO DISPOSITIVOS DESDE AIRWATCH Y ANALIZANDO LOS QUE ESTAN CREADOS \n";
    $debs .=  $processed;
}


//echo "quitar el exit";
//exit;

TelegramATelefonia($debs);
#EnviaTelegram($debs,"jferia");  
#EnviaTelegram($debs,"acota");  
#EnviaTelegram($debs,"eresendiz");  
#EnviaTelegram($debs,"gsalazar");  
#EnviaTelegram($debs,"fvargas");

/*
print_r($awdevsa);
[DeviceFriendlyName] => CELCUL106


           [445] => Array
                (
                    [EasIds] => Array
                        (
                        )

                    [TimeZone] => 
                    [Udid] => c7bbf7ad826cb8b8dbf205fb7c702b552cbb8806f6
                    [SerialNumber] => 325106991533
                    [MacAddress] => D472263DE332
                    [Imei] => 865651046999300
                    [EasId] => 
                    [AssetNumber] => c7bbf7ad826cb8b8dbf205fb7c702b552cbb8806f6
                    [DeviceFriendlyName] => CELTEP101
                    [DeviceReportedName] => Android_ZTE Blade A5 2020_865651046999300
                    [LocationGroupId] => Array
                        (
                            [Id] => Array
                                (
                                    [Value] => 20829
                                )

                            [Name] => Reparto EMM
                            [Uuid] => 955e9e6e-9977-4783-a804-e8e974b7de79
                        )

                    [LocationGroupName] => Reparto EMM
                    [UserId] => Array
                        (
                            [Id] => Array
                                (
                                    [Value] => 371891
                                )

                            [Name] => cristian galvan medrano
                            [Uuid] => badadbe5-e295-49c8-a298-6815bfc1007e
                        )

                    [UserName] => cgalvan
                    [DataProtectionStatus] => 0
                    [UserEmailAddress] => movilesoccidente@tpitic.com.mx
                    [Ownership] => C
                    [PlatformId] => Array
                        (
                            [Id] => Array
                                (
                                    [Value] => 5
                                )

                            [Name] => Android
                        )

                    [Platform] => Android
                    [ModelId] => Array
                        (
                            [Id] => Array
                                (
                                    [Value] => 5
                                )

                            [Name] => ZTE ZTE Blade A5 2020
                        )

                    [Model] => ZTE ZTE Blade A5 2020
                    [OperatingSystem] => 9.0.0
                    [PhoneNumber] => 
                    [LastSeen] => 2022-02-14T20:10:21.540
                    [EnrollmentStatus] => Enrolled
                    [ComplianceStatus] => Compliant
                    [CompromisedStatus] => 
                    [LastEnrolledOn] => 2022-02-14T19:19:03.983
                    [LastComplianceCheckOn] => 2022-02-14T19:19:04.743
                    [LastCompromisedCheckOn] => 2022-02-14T19:43:28.413
                    [IsSupervised] => 
                    [VirtualMemory] => 0
                    [OEMInfo] => ZTE ZTE Blade A5 2020
                    [IsDeviceDNDEnabled] => 
                    [IsDeviceLocatorEnabled] => 
                    [IsCloudBackupEnabled] => 
                    [IsActivationLockEnabled] => 
                    [IsNetworkTethered] => 
                    [BatteryLevel] => 
                    [IsRoaming] => 
                    [SystemIntegrityProtectionEnabled] => 
                    [ProcessorArchitecture] => 0
                    [TotalPhysicalMemory] => 0
                    [AvailablePhysicalMemory] => 0
                    [OSBuildVersion] => 
                    [DeviceCellularNetworkInfo] => Array
                        (
                            [0] => Array
                                (
                                    [CarrierName] => 
                                    [CardId] => 
                                    [PhoneNumber] => 
                                    [DeviceMCC] => Array
                                        (
                                            [SIMMCC] => 0
                                            [CurrentMCC] => 0
                                        )

                                    [IsRoaming] => 
                                )

                        )

                    [EnrollmentUserUuid] => 00000000-0000-0000-0000-000000000000
                    [ManagedBy] => 1
                    [WifiSsid] => <unknown ssid>
                    [Id] => Array
                        (
                            [Value] => 500898
                        )

                    [Uuid] => b381003b-14f2-4bff-9e74-60e46ba315d4
                )

        )

    [Page] => 0
    [PageSize] => 500
    [Total] => 446


require('php/funciones.php');
//include('configuraciones.class.php');
$celdap=GetCellsFromLDAP("x");
echo "<pre>";
//print_r($celdap);

echo "Este scripi se trae todos los devices de Airwatch <br>";

$imeis=array_keys($celdap);
$conn = ConectaSQL('ocsweb');

$awdevs=QueryToAirwatchAPI("ALLDEVS","ALLDEVS");
$awdevsa=json_decode($awdevs, true);
echo "<pre>";
//print_r($awdevsa);
echo "</pre>";

$all=Array();
$cnt=0;

foreach ($awdevsa['Devices'] as &$valuex) {
	$cnt++;
	$fn="";
	$din="";
    echo "<pre>";
    //print_r($valuex);
    if (strlen($valuex['DeviceFriendlyName']) > 7 ) {
	    if (strlen($valuex['DeviceFriendlyName']) < 10 ) {
			$OCS=GetOCSInfoFromTAG($valuex['DeviceFriendlyName'],"HARDWARE_ID",$conn);
			$fn=$valuex['DeviceFriendlyName'];
			$all[$fn]['serial']=$valuex['SerialNumber'];
	    } else {
	    	// Si el tag esta mal buscar la serie en LDAP
	    	$din=GetDeviceInfoFromLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","deviceserial",$valuex['SerialNumber']);
	    	$mytg=$din[0]['devicetag'];
	    	$OCS="INVALID TAG, NOT CHECKING (TAG= $mytg sn: ".$valuex['SerialNumber']." )";
	    	//print_r($din);
	    }
    } else {
    	$OCS="INVALID TAG, NOT CHECKING";	
    }
	$sx='';
	$xxx='';
	$xxxx='';
    if ($OCS == "NO TAG ON OCS") {
		$xxx=GetOCSTAG($valuex['SerialNumber'],$conn);
		if (strlen($xxx) > 3) {
			$xxxx="ACTUAL WRONG TAG ".$xxx;	
			CorrectOCSTAG($valuex['DeviceFriendlyName'],$xxx,$conn);
		} else {
			$xxxx="ACTUAL WRONG TAG TOO SHORT OR NOT FOUND";	
		}
		$sx=" -> Serie:".$valuex['SerialNumber'];
    }

    echo $cnt." xx".$valuex['DeviceFriendlyName']."yy  IMEI: --> ".$valuex['Imei']."  OCS: ----> ".$OCS." ".$sx." = ".$xxxx;
    echo "</pre>";
}


//print_r($all);
$con=ConectaLDAP();
echo $con;

foreach ($imeis as &$value) {
	$success='DUNNO';
	$valid='DUNNO';
	$tg="";
    if ($celdap[$value]['deviceimei'] == "PORASIGNAR") {
    	//$sn=$celdap[$value]['deviceserial'];
    	$tg=$celdap[$value]['devicetag'];
    	$sn=$all[$tg]['serial'];
        echo $celdap[$value]['devicetag']." -> ".$celdap[$value]['deviceimei']."<br>$sn";
        echo "<br>";
		echo $dn="DeviceTAG=".$tg.",ou=Celulares,ou=Devices,dc=transportespitic,dc=com";        
        $telinfo=QueryToAirwatchAPI("DEVICE",$sn);    
        //echo "<pre> $telinfo </pre>";
        $telia=json_decode($telinfo, true);
        echo "<pre>";
        //print_r($telia);
		$brand=GetBrandFromModel($telia[ModelId][Name]);
		if ($brand == "ERROR") {
			echo "ERROR ON BRAND GET";
			exit;	
		}

		$entry = array();
		$entry['devicebrand']=$brand;
		$entry['deviceimei']=$telia['Imei'];
		$entry['devicelastenrolledon']=$telia['LastEnrolledOn'];
		$entry['devicelastseen']=$telia['LastSeen'];
		$entry['devicemac']=$telia['MacAddress'];
		$entry['devicemodel']=$telia[ModelId][Name];
		$entry['deviceserial']=$telia['SerialNumber'];
		$entry['objectClass'][0] = "top";
		$entry['objectClass'][1] = "DeviceInfo";
		print_r($entry);  
    	$ldm=ldap_modify($con, $dn, $entry);
    	//$ldm=ldap_mod_replace_ext($con, $dn, $values);
    	echo $ldm;
    	echo $RES=ldap_error($con);
        echo "</pre>";
    }
}


echo "Total: ".$cnt;





//DeviceModel
//DeviceSerial

$entry['deviceimei']=$forma['val-devicimei'];


$brand=GetBrandFromModel($telia[ModelId][Name]);


$entry['devicebrand']=$brand;
$entry['devicelastenrolledon']=$telia['LastEnrolledOn'];
$entry['devicelastseen']=$telia['LastSeen'];
$entry['devicemac']=$telia['MacAddress'];
$entry['devicemodel']=$telia[ModelId][Name];
$entry['deviceserial']=$telia['SerialNumber'];
$entry['objectClass'][0] = "top";
$entry['objectClass'][1] = "DeviceInfo";



echo "<pre>";
print_r($entry);
echo "</pre>";
echo $ERROR;
return false;


*/

?>



