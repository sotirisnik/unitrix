<?php

	function LimitedAccess( $ContestID, $id ) {
		
			$ContestID = mysql_real_escape_string( $ContestID );
			$id = mysql_real_escape_string( $id );

			$res = mysql_query( 
				"SELECT
					*
				 FROM
					competitions
				 WHERE
					competitions.id = '$ContestID'
				 LIMIT 1;
				");
			
			if ( mysql_num_rows($res) == 0 ) {
				return ( true );
			}
			
			$obj = mysql_fetch_array($res);
			
			if ( $obj['visible'] == 1 ) {
				return ( true );
			}
			
			if ( $obj['access'] == 1 ) {
				$res = mysql_query( 
					"SELECT
						*
					 FROM
						competition_access
					 WHERE
						user_id = '$id'
						AND competition_id = '$ContestID'
					 LIMIT 1;
					");
				if ( mysql_num_rows( $res ) == 1 ) {
					return ( false );
				}else {
					return ( true );
				}
			}
			
			return ( false );
			
	}

?>
