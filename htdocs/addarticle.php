<?php

	if ( !isset( $_POST['maintitle'] ) || !isset( $_POST['maintext'] )  ) {
		die();
	}

	$title = mysql_real_escape_string($_POST['maintitle']);
	$text = mysql_real_escape_string($_POST['maintext']);

	include "models/database.php";
	
	OpenDatabase();

	$sql = "";

	if ( $_POST['sid'] == '' ) {
	
		$sql = "
		
			INSERT INTO `grader`.`articles` (
				`id`,
				`title`,
				`text`,
				`date`
				)
				VALUES (
				'', '$title', '$text', NOW()
				);
		
		";

	}else {

		$sid = mysql_real_escape_string( $_POST['sid'] );

		$sid_length = strlen( $sid );

		for ( $i = 0; $i < $sid_length; ++$i ) {
			if ( !( $sid[i] >= '0' && $sid[$i] <= '9' ) ) {
				CloseDatabase();
				header( "Location: index.php?page=manage&insert=8" );
			}
		}

		$sql = "		
			UPDATE `grader`.`articles`

			SET `title`='$title',
				`text`='$text'

			WHERE
				`id` = '" . $_POST['sid'] . "'
		";
	
	}

	$res = mysql_query( $sql ) or die( mysql_error() );
	
	CloseDatabase();
	
	header( "Location: index.php?page=manage&insert=7" );
	
?>
