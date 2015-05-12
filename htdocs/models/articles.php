<?php

function GetArticle( $id ) {

	$id = mysql_real_escape_string( $id );
	 
	$result = mysql_query(
		"
			SELECT
				title, text
			FROM
				articles
			WHERE
				id = '$id'
			LIMIT 1;
		"
	);
	 
	if ( mysql_num_rows( $result ) == 0 ) {
		return ( false );
	}
	 
	$obj = mysql_fetch_array( $result );
	 
	return ( $obj );

}

function GetArticles( ) {

	$result = mysql_query(
		"
			SELECT
				id, title, text
			FROM
				articles;
		"
	);
	 
	if ( mysql_num_rows( $result ) == 0 ) {
		return ( false );
	}
	
	$rows = array();
		
	while ( $row = mysql_fetch_array( $result ) ) {
		$rows[] = $row;
	}
	
	return ( $rows );

}

?>
