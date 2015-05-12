<?php

	if ( !isset($_GET['id']) ) {
		header ( "Location: index.php?page=manage" );
		die();
	}

	include "models/database.php";

	OpenDatabase();
	
	$id = $_GET['id'];
	
	$sql = "SELECT visible FROM competitions WHERE id='" . $id ."'";// LIMIT 1";
	$res = mysql_query( $sql );
	$obj = mysql_fetch_array( $res );
	$obj['visible'] = 1 - $obj['visible'];
	
	$sql = "UPDATE competitions SET visible = '" . $obj['visible'] . "' WHERE id ='" . $id . "'";
	mysql_query( $sql ) or die( mysql_error() );
	CloseDatabase();
	
	header( "Location: index.php?page=manage" );
	
?>