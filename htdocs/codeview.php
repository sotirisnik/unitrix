<?php

	session_start();

	if ( !isset( $_GET['id'] ) || !isset( $_SESSION['username'] ) ) {
		die();
	}

	include "models/database.php";
	include "models/sourcecode.php";
	include "models/submissions.php";
	include "models/users.php";
	
	OpenDatabase();

	$admin = 0;
	
	if ( isset($_SESSION['username']) ) {
		$admin = isAdmin( $_SESSION['username'] );
	}	

	if ( !$admin && isMysubmission( $_GET['id'], $_SESSION['username'] ) == false ) {
			CloseDatabase();
			echo "Δεν έχετε πρόσβαση στα περιεχόμενα του συγκεκριμένου κώδικα.";
			die();
	}

	$code = GetSourceCode( $_GET['id'] );
	$code = Cplusplus($code);//GetSourceCode( $_GET['id'] );
	
	CloseDatabase();

	echo "<pre style='font-size:1.2em;' >" . $code . "</pre>";
	
?>
