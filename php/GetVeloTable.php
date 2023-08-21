<?php
require('funciones.php');

$XXX = QueryToVeloAPI('tipo','val');




$html = "<table border = 1><tr><th>OFI</th><th>STATUS</th><th>ACTIVO DESDE</th><th>ULTIMO CONTACTO</th><tr>";


foreach ($XXX as &$value) {
    //echo ;
    $html .= "<tr><td>".$value[name]."</td><td>".$value[edgeState]."</td><td><small>".$value[serviceUpSince]."</small></td><td><small>".$value[lastContact]."</small></td><tr>";
  
}


$html .= "</table>";

/*
//while($row = $XXX) {
  //echo "---------------<br>";
  while($rowb = $row) {
    echo $rowb[id];
  }
  //sleep('500');
}  
*/

/*
echo "<pre>";
print_r($XXX);
echo "</pre>";
      [id] => 67589
            [created] => 2023-06-05T16:25:39.000Z
            [enterpriseId] => 1479
            [enterpriseLogicalId] => 9d5b9d19-3826-4c39-9ee5-91cb36cee60d
            [siteId] => 67781
            [activationKey] => QYN7-SHDZ-CVCT-2WJL
            [activationKeyExpires] => 2023-07-05T16:25:40.000Z
            [activationState] => ACTIVATED
            [activationTime] => 2023-06-06T19:15:40.000Z
            [softwareVersion] => 5.0.1.2
            [buildNumber] => R5012-20221107-GA-abdaf158ef
            [factorySoftwareVersion] => 5.0.0
            [factoryBuildNumber] => R5000-20220316-MR-GA
            [platformFirmwareVersion] => **NA**
            [platformBuildNumber] => **NA**(BIOS_3.50.0.9-16_CPLD_0x29_PIC_v20P), Upgradable
            [modemFirmwareVersion] => 
            [modemBuildNumber] => 
            [softwareUpdated] => 2023-06-08T16:03:02.000Z
            [selfMacAddress] => 00:00:00:00:00:00
            [deviceId] => CC6825F5-012C-47AD-BFF4-8E35F700C13D
            [logicalId] => bd18de4f-4fef-4b41-a3dc-bacc084675d9
            [serialNumber] => GKJZV43
            [modelNumber] => edge620
            [deviceFamily] => EDGE6X0
            [lteRegion] => 
            [name] => TP Villahermosa
            [dnsName] => 
            [description] => 
            [alertsEnabled] => 1
            [operatorAlertsEnabled] => 1
            [edgeState] => CONNECTED
            [edgeStateTime] => 0000-00-00 00:00:00
            [isLive] => 0
            [systemUpSince] => 2023-07-09T22:26:02.000Z
            [serviceUpSince] => 2023-07-09T22:26:47.000Z
            [lastContact] => 2023-07-14T21:18:07.185Z
            [serviceState] => IN_SERVICE
            [endpointPkiMode] => CERTIFICATE_OPTIONAL
            [haState] => UNCONFIGURED
            [haPreviousState] => UNCONFIGURED
            [haLastContact] => 2023-07-14T21:18:07.000Z
            [haSerialNumber] => 
            [bastionState] => UNCONFIGURED
            [modified] => 2023-07-14T21:18:07.000Z
            [customInfo] => 
            [isHub] => 

*/

//echo $html;
$jsonSearchResults[] =  array(
    'success' => 'YES',
    'data' => $html
);

//echo $forma;

echo json_encode ($jsonSearchResults);
return false;




?>


