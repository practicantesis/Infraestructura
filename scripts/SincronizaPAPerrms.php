<?php 

require('/var/www/html/INFRAESTRUCTURA-DESARROLLO/jferia/Infraestructura/php/funciones.php');

$ldapconn=ConectaLDAP();
$grupos=GetGroupMembers("*","array","PAgroupname");

/*
$usuarios=GetUsersFromLDAP("ou=People,dc=transportespitic,dc=com","perms");
echo "<pre>";
//print_r($usuarios);
echo "</pre>";
for ($iii=0; $iii<count($usuarios); $iii++) {
	$uab=GetUserInfoFromLDAPConn($usuarios[$iii],"array",$ldapconn);
	echo $usuarios[$iii]." Nivel de acceso:".$uab[0]["accesosdered"][0]." <br>";
}	
//return false;
*/


$sinpalo=GetBranchesWihoutPA();
//print_r($sinpalo);
$fpa=0;

echo "<pre>";
for ($i=0; $i<count($grupos); $i++) {
	if (preg_match("/cn=(PA_Nivel(\d))\,/i",$grupos[$i], $match)) {
	//if (preg_match("/cn=(PA_Nivel(8))\,/i",$grupos[$i], $match)) {
		echo "Analizando Usuarios del Grupo ".$match[1]."<br>=================================<br>";
		//echo "Nivel Detectado: ".$match[2]."<br>---------------------<br>";
		$miembros=GetGroupMembers($match[1],"array","PAgroupmembers");
		for ($ii=0; $ii<count($miembros); $ii++) {
			//echo $miembros[$ii]."<br>";
			if (preg_match("/uid=(\w+),ou/i",$miembros[$ii], $matchb)) {
				$status="";
				$ua=GetUserInfoFromLDAPConn($matchb[1],"array",$ldapconn);
				if ($ua[0]["accesosdered"][0] == $match[2]) {
					$status="OK!";
				} else {
					$status="ERROR";
					echo $ua[0]["oficina"][0]."->".$matchb[1]." User LDAP: ".$ua[0]["uid"][0]." -> Acceso LDAP -> -".$ua[0]["accesosdered"][0]."- -> $status  (".$match[2].") <br>";

				}
				if ($ua[0]["accesosdered"][0] == "9") {
					echo $ua[0]["uid"][0]." Tiene nivel 9 el People, quitandolo del grupo ".$grupos[$i]."<br>";
					$dnnukex="uid=".$ua[0]["uid"][0].",ou=People,dc=transportespitic,dc=com";
					$resv = DeleteLDAPVAl($grupos[$i],$dnnukex,"member");
					echo $resv ."<br>";
				}

				if (strlen($ua[0]["sn"][0]) < 1 ) {
					$dnnuke="uid=".$matchb[1].",ou=People,dc=transportespitic,dc=com";
					echo $dnnuke." NO EXISTE, en people, quitandolo del grupo ".$grupos[$i]."<br>";
					$res = DeleteLDAPVAl($grupos[$i],$dnnuke,"member");
					echo $res ."<br>";
					//sleep(10);
				}

				if (in_array($ua[0]["oficina"][0], $sinpalo)) {
					if ($ua[0]["travelling"][0] == "YES" ) {
						//echo '########%%%%%%############## Es de una oficina sin palo alto CON TRAVELLING #######%%%%%%%%%%%%%%%%%%%%%##########<br>';
						$status="OK!";
					} else {
						echo 'Eliminando '.$matchb[1]." Es de una oficina SIN PA<br>";
						$dnnukex="uid=".$matchb[1].",ou=People,dc=transportespitic,dc=com";
						$resx = DeleteLDAPVAl($grupos[$i],$dnnukex,"member");
						echo $resx ."<br>";
					}
				//    
				//    $fpa++;
				}
				//print_r($ua);
			}
		}
		//print_r($miembros);
	}

}	

//print_r($miembros);
//print_r($grupos);
//echo "</pre> Total de usuarios sin OFI con PA:".$fpa;

//GetGroupsForUser($user,$how) {
//function GetGroupMembers($user,$how,$what) {
//function smbGetGroupMembers($user,$how,$what) {
//GetUserInfoFromLDAP($user,$how)

?>