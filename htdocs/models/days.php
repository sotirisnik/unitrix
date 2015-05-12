<?php

	function Month( $n ) {
		if  ( $n == "01" ) {
		    return ( "Ιανουαρίου" );
		}else if  ( $n == "02" ) {
		    return ( "Φεβρουαρίου" );
		}else if  ( $n == "03" ) {
		    return ( "Μαρτίου" );
		}else if  ( $n == "04" ) {
		    return ( "Απριλίου" );
		}else if  ( $n == "05" ) {
		    return ( "Μαίου" );
		}else if  ( $n == "06" ) {
		    return ( "Ιουνίου" );
		}else if  ( $n == "07" ) {
		    return ( "Ιουλίου" );
		}else if  ( $n == "08" ) {
		    return ( "Αυγούστου" );
		}else if  ( $n == "09" ) {
		    return ( "Σεπτεμβρίου" );
		}else if  ( $n == "10" ) {
		    return ( "Οκτώβρίου" );
		}else if  ( $n == "11" ) {
		    return ( "Νοεμβριου" );
		}else if  ( $n == "12" ) {
		    return ( "Δεκεμβρίου" );
		}
		
		return ( $n );
		
		return ( "???" );
	
	}
	
	function GetDay( $n ) {
	
		if ( $n == "00" ) {
			return ( "Κυριακή" );
		}else if ( $n == "01" ) {
			return ( "Δευτέρα" );
		}else if ( $n == "02" ) {
			return ( "Τρίτη" );
		}else if ( $n == "03" ) {
			return ( "Τετάρτη" );
		}else if ( $n == "04" ) {
			return ( "Πέμπτη" );
		}else if ( $n == "05" ) {
			return ( "Παρασκευή" );
		}else if ( $n == "06" ) {
			return ( "Σάββατο" );
		}
		
		return ( "???" );
		
	}
	
?>
