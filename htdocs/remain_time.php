<?php

	session_start();

	include "models/database.php";
	include "models/access.php";
	include "models/users.php";
	
	OpenDatabase();
	
	$sql = "SELECT id,name,finish_date,access FROM competitions WHERE ( NOW() BETWEEN start_date-INTERVAL 24 SECOND AND finish_date ) AND visible='0'";//DATE(start_date) <= DATE(NOW()) AND DATE(NOW()) <= DATE(finish_date) AND CURTIME() < finish_hour";
	//echo $sql;
	$result = mysql_query( $sql ) or die( mysql_error() );
	$num = mysql_num_rows( $result );
	
	$Answer = "";
		
	if ( $num == 0 ) {
		$Answer = "Δεν υπάρχουν διαγωνισμοί";
		CloseDatabase();
		echo ( $Answer );
		die();
	}
		
	$Answer ="<ul style='list-style-type:none;'>";
	
	$t = time();
	/*
	$Hour = date( 'h', $t );
	$Minutes = date( 'i', $t );
	$Seconds = date( 's', $t );
	$Day = date( 'd', $t );
	$Month = date( 'm', $t );
	$Year = date( 'Y', $t );
	*/
	$From = date( 'Y-m-d h:i:s', $t);
	
	//$From = "$Year-$Month-$Day $Hour:$Minutes:$Seconds";
	$first = new DateTime( $From );
	//echo "<br> " . $first->format( 'Y-m-d h:i:s' ) . "<br>";
	
	$id = $_GET['id'];
	
	while ( $obj = mysql_fetch_array( $result ) ) {
	
		$fl = true;
		
		if ( $obj['access'] == 1 ) {
			//SELECT id,name,finish_date,access
			$Sql = "SELECT * FROM competition_access WHERE user_id='$id' AND competition_id=" . $obj['id'];// ."'";
			
			if ( mysql_num_rows( mysql_query($Sql) ) == 1 ) {
				$fl = false;
			}
		}
		
		if ( $obj['access'] == '1'  && $fl == true && isAdmin(FindUserId( $_SESSION['username'] ) ) == false ) {
			continue;
		}
		
		$Answer .= "<li><a href='index.php?page=competitions&id=" . $obj['id'] ."'>" . $obj['name'] . "</a></li>";
		
		$val = $obj['finish_date'];// . " " . $obj['finish_hour'] . ":00:00";
		
		//echo "Val := " . $val . "<br>";
		
		$Second = new DateTime( $val );
		
		//echo $Second->format( 'Y-m-d h:i:s' );
		
		$diff = $first->diff($Second);
		
		$remain_days = $diff->format( '%D' );
		$remain_hours = $diff->format( '%H' );
		//echo  date( 'a', $t);
		if ( date( 'a', $t) == 'pm' ) {
			$remain_hours -= 12;
		}
		
		if ( $remain_days >= 1 ) {
			$remain_hours += 24*$remain_days;
		}
		//echo "Yeah := " . $remain_hours . "<br>";
		//if ( $diff->format( '%H:%I:%S' ) != '00:00:00' )
		
		$rem = ($remain_hours < 10 ? '0' . $remain_hours : $remain_hours) . $diff->format( ':%I:%S' );
		
		if ( $rem != '00:00:00' )
			$Answer .= "<li> Απομένουν: " . ($remain_hours < 10 ? '0' . $remain_hours : $remain_hours) . $diff->format( ':%I:%S' ) . "<li>";
		else
			$Answer .= "<li> Ο χρόνος τελείωσε. <li>";
		
	}
		
	$Answer .= "</ul>";
	
	CloseDatabase();
	
	echo ( $Answer );
	//die();
	
	//echo $From . "<br>" . $val . "<br>";
	
?>	
				