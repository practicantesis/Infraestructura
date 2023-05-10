<?php
//$_POST["keyword"]='am';
//https://stackoverflow.com/questions/32197105/autocomplete-textbox-from-active-directory-usind-ldap-users-in-php


require('funciones.php');

if (($_POST["action"] == "DUNNO") and ($_POST["nukedevuser"] == "YES")) {
      //print_r($_POST);
      $jsonSearchResults[] =  array(
          'success' => 'NO',
          'mes' => "Para borrar el Devuser Desde Aqui debe ser junto con el telefono, si desea borrar solo el devuser use el apartado Device Users",
      );
      echo json_encode ($jsonSearchResults);
      
      return false;
}

//print_r($_POST);
//return false;
// Realizar cambios hacia ottras cosas, por ejemplo, borrar tags duplicados

$FOUND="DUNNO";
$conn=ConectaSQL('ocsweb');

$mes .='<br>ANALISIS DEL DISPOSITIVO<br>';
$huerfano="DUNNO";
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
            $luser=GetDeviceTagInfoFromAssignedUserLDAP($_POST["param"],"count");
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
                  //if ($_POST["action"] == "CHANGE") {
                  if ($_POST["nukedevuser"] == "YES") {      
                        //$DeleteDUPOCSTag="YES";
                        DeleteLDAPUser("duusernname=".$_POST["param"].",ou=DeviceUsers,dc=transportespitic,dc=com");
                  } else {
                        $propuesta .= "Si palomea Eliminar Usuario se Borrara el Devuser ".$_POST["param"]." --> ("."duusernname=".$_POST["param"].",ou=DeviceUsers,dc=transportespitic,dc=com".")<br>";
                  }                                 
            }
      }            
}      

// CEL
if (preg_match("/^(C[E|P][L|H])(\w\w\w)\d+$/i",$_POST["param"],$matches)) {
      //$mes.='<div>-</div>';
      $regsig = "DUNNO";      
      $mes .='<div class="alert alert-success" role="alert">';
      $mes .=  "TAG Con Formato valido para OFICINA ".$matches[2]. " TIPO:".$matches[1];
      $mes.='</div>';
      $ofi =  $matches[2];
      $tipo =  $matches[1];
      $reg = getRegFromSiglas($matches[2]);
      $regsig=getRegSiglaFromRegional($reg);
      $di=GetDeviceInfoFromLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","devicetag",$_POST["param"]);
      $dupocs=CheckOCSDupTAG($_POST["param"],$conn);


      // EVITAR BORRAR DEVUSER SI EXISTE EN PEOPLE EL USERNAME
      if (($_POST["action"] == "CHANGE") and ($_POST["nukedevuser"] == "YES")) {
            $existinppl = CheckExistentValueLDAP("ou=People,dc=transportespitic,dc=com","uid",$di[0]['deviceassignedto'][0]);
            //NO quiere decir que el usuario si existe en People
            if ($existinppl == "NO") {
                  $jsonSearchResults[] =  array(
                        'success' => 'NO',
                        'mes' => "USUARIO EXISTENTE EN PEOPLE"
                  );
                  echo json_encode ($jsonSearchResults);
                  return false;
            }
      }
      //      

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
                  //print_r($di);
            }
            // test
            if (1 == 1) {
            if ($dadodebaja != "YES") {
            //} else {
                        $mes .='<div class="alert alert-success" role="alert">';
                        $mes.=" DEVICE EXISTENTE EN LDAP PERETENECE A OFICINA ".$di[0]['deviceoffice'][0]." <br>IMEI LDAP: ".$di[0]['deviceimei'][0]." <br>SERIAL LDAP: ".$di[0]['deviceserial'][0] ;
                        $mes.='</div>';
                        $borrable="SI";
                        //if ($dadodebaja != "YES") {
                        if ($_POST["action"] == "CHANGE") {
                              $ChangeDeviceOffice="YES";
                        } else {
                              $propuesta .= "Cambiar el DeviceOffice en LDAP del dispositivo con TAG ".$_POST["param"]." de ".$di[0]['deviceoffice'][0]." a BAJA_CEL_".$regsig."<br>";
                        }
                  }  { $propuesta .= "-=-<br>"; }                        
                  // CHECK ASSIGNED USER
                  $ato=GetDeviceUserInfoFromLDAP($di[0]['deviceassignedto'][0]);
                  //print_r($ato);
                  if ($ato['count'] == 0) {
                        if ($di[0]['deviceassignedto'][0] != "BAJA") {
                              $mes.='<div class="alert alert-warning" role="alert">';
                              $mes.=" DEVICE USER DECLARADO PARA CELULAR ".$di[0]['deviceassignedto'][0]." NO ENCONTRADO EN LDAP";
                              $mes.='</div>';
                              if ($_POST["action"] == "CHANGE") {
                                    $UnasignUserFromDev="YES";
                              } else {
                                    $propuesta .= "Cambiar el usuario del dispositivo con TAG ".$_POST["param"]." de ".$di[0]['deviceassignedto'][0]." a BAJA<br>";
                              }                                 
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
                              $propuesta .= "Cambiar el usuario del dispositivo con TAG ".$_POST["param"]." de ".$di[0]['deviceassignedto'][0]." a BAJA<br>";
                        }                                 
                  }

                  // CHECK serial for TAG ON AIRWATCH
                  $snl=strlen($di[0]['deviceserial'][0]);
                  if ($snl != 0) {
                        $airwatchPorSerie=QueryToAirwatchAPI('DEVICE',$di[0]['deviceserial'][0]);
                        //print $airwatchPorSerie;
                        if ($airwatchPorSerie == "UNAUTH") {
                              $jsonSearchResults[] =  array(
                                  'success' => 'NO',
                                  'mes' => 'UNAUTHORIZED API',

                              );
                              echo json_encode ($jsonSearchResults);
                              return false;
                        }
                        $eljson = json_decode ($airwatchPorSerie, true);
                        if ($eljson == "404") {
                              $mes .='<div class="alert alert-warning" role="alert">';
                              $mes.=" NUMERO DE SERIE ".$di[0]['deviceserial'][0]." NO ENCONTRADO EN AIRWATCH $snl ";
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
                              $mes .='<div class="alert alert-warning" role="alert">';
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
                  } else {
                        $mes .='<div class="alert alert-danger" role="alert">';
                        $mes.=" SERIE PARA EL TAG (".$_POST["param"].") NO DECLARADA EN LDAP";
                        $mes.='</div>';
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
} else { $propuesta .= "- -<br>"; }                        

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
} else { $propuesta .= "-.-<br>"; }                                                      


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

                  if (($_POST["action"] == "CHANGE") and ($_POST["nukedevuser"] == "YES")) {
                        $existinppl = CheckExistentValueLDAP("ou=People,dc=transportespitic,dc=com","uid",$di[0]['deviceassignedto'][0]);
                        if ($existinppl == "YES") {
                              $dele=DeleteLDAPUser("duusernname=".$di[0]['deviceassignedto'][0].",ou=DeviceUsers,dc=transportespitic,dc=com");      
                        } else {
                              $mes .='<br>ATENCION!!!! EL DUUSERNAME '.$di[0]['deviceassignedto'][0].' TAMBIEN EXISTE EN PEOPLE<br>';
                        }
                        
                  }                        


            }                 
      }
      //print_r($di);
} else {
      if ($FOUND != "YES") {
            $mes.='<div class="alert alert-danger" role="alert">';
            $mes.=" NO EXISTE TAG NI DEVUSER CON ESA BUSQUEDA";
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
    'borrable' => $borrable,
    'devuser' => $di[0]['deviceassignedto'][0],
    'ofi' => $ofi,
);
echo json_encode ($jsonSearchResults);

return false;

