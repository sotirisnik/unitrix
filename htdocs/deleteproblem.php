<?php

	session_start();

	if ( !isset($_GET['id']) ) {
		die();
	}

	include "models/database.php";
	include "models/users.php";
	include "models/problems.php";
	
	OpenDatabase();
	
	$id = $_GET['id'];
	
	if ( !isAdmin( $_SESSION['username'] ) ) {
		CloseDatabase();
		header ( "Location: index.php?page=wrongpage" );
		die();
	}else {
		DeleteProblem( $_GET['id'] );
		CloseDatabase();
		header ( "Location: index.php?page=manage&insert=5" );
	}

?>
