<?php 

require('/var/www/html/INFRAESTRUCTURA-DESARROLLO/jferia/Infraestructura/php/funciones.php');

$ldapconn=ConectaLDAP();
$grupos=GetGroupMembers("*","array","PAgroupname");
$sinpalo=GetBranchesWihoutPA();

$usuarios=GetUsersFromLDAP("ou=People,dc=transportespitic,dc=com","perms");
echo "<pre>";
//print_r($usuarios);
echo "</pre>";
for ($iii=0; $iii<count($usuarios); $iii++) {
	$chk="DUNNO";
	$matches[1] = 0;
	$uab=GetUserInfoFromLDAPConn($usuarios[$iii],"array",$ldapconn);
	if (in_array($uab[0]["oficina"][0], $sinpalo)) {
		if ($uab[0]["travelling"][0] == "YES" ) {
			//echo "Checando ".$usuarios[$iii]." sin palo con traveling <br>";
			$chk="YES";
		} else {
			//echo "Quitando ".$usuarios[$iii]." sin palo sin traveling<br>";	
		}
	} else {
		if ($uab[0]["accesosdered"][0] == "9") {
			//echo "Saltando ".$usuarios[$iii]." CON nivel 9 <br>";	
		} else {
			//echo "Checando ".$usuarios[$iii]." CON palo <br>";
			$chk="YES";
		}
	}
	if ($chk == "YES") {
		//echo "boo<br>";
		$gpo=GetPAGroupsForUser($usuarios[$iii],"array",$ldapconn);
		if (count($gpo) > 1) {
			echo $usuarios[$iii]." Esta en mas de 2 grupos ".$gpo[0]." <br>";
		} else {
			if (preg_match('/cn=PA_Nivel(\d)/',$gpo[0],$matches)) {
			//if (preg_match('/uid=(\w+),ou=People/', $dn, $match) == 1) {
				//echo $usuarios[$iii]." Esta en el grupo ".$matches[1]." <br>";
			}
			if ($uab[0]["accesosdered"][0] != $matches[1]) {
				if (strlen($matches[1]) > 0 ) {
					echo $usuarios[$iii]." Esta en el grupo ".$matches[1]." pero NO concuerda con su valor de LDAP ".$uab[0]["accesosdered"][0]." <br>";	
				} else {
					echo $usuarios[$iii]."  NOOOOOOO esta en el grupo ".$matches[1]." de  su valor de LDAP es ".$uab[0]["accesosdered"][0]." <br>";	
				}
				
			}
			//echo $gpo[0]."<pre>";	
			//print_r($gpo);
			//echo "</pre>";	
			//echo "<br>";	
		}

	}
}					


/*						

	echo $usuarios[$iii]." Nivel de acceso en People: ".$uab[0]["accesosdered"][0]."  OFI: ".$uab[0]["oficina"][0]."<br>";
	//function CheckExistentValueLDAP("ou=PaloAltoPerms,ou=groups,dc=transportespitic,dc=com",$what,$val) {
	$gpo=GetPAGroupsForUser($usuarios[$iii],"array");
	echo "<pre>";	
	print_r($gpo);
	echo "</pre>";	
	echo "<br>";	
*/	
	

?>
