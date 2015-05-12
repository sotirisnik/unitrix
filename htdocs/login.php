<?php
	session_start();
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	include "models/database.php";
	include "models/users.php";
	include "models/ldap_auth.php";	

	//echo $username . " " . $password;
	
	OpenDatabase();
	
	$username = mysql_real_escape_string( $username );
	$password = mysql_real_escape_string( $password );
	
	$fl = 0;
	
	if ( AuthenticateUser( $username, $password ) ) {
		$fl = 1;
		//session_start();
		$_SESSION['username'] = $username;
		$_SESSION['fullname'] = FindUserByUsername( $_SESSION['username'] )['fullname'];
		addFullname( $_SESSION['username'], $_SESSION['fullname'], 0 );
	}

	if ( $fl == 0 && ldap_authenticate( $username, $password ) == true ) {
		$fl = 1;
		$_SESSION['username'] = strtolower( $username );
		addFullname( $_SESSION['username'], $_SESSION['fullname'], 0 );
	}

	CloseDatabase();
		
	if  ( $fl == 0 ) {
		header( "Location: index.php?error=1" );
	}else {
		header( "Location: index.php?error=success" );
	}
?>
