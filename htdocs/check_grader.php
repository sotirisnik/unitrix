<?php

	include "database.php";
	
	OpenDatabase( );
	
	$sql = "SELECT * FROM sources WHERE examined = '0' ";
	
	if ( mysql_num_rows( mysql_query( $sql ) ) >= 1 ) {
		echo "Something is being examined";
		die();
	}
	
	$sql = "SELECT * FROM sources WHERE examined = '-1' ";
	$res = mysql_query( $sql );
	
	if ( mysql_num_rows($res) == 0 ) {
		echo "Nothing to be examined";
		die();
	}
	
	$obj = mysql_fetch_array( $res );
	
	$msg = "php.exe C:\xampp\htdocs\unitrix\grader.php -compiler=1" . " -id=" . $obj['id'];
	echo $msg;
	shell_exec( $msg );
	
	CloseDatabase();
	
	echo "ok";
	
?>