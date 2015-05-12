<?php

	$servername = "localhost";
	$dbuser = "db_user";
	$dbpass = "db_password";
	$dbname = "grader";

	function OpenDatabase( ) {
		global $servername, $dbuser, $dbpass, $dbname;
		mysql_connect( $servername, $dbuser, $dbpass ) or die ( "cannot connect" );
		mysql_select_db( $dbname );
		mysql_query( "SET NAMES 'utf8'" );
	}
	
	function CloseDatabase( ) {
		mysql_close();
	}

	global $conn;

	function OpenPDODatabase( ) {
		global $servername, $dbuser, $dbpass, $dbname, $conn;
		try {
    		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbuser, $dbpass );
    		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//$conn->setAttribute( PDO::ATTR_AUTOCOMMIT, 0 );
    		echo "Connected successfully"; 
    	}catch( PDOException $e ) {
    		echo "Connection failed: " . $e->getMessage();
    	}

	}

	function ClosePDODatabase( ) {
		$conn = null;
	}
	
?>
