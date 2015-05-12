<?php

	include "models/database.php";
	include "models/days.php";
	
	$t = time();
	
	$G = date( 'G', $t );
	
	if ( $G < 10 ) {
		$From .= "0" . date( 'G:i:s', $t);
	}else {
		$From = date( 'G:i:s', $t);
	}
	echo GetDay( date( 'w', $t ) ) . ", " . date ( 'd', $t ) . " " . Month( date ( 'm', $t ) ) . date ( 'Y', $t ) . " - " . $From;
	
?>	
				