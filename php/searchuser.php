<?php
//$_POST["keyword"]='am';
//https://stackoverflow.com/questions/32197105/autocomplete-textbox-from-active-directory-usind-ldap-users-in-php
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


      if (preg_match("/^[A-Z]{2,4}$/",$_POST["keyword"],$matches)) {
            $ldapfilter="(oficina=".$_POST["keyword"].")";
            //echo $ldapfilter;
      }
      if (preg_match("/^MT1$/i",$_POST["keyword"],$matches)) {
            $ldapfilter="(oficina=".$_POST["keyword"].")";
            //echo $ldapfilter;
      }
      if (preg_match("/^\d{3,6}/i",$_POST["keyword"],$matches)) {
            $ldapfilter="(noempleado=".$_POST["keyword"].")";
            //echo $ldapfilter;
      }
      if (preg_match("/el mas guapo/",$_POST["keyword"],$matches)) {
            $ldapfilter="uid=jferia";
            //echo $ldapfilter;
      }
      if (preg_match("/el mas inteligente/",$_POST["keyword"],$matches)) {
            $ldapfilter="uid=iaguiar";
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
                        echo "No matches found! ";
                  }
            }
      }
      //echo json_encode($return_arr);
      echo $aut;
}

?>
  
