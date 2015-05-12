<?php

	session_start();

	if ( !isset( $_GET['problemid'] ) ) {
		die();
	}

	
	include "models/database.php";
	include "models/tests.php";
	include "models/users.php";

	OpenDatabase();

	if ( isAdmin( $_SESSION['username'] ) ) {
		insertNUllTest( $_GET['problemid'] );
	}

	CloseDatabase();

	header( "Location: index.php?page=aetests&id=" . $_GET['problemid'] );
	
?>
