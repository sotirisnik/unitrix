<?php
function ldap_authenticate($user, $password) {
	// Active Directory server
	$ldap_host = "LDAP://students.cs.unipi.gr";

	// Active Directory DN
	$ldap_dn = ",ou=users,dc=cs,dc=unipi,dc=gr";

	$ldap = ldap_connect($ldap_host) or die ("Could not connect to butters");

	ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);

	$ldaprdn = 'uid=' . $user . $ldap_dn; 
	//echo $ldaprdn;

	$bind = ldap_bind($ldap, $ldaprdn, $password);

	$justthese = array("gecos");

	if($bind) {
		$result = ldap_search( $ldap, $ldaprdn, "name=$user", $justthese ) or die ("Error in query");
		$data = ldap_get_entries($ldap, $result);
		
		$_SESSION['fullname'] = $data[0]['gecos'][0];

		return true;
	}
	return false;
}
?>

