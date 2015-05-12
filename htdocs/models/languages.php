<?php

	function GetLanguages( ) {
		
		$res = mysql_query(
			"SELECT
				id, name
			FROM
				languages;
			" );
		
		$rows = array();
		
		while ( $obj = mysql_fetch_array( $res ) ) {
			$rows[] = $obj;
		}
		
		return ( $rows );
		
	}

	function GetLanguageID( $lang_name ) {
		
		$lang_name = mysql_real_escape_string( $lang_name );

		$res = mysql_query(
			"SELECT
				id
			FROM
				languages
			WHERE
				languages.name = '$lang_name'
			LIMIT 1;" );
		
		if ( mysql_num_rows( $res ) == 0 ) {
			return ( false );
		}

		$obj = mysql_fetch_array( $res );
		$Answer = $obj['id'];

		return ( $Answer );
		
	}
	
	function MapLanguages( ) {
		
		$ret = "";
		
		$res = mysql_query(
			"SELECT
				id, name
			FROM
				languages;
			" );
		
		while ( $obj = mysql_fetch_array( $res ) ) {
			$ret[ $obj['name'] ] = $obj['id'];
		}
		
		return ( $ret );
		
		
	}
	
	function TotalLanguages( ) {
		
		$res = mysql_query(
			"SELECT
				id, name
			FROM
				languages;
			" );
		
		return ( mysql_num_rows( $res ) );
		
	}
	

?>
