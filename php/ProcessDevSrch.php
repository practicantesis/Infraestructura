<?php
//$_POST["keyword"]='am';
//https://stackoverflow.com/questions/32197105/autocomplete-textbox-from-active-directory-usind-ldap-users-in-php

require('funciones.php');

//print_r($_POST);

$FOUND="DUNNO";
$conn=ConectaSQL('ocsweb');

$mes .='<br>ANALISIS DEL DISPOSITIVO<br>';

$mes.='<div>.</div>';
if (preg_match("/^[a-zA-Z]+$/i",$_POST["param"],$matches)) {
      //$mes.='<div>-</div>';
      $di=GetDeviceUserInfoFromLDAP($_POST["param"]);
      if ($di['count'] == 0) {
            $mes.='<div class="alert alert-danger" role="alert">';
            $mes.=" DEVICE USER NO ENCONTRADO EN LDAP";
            $mes.='</div>';
      }
      if ($di['count'] > 1) {
            $mes .='<div class="alert alert-success" role="alert">';            
            $mes.=" DEVICE USER DUPLICADO, SE ENCUENTRA ".$di['count']." VECES EN LDAP";
            $mes.='</div>';
            $FOUND="YES";
      }
      if ($di['count'] == 1) {
            $mes .='<div class="alert alert-success" role="alert">';
            $mes.=" DEVICE USER ENCONTRADO EN LDAP";
            $mes.='</div>';
            $FOUND="YES";
            $luser=GetDeviceTagInfoFromAssignedUserLDAP($_POST["param"]);
            //print_r($luser);
            if ($luser['count'] == 1) {
                  $mes .='<div class="alert alert-success" role="alert">';
                  $mes.=" EL DEV USER TIENE DISPOSITIVO ASIGNADO EN LDAP ".$luser[0]['devicetag'][0];
                  $mes.='</div>';
            }

            if ($luser['count'] == 0) {
                  $mes .='<div class="alert alert-danger" role="alert">';
                  $mes.=" EL DEV USER NO TIENE DISPOSITIVO ASIGNADO EN LDAP";
                  $mes.='</div>';
            }
      }            
}      

if (preg_match("/^CEL(\w\w\w)\d+$/i",$_POST["param"],$matches)) {
      //$mes.='<div>-</div>';
      $regsig = "DUNNO";      
      $mes .='<div class="alert alert-success" role="alert">';
      $mes .=  "TAG Con Formato valido para OFICINA ".$matches[1];
      $mes.='</div>';
      $ofi =  $matches[1];
      $reg = getRegFromSiglas($matches[1]);
      $regsig=getRegSiglaFromRegional($reg);
      $di=GetDeviceInfoFromLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","devicetag",$_POST["param"]);
      $dupocs=CheckOCSDupTAG($_POST["param"],$conn);


      if (is_array($dupocs)) {
            //print_r($dupocs);
            foreach ($dupocs as &$value) {
                  $sn=GetOCSSerialFromHWID($value,$conn);
                  $dupocsof=GetOCSOFIFromHwId($value,$conn);
                  $hids .= "<br>HWID: ".$value." SN:".$sn." OFI: ".$dupocsof."<br>";
            }
            $mes.='<div class="alert alert-danger" role="alert">';
            $mes.=" TAG DUPLICADO EN OCS : ".$hids;
            $mes.='</div>';


            if ($_POST["action"] == "CHANGE") {
                  $DeleteDUPOCSTag="YES";
            } else {
                  $propuesta .= "Eliminar TAG Duplicado en OCS (Realicelo manualmnente antes de continuar)<br>";
            }                                 
      }

      if ($di['count'] == 0) {
            $mes.='<div class="alert alert-danger" role="alert">';
            $mes.=" TAG NO ENCONTRADO EN LDAP";
            $mes.='</div>';
      }
      if ($di['count'] > 1) {
            $mes.=" TAG DUPLICADO, SE ENCUENTRA ".$di['count']." VECES EN LDAP";
      }
      if ($di['count'] == 1) {
            $mes .='<div class="alert alert-success" role="alert">';
            $mes.=" TAG ENCONTRADO EN LDAP";
            $mes.='</div>';
            if (preg_match("/^BAJA_CEL_(\w\w\w)$/i",$di[0]['deviceoffice'][0],$matchesb)) {
                  $mes.='<div class="alert alert-danger" role="alert">';
                  $mes.=" DEVICE DADO DE BAJA EN LDAP REGION ".$matchesb[1];
                  $mes.='</div>';
                  $dadodebaja="YES";
}
// test
if (1 == 1) {
                  if ($dadodebaja != "YES") {
            //} else {
                        $mes .='<div class="alert alert-success" role="alert">';
                        $mes.=" DEVICE EXISTENTE EN LDAP PERETENECE A OFICINA ".$di[0]['deviceoffice'][0]." <br>IMEI LDAP: ".$di[0]['deviceimei'][0]." <br>SERIAL LDAP: ".$di[0]['deviceserial'][0] ;
                        $mes.='</div>';

                  //if ($dadodebaja != "YES") {
                        if ($_POST["action"] == "CHANGE") {
                              $ChangeDeviceOffice="YES";
                        } else {
                              $propuesta .= "Cambiar el DeviceOffice en LDAP del dispositivo con TAG ".$_POST["param"]." a BAJA_CEL_".$regsig."<br>";
                        }
                  }  { $propuesta .= "sk0<br>"; }                        

                  // CHECK ASSIGNED USER
                  $ato=GetDeviceUserInfoFromLDAP($di[0]['deviceassignedto'][0]);
                  //print_r($ato);
                  if ($ato['count'] == 0) {
                        if ($di[0]['deviceassignedto'][0] != "BAJA") {
                              $mes.='<div class="alert alert-danger" role="alert">';
                              $mes.=" DEVICE USER DECLARADO PARA CELULAR ".$di[0]['deviceassignedto'][0]." NO ENCONTRADO EN LDAP";
                              $mes.='</div>';
                        }
                  }
                  if ($ato['count'] > 1) {
                        $mes .='<div class="alert alert-success" role="alert">';            
                        $mes.=" DEVICE USER DECLARADO PARA CELULAR ".$di[0]['deviceassignedto'][0]." DUPLICADO, SE ENCUENTRA ".$ato['count']." VECES EN LDAP";
                        $mes.='</div>';
                        $FOUND="YES";
                  }
                  if ($ato['count'] == 1) {
                        $mes .='<div class="alert alert-success" role="alert">';
                        $mes.=" DEVICE USER DECLARADO PARA CELULAR ".$di[0]['deviceassignedto'][0]." ENCONTRADO EN LDAP";
                        $mes.='</div>';
                        $FOUND="YES";
                        if ($_POST["action"] == "CHANGE") {
                              $UnasignUserFromDev="YES";
                        } else {
                              $propuesta .= "Cambiar el usuario del dispositivo con TAG ".$_POST["param"]." a BAJA<br>";
                        }                                 
                  }            
                  // CHECK serial for TAG ON AIRWATCH
                  $airwatchPorSerie=QueryToAirwatchAPI('DEVICE',$di[0]['deviceserial'][0]);
                  $eljson = json_decode ($airwatchPorSerie, true);
                  if ($eljson == "404") {
                        $mes .='<div class="alert alert-danger" role="alert">';
                        $mes.=" NUMERO DE SERIE ".$di[0]['deviceserial'][0]." NO ENCONTRADO EN AIRWATCH ";
                        $mes.='</div>';
                        $FOUND="AW404";

                  } else {
                        if ($_POST["param"] == $eljson['DeviceFriendlyName']) {
                              $mes .='<div class="alert alert-success" role="alert">';
                              $mes.=" NUMERO DE SERIE ".$di[0]['deviceserial'][0]." ENCONTRADO EN AIRWATCH Y COINCIDE EL DeviceFriendlyName CON EL TAG ";
                              $mes.='</div>';
                              $FOUND="AWYES";
                              if ($_POST["action"] == "CHANGE") {
                                    $DeleteAirwatchDevice="YES";
                              } else {
                                    //print_r($eljson);
                                    if (! str_contains($propuesta, "Eliminar IMEI ".$eljson['Imei']." y serie ".$eljson['SerialNumber']." de Airwatch<br>")) { 
                                          $propuesta .= "Eliminar IMEI ".$eljson['Imei']." y serie ".$eljson['SerialNumber']." de Airwatch<br>";
                                    }                                 
                              }

                        } else {
                              $mes .='<div class="alert alert-danger" role="alert">';
                              $mes.=" NUMERO DE SERIE ".$di[0]['deviceserial'][0]." ENCONTRADO EN AIRWATCH PERO NO COINCIDE EL DeviceFriendlyName (".$eljson['DeviceFriendlyName'].") CON EL TAG ";
                              $mes.='</div>';
                              $FOUND="AWPEND";
                              if ($_POST["action"] == "CHANGE") {
                                    $DeleteAirwatchDevice="YES";
                              } else {
                                    $propuesta .= "SE SUGIERE VERIFICAR SERIE/IMEI/FRIENDLY EL DISPOSITIVO EN AIRWATCH ANTES DE APLICAR Eliminar IMEI ".$di[0]['deviceimei'][0]." de Airwatch Y SI ESTA SEGURO, APLICAR<br>";
                              }


                        }
                  }
                  // CHECK imei for TAG ON AIRWATCH
                  $airwatchPorImei=QueryToAirwatchAPI('DEVICEperIMEI',$di[0]['deviceimei'][0]);
                  $eljsoni = json_decode ($airwatchPorImei, true);
                  if ($eljsoni == "404") {
                        $mes .='<div class="alert alert-danger" role="alert">';
                        $mes.=" IMEI ".$di[0]['deviceimei'][0]." NO ENCONTRADO EN AIRWATCH ";
                        $mes.='</div>';
                        $FOUND="AWI404";
                  } else {
                        if ($_POST["param"] == $eljsoni['DeviceFriendlyName']) {
                              $mes .='<div class="alert alert-success" role="alert">';
                              $mes.=" IMEI ".$di[0]['deviceimei'][0]." ENCONTRADO EN AIRWATCH Y COINCIDE EL DeviceFriendlyName CON EL TAG ";
                              $mes.='</div>';
                              $FOUND="AWYES";
                              if ($_POST["action"] == "CHANGE") {
                                    $DeleteAirwatchDevice="YES";
                              } else {
                                    if (! str_contains($propuesta, "Eliminar IMEI ".$eljsoni['Imei']." y serie ".$eljsoni['SerialNumber']." de Airwatch<br>")) { 
                                          $propuesta .= "Eliminar IMEI ".$eljsoni['Imei']." y serie ".$eljsoni['SerialNumber']." de Airwatch<br>";
                                    }                                 
                              }
                        } else {
                              $mes .='<div class="alert alert-danger" role="alert">';
                              $mes.=" IMEI ENCONTRADO EN AIRWATCH PERO NO COINCIDE EL DeviceFriendlyName CON EL TAG (".$eljsoni['DeviceFriendlyName'].")";
                              $mes.='</div>';
                              $FOUND="AWPEND";
                              if ($_POST["action"] == "CHANGE") {
                                    $DeleteAirwatchDevice="YES";
                              } else {
                                    $propuesta .= "SE SUGIERE VERIFICAR EL DISPOSITIVO EN AIRWATCH ANTES DE APLICAR Eliminar IMEI ".$di[0]['deviceimei'][0]." de Airwatch<br>";
                              }

                        }
                  }
                  // GetOCSTAG($serial,$conn)
                  // GetOCSHwIDFromIMEI($imei,$conn) {
                  // GetOCSImeiFromTag($tag,$conn) {
                  // GetOCSInfoFromTAG($tag,$what,$conn) 
                  // 
                  //
                  
                  $ocshw=GetOCSHwIDFromIMEI($di[0]['deviceimei'][0],$conn);
                  $ocstg=GetOCSTAGFromHwId($ocshw,$conn);
                  $ocsof=GetOCSOFIFromHwId($ocshw,$conn);
                  // OCS SERIAL
                  $ocsserial=GetOCSTAG($di[0]['deviceserial'][0],$conn);
                  if ($ocsserial == "NOTFOUND") {
                        $mes .='<div class="alert alert-danger" role="alert">';
                        $mes.=" SERIE DECLARADA EN LDAP ".$di[0]['deviceserial'][0]." PARA DEVICE NO EXISTE EN OCS (OFI: ".$ocsof." ) (TAG: ".$ocstg." ) ";
                        $mes.='</div>';
                        $FOUND="OCSNOSERIE";
                  }
                  if ($ocsserial == $_POST["param"]) {
                        $mes .='<div class="alert alert-success" role="alert">';
                        $mes.=" SERIE DECLARADA EN LDAP ".$di[0]['deviceserial'][0]." PARA DEVICE SI EXISTE EN OCS (HWID: ".$ocshw.") (OFI: ".$ocsof." ) (TAGOCS: ".$ocstg." ) ";
                        $mes.='</div>';
                        $FOUND="OCSNOSERIE";

if (! preg_match("/^BAJA_CEL_(\w\w\w)$/i",$ocsof,$matchesc)) {                        
                        if ($_POST["action"] == "CHANGE") {
                              $ChangeOCSTAG="YES";
                        } else {
                              $propuesta .= "Cambiar la oficina del HWID ".$ocshw." de OCS a BAJA_CEL_".$regsig."<br>";
                        }
} else { $propuesta .= "sk1<br>"; }                        

                  }
                  // OCS OMEI
                  //echo $ocsimei=GetOCSImeiFromTag($_POST["param"],$conn);
                  
                  if ($ocshw == "NOTFOUND_or_DUP") {
                        $mes .='<div class="alert alert-danger" role="alert">';
                        $mes.=" IMEI NO ENCONTRADO O DUPLICADO EN OCS  ";
                        $mes.='</div>';
                        $FOUND="OCSIMEINOTFOUND";
                  } else {
                        if ($_POST["param"] == $ocstg) {
                              $mes .='<div class="alert alert-success" role="alert">';
                              $mes.=" IMEI ENCONTRADO EN OCS (HWID: ".$ocshw.") Y COINCIDE EL CON EL IMEI DE LDAP (OFI: ".$ocsof." ) (TAGOCS: ".$ocstg." )";
                              $mes.='</div>';
                              $FOUND="OCSIMEIYES";


if (! preg_match("/^BAJA_CEL_(\w\w\w)$/i",$ocsof,$matchesc)) {                        
                              if ($_POST["action"] == "CHANGE") {
                                    $ChangeOCSTAG="YES";
                              } else {
                                    if (! str_contains($propuesta, "Cambiar la oficina del HWID ".$ocshw." de OCS a BAJA_CEL_".$regsig)) { 
                                          $propuesta .= "Cambiar la oficina del HWID ".$ocshw." de OCS a BAJA_CEL_".$regsig;
                                    }                                 
                                    
                              }
} else { $propuesta .= "sk2<br>"; }                                                      


                        } else {
                              $mes .='<div class="alert alert-danger" role="alert">';
                              $mes.=" IMEI ENCONTRADO EN OCS (HWID: ".$ocshw.") PERO NO COINCIDE CON EL IMEI DE LDAP  (TAGOCS: ".$ocstg." )";
                              $mes.='</div>';
                              $FOUND="OCSWRONMGIMEI";
                        }
                  }


                  if ($ChangeOCSTAG=="YES") {
                        $propuesta .= "CAMBIANDO OFICINA DEL HWID - ".$ocshw." OCS A: BAJA_CEL_".$regsig."<br>";
                        $TAGCHG=UpdateOCSOffice($ocshw,"BAJA_CEL_".$regsig,$conn);
                        
                        // llamar cambio
                  }
                  if ($DeleteAirwatchDevice=="YES") {
                        $propuesta .= "BORRANDO DISPOSITIVO CON IMEI ".$di[0]['deviceimei'][0]." DE AIRWATCH<br>";
                        $outapi=QueryToAirwatchAPI("DeleteDEVICEperIMEI",$di[0]['deviceimei'][0]);
                        // llamar cambio
                  }
                  if ($UnasignUserFromDev=="YES") {
                        $propuesta .= "CAMBIANDO EL DeviceUser DEL TAG ".$_POST["param"]." A BAJA<br>";
$updateuserldap=UpdateLDAPVAl('DeviceTAG='.$_POST["param"].',ou=Celulares,ou=Devices,dc=transportespitic,dc=com',"BAJA","deviceassignedto");
                        // llamar cambio
                  }
                  if ($ChangeDeviceOffice=="YES") {
                        $propuesta .= "CAMBIANDO EL DeviceOffice DEL TAG ".$_POST["param"]." A BAJA_CEL_".$regsig."<br>";
            $updateofices=UpdateLDAPVAl('DeviceTAG='.$_POST["param"].',ou=Celulares,ou=Devices,dc=transportespitic,dc=com',"BAJA_CEL_".$regsig,"deviceoffice");
                        // llamar cambio

                  }
                  logDevChange($_SESSION['user'],"DELETECELL",$_POST["param"],$propuesta,$conn);
                  $propuesta .= $TAGCHG.$outapi.$updateuserldap.$updateofices;
            }                 
      }
      //print_r($di);
} else {
      if ($FOUND != "YES") {
            $mes.='<div class="alert alert-danger" role="alert">';
            $mes.=" NO VEO TAG NI DEVUSER CON ESO QUE PUSISTE, DE CUAL FUMASTE?";
            $mes.='</div>';
      }
}



$mes .='<strong>';
if ($_POST["action"] == "CHANGE") {
      $mes .='<br>APLICANDO CAMBIOS<br>';
} else {
      $mes .='<br>CAMBIOS PROPUESTOS<br>';
} 
$mes .='</strong>';
$mes .='<li>'.$propuesta;


$jsonSearchResults[] =  array(
    'success' => 'YES',
    'mes' => $mes,
    'ofi' => $ofi,
);
echo json_encode ($jsonSearchResults);

return false;


/*
MySQL [ocsweb]> select * from config where TVALUE like '%BAJA_CEL_%';
+--------------------------+--------+--------------+----------+
| NAME                     | IVALUE | TVALUE       | COMMENTS |
+--------------------------+--------+--------------+----------+
| ACCOUNT_VALUE_OFICINA_49 |     49 | BAJA_CEL_NOR | NULL     |
| ACCOUNT_VALUE_OFICINA_50 |     50 | BAJA_CEL_NST | NULL     |
| ACCOUNT_VALUE_OFICINA_51 |     51 | BAJA_CEL_OCT | NULL     |
| ACCOUNT_VALUE_OFICINA_52 |     52 | BAJA_CEL_SUR | NULL     |
| ACCOUNT_VALUE_OFICINA_53 |     53 | BAJA_CEL_CNT | NULL     |
| ACCOUNT_VALUE_OFICINA_61 |     61 | BAJA_CEL_TRA | NULL     |
+--------------------------+--------+--------------+----------+
+-------------+--------------+----------+----------+
| HARDWARE_ID | TAG          | fields_3 | fields_4 |
+-------------+--------------+----------+----------+
|          28 | NOTAG        | 57       |          |
insert into config values ('ACCOUNT_VALUE_OFICINA_61','61','BAJA_CEL_TRA',NULL);
MySQL [ocsweb]> describe EventosDevices;
+-------------+------------------+------+-----+---------+----------------+
| Field       | Type             | Null | Key | Default | Extra          |
+-------------+------------------+------+-----+---------+----------------+
| ID          | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
| quien       | varchar(10)      | YES  |     | NULL    |                |
| tipo        | varchar(15)      | YES  |     | NULL    |                |
| tag         | varchar(15)      | YES  |     | NULL    |                |
| fecha       | datetime         | YES  |     | NULL    |                |
| descripcion | text             | YES  |     | NULL    |                |
+-------------+------------------+------+-----+---------+----------------+
*/


return false;

if(!empty($_POST["keyword"])) {
      $ldapserver = 'ldap.tpitic.com.mx';
      $ldapuser      = 'cn=feria,dc=transportespitic,dc=com';  
      $ldappass     = 'sistemaspitic';
      $ldaptree    = "ou=People,dc=transportespitic,dc=com";
      $ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
      //$ldapfilter="(uid=".$_POST["keyword"]."*)";

      $ldapfilter="(|(uid=".$_POST["keyword"]."*)(sn=".$_POST["keyword"]."*)(givenname=".$_POST["keyword"]."*)(puesto=".$_POST["keyword"]."))";
      $aut='';


      if (strlen($_POST["keyword"]) == 17) {
            $_POST["keyword"]=strtoupper($_POST["keyword"]);
            echo $_POST["keyword"];
            if (filter_var($_POST["keyword"] , FILTER_VALIDATE_MAC)) {
                  $ldapfilter="(|(lanmac=".$_POST["keyword"].")(wifimac=".$_POST["keyword"]."))";
            }
      }
      if (filter_var($_POST["keyword"] , FILTER_VALIDATE_IP)) {
            $ldapfilter="(|(lanip=".$_POST["keyword"].")(voiceip=".$_POST["keyword"].")(DeviceIP=".$_POST["keyword"]."))";
            $ldaptree    = "dc=transportespitic,dc=com";

      }

      if (preg_match("/^192\.168\.\d+\.$/i",$_POST["keyword"],$matches)) {
            $ldapfilter="(|(lanip=".$_POST["keyword"]."*)(voiceip=".$_POST["keyword"]."*))";
            //echo $ldapfilter;
      }


      if (preg_match("/^[A-Z]{2,4}$/i",$_POST["keyword"],$matches)) {
            $ldapfilter="(oficina=".$_POST["keyword"].")";
            //echo $ldapfilter;
      }



//echo $ldapfilter;


      if($ldapconn) {
            $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
            if ($ldapbind) {
                  $result = ldap_search($ldapconn,$ldaptree, $ldapfilter) or die ("Error in search query: ".ldap_error($ldapconn));
                  $ldata = ldap_get_entries($ldapconn, $result);
                  for ($i = 0; $i < $ldata["count"]; $i++) {
                        
                        //$aut .= '<li> <a href="#" onClick="selectUser('."'".$ldata[$i]["uid"][0]."'".');" >'.$ldata[$i]["uid"][0].'</a></li>';
                        //if (isset($ldata[$i]["uid"][0])) {
                              $return_arr[] =  $ldata[$i]["uid"][0];
                              $aut .= '<li> <a href="#" onClick="selectUser('."'".$ldata[$i]["uid"][0]."'".');" >'.$ldata[$i]["uid"][0].' - '.$ldata[$i]["givenname"][0].' '.$ldata[$i]["sn"][0].' ('.$ldata[$i]["oficina"][0].') </a></li>';
                        //} else {
                        //      $aut .= '<li>xx</li>';
                        //}
                        //echo "<pre>";
                        //print_r($ldata);
                        //echo $ldata["count"];
                        //echo  $ldata[$i]["uid"][0];
                        //echo "</pre>";
                  }
                  if ($i == 0) {
                        echo "No matches found!";
                  }
            }
      }
      //echo json_encode($return_arr);
      echo $aut;
}


/*
1,- Eliminar telefono de Airwatch
2.- Cambiar la oficina del TAG de LDAP CELTRA168 de TRA a BAJACELNOR
3.- Al usuario jpenilla se cambiara 
4.- Cambiar  DeviceAssignedTo: jpenilla del TAG de LDAP CELTRA168 a BAJA
5,- Cambiar el TAG del HWID 33056 de OCS a BAJA_CELTRA168

Functionality – Deletes the device information from the AirWatch Console and un-enrolls the device.
HTTP Method – DELETE
API URI – https://host/api/mdm/devices/{id}
You can delete a device using the following parameter:
l Alternate device ID type – https://host/api/mdm/devices?searchby={searchby}&id={id}
*/

?>
  