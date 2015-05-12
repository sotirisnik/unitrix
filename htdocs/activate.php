<?php

	session_start();

	if ( !isset($_GET['id']) ) {
		die();
	}

	include "models/database.php";
	include "models/users.php";
	
	OpenDatabase();
	
	$id = $_GET['id'];
	$id = mysql_real_escape_string( $id );
	
	$User_id = $_SESSION['username'];
	$User_id = mysql_real_escape_string( $User_id );

	$query = "SELECT user_id, parent_id FROM sources WHERE id ='$id'";
	$result = mysql_query( $query );
	$obj = mysql_fetch_array( $result );
	$PARENTID = $obj['parent_id'];
	$USERID = $obj['user_id'];
	
	$query = "SELECT parent_id FROM problems WHERE id =" . $PARENTID;
	$result = mysql_query( $query );
	$obj = mysql_fetch_array( $result );
	$gotoParentID = $obj['parent_id'];
	
	//echo $id . " " . $PARENTID . " " . $gotoParentID;
	
	if ( $User_id == $USERID || isAdmin($User_id) ) {
		$sql = "UPDATE sources SET active = '0' WHERE user_id='$USERID' AND parent_id='$PARENTID' AND active='1' ";
		$res = mysql_query( $sql );
		$sql = "UPDATE sources SET active = '1' WHERE user_id='$USERID' AND id='$id' ";
		$res = mysql_query( $sql );
		CloseDatabase();
		header ( "Location: index.php?page=mysubmissions&id=" . $gotoParentID );
	}else {
		CloseDatabase();
		header ( "Location: index.php?page=mysubmissions&id=" . $gotoParentID . "&error=wrongpage" );	
	}
?>
