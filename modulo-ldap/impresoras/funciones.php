<?php

function ConectaLDAP() {
    include 'configuraciones.class.php';
    $ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
    if($ldapconn) {
        $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
    }
    $err=ldap_error($ldapconn);
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION,3);
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS,0);
    return $ldapconn;

}


