<?php

	function GetLatestNews( ) {

		$result = mysql_query(
			"SELECT
				*
			 FROM
				articles
			 ORDER
				BY DATE DESC
			LIMIT 4;"
		);
		
		if ( mysql_num_rows( $result ) == 0 ) {
			return ( false );
		}

		$ret = array();

		while ( $obj = mysql_fetch_array($result) ) {
			$ret[] = $obj;
		}

		return ( $ret );
	
	}

	function GetNewsWithID( $id ) {

		$id = mysql_real_escape_string( $id );

		$result = mysql_query(
			"SELECT
				*
			 FROM
				articles
			 WHERE
				id = '$id'
			LIMIT 1;"
		);
		
		if ( mysql_num_rows( $result ) == 0 ) {
			return ( false );
		}

		$obj = mysql_fetch_array($result);

		return ( $obj );
	
	}
	
?>
