<?php
function GetDevUsersFromLDAPCells($how,$ofi,$conn) {
    include 'configuraciones.class.php';
    $err='';
    $data='';
    $out='';
    if ($how == "array") {
        $out=array();
    }
    $ldapconn=$conn;
    //error_reporting(E_ALL);
    //ini_set('display_errors', 'On');
    set_time_limit(30);
    $array1= array();
    $result = ldap_search($ldapconn,"ou=People,dc=transportespitic,dc=com", "(uid=*)");
    $err=ldap_error($ldapconn);
    $ldata = ldap_get_entries($ldapconn, $result);
    //echo $ldata["count"];
    for ($i=0; $i<$ldata["count"]; $i++) {
        //array_push($array1,$ldata[$i][$what][0]);
        //echo "<pre>";
        //print_r($ldata);
        if ($how == "htmltable") {
            $out .= "<tr><td>".$ldata[$i]['uid'][0]."</td></tr>";    
        }
        if ($how == "array") {
            $at=$ldata[$i]['uid'][0];
            //$out[$at]=$ldata[$i]['devicetag'][0];
            $out[$at]['labor']=$ldata[$i]['puesto'][0];
            $out[$at]['name']=$ldata[$i]['cn'][0];
        

            //array_push($out,$ldata[$i]['uid'][0]);
        }
        if ($how == "exists") {
            return $ldata["count"];
        }
        
        //echo "</pre>";
        //return false;
    }
    //echo $out;
    return $out;
}


function UpdateLDAPVAl($dn,$value,$vname) {
    $ldapBind=ConectaLDAP();
    $values[$vname][0] = array();
    $values[$vname][0]=$value;
    //echo $dn;
    //print_r($values);
    //ldap_modify($ldapBind, $dn, $values);
    if (@ldap_modify($ldapBind, $dn, $values)) {
        $RES="YES";
    } else {
         $RES=ldap_error($ldapBind);
    }
    return $RES;
}

?>