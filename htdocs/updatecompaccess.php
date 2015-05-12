<?php

	if ( !isset( $_POST['accessby'] ) ) {
		header( "Location: index.php?error=2" );
		die();
	}
	
	if ( !isset( $_POST['get_contest_id'] ) ) {
		header( "Location: index.php?error=2" );
		die();
	}
	
	include "models/database.php";
	
	OpenDatabase();
	
	$query = "DELETE FROM competition_access WHERE competition_id=" . $_POST['get_contest_id'];
	mysql_query( $query );
	
	if ( strcmp($_POST['accessby'], "all") == 0 ) {
		//echo "access is for everyone";
		$query = "UPDATE competitions SET access = '0' WHERE id= '" . $_POST['get_contest_id'] . "'";
		$result = mysql_query( $query );
		header( "Location: index.php?page=manage&insert=2" );
		//echo $query . "<br>";
	}else if ( strcmp($_POST['accessby'], "few") == 0 ) {
		//echo " access is for few";
		$query = "UPDATE competitions SET access = '1' WHERE id= '" . $_POST['get_contest_id'] . "'";
		$result = mysql_query( $query );
		
		//$x = $_POST['compusers'];
	
		//print_r($_POST['user']);
	
		//echo "Total := " . count($_POST['user']) . "<br>";
		for( $i = 0; $i < count($_POST['user']); ++$i ) {
			//do something with $item
			//echo "$i := " . $_POST['user'][$i] . "<br>";
		
			$query = "
			INSERT INTO `grader`.`competition_access` (
			`user_id` ,
			`competition_id`
			)
			VALUES (
			'". $_POST['user'][$i] ."', '". $_POST['get_contest_id'] ."'
			)
			";
			mysql_query( $query );
		}
		
		header( "Location: index.php?page=manage&insert=2" );
	}
	
	CloseDatabase();

?>