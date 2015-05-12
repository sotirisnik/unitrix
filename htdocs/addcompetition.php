<?php

	if ( !isset( $_POST['contest_name'] ) || !isset( $_POST['contest_duration'] ) || !isset( $_POST['dstart'] ) || !isset( $_POST['dfinish'] ) || !isset( $_POST['sid'] ) ) {// || !isset( $_POST['shour'] ) || !isset( $_POST['smin'] ) || !isset( $_POST['fhour'] ) || !isset( $_POST['fmin'] ) ) {
		header( "Location: index.php?page=manage&error=2" );
		die();
	}
	
	include "models/database.php";
	
	$name = $_POST['contest_name'];
	$duration = $_POST['contest_duration'];
	
	$start_date = $_POST['dstart'];
	$tmp = explode( "/", $start_date );
	$start_date =  $tmp[2] . "-" . $tmp[1] . "-" . $tmp[0] . " ";
	
	$finish_date = $_POST['dfinish'];
	$tmp = explode( "/", $finish_date );
	$finish_date =  $tmp[2] . "-" . $tmp[1] . "-" . $tmp[0] . " ";
	
	$shour = $_POST['shour'];
	$smin = $_POST['smin'];
	$fhour = $_POST['fhour'];
	$fmin = $_POST['fmin'];
	
	if ( $shour < 10 )
		$start_date .= "0" . $shour;
	else
		$start_date .= ":" . $shour;
	
	if ( $smin < 10 )
		$start_date .= ":0" . $smin;
	else
		$start_date .=  ":" . $smin;
	
	$start_date .= ":00";
	
	if ( $fhour < 10 )
		$finish_date .= "0" . $fhour;
	else
		$finish_date .= ":" . $fhour;
	
	if ( $fmin < 10 )
		$finish_date .= ":0" . $fmin;
	else
		$finish_date .=  ":" . $fmin;
	
	$finish_date .= ":00";
	
	/*
	echo $name . "<br>";
	echo $duration . "<br>";
	echo "Start := " . $start_date . "<br>";
	echo $finish_date . "<br>";
	*/
	//die();
	
	OpenDatabase();
	
	$sql = "";
	
	if ( $_POST['sid'] == '' ) {
	
		$sql = "
		
			INSERT INTO `grader`.`competitions` (
				`id` ,
				`name` ,
				`duration` ,
				`start_date` ,
				`finish_date`
				)
				VALUES (
				'', '$name', '$duration', '$start_date', '$finish_date'
				);
		
		";
	
	}else {
	
			$sql = "		
				UPDATE `grader`.`competitions`

				SET `name`='$name',
					`duration`='$duration',
					`start_date`='$start_date',
					`finish_date`='$finish_date'
				
				WHERE
					`id` = '" . $_POST['sid'] . "'
			";
	
	}
	
	$res = mysql_query( $sql ) or die( mysql_error() );
	
	CloseDatabase();
	
	header( "Location: index.php?page=manage&insert=2" );
	
?>