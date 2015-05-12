<?php

	function AuthenticateUser( $username, $password ) {
		//return true if Login was correct, false on failure

		$username = mysql_real_escape_string( $username );
		$password = mysql_real_escape_string( $password );
		$password = hash("sha512", $password, false );

		$result = mysql_query(
			"SELECT
				*
			 FROM
				users
			 WHERE
				username='$username'
				AND password='$password'
			LIMIT 1;"
		);
		
		return ( mysql_num_rows( $result ) == 1 );
	
	}
	
	function isAdmin( $id ) {
		$id = mysql_real_escape_string( $id );
		$query = "SELECT * FROM admin WHERE user_id='$id'";
		$result = mysql_query( $query ) or die( mysql_error() );
		return ( mysql_num_rows( $result ) == 1 );
	}

	function isAdminPDO( $id ) {

		global $conn;

		$id = mysql_real_escape_string( $id );
		$query = "SELECT * FROM admin WHERE user_id='$id'";

		$stmt = $conn->query( $query );

		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		return ( $stmt->rowCount( ) == 1 );
	}
	
	function UserExists( $username ) {
		$username = mysql_real_escape_string( $username );
		$sql = "SELECT id FROM users WHERE username='$username'";
		$result = mysql_query( $sql );
		return ( mysql_num_rows( $result ) == 1 );
	}
	
	function FindUserByUsername( $username ) {
		$username = mysql_real_escape_string( $username );
		$sql = "SELECT email,fullname FROM users WHERE username='$username'";
		$result = mysql_query( $sql );
		$obj = mysql_fetch_array( $result );
		return ( $obj );		
	}
	
	function addFullname( $username, $fullname, $ref ) {

		$username = mysql_real_escape_string( $username );
		$fullname = mysql_real_escape_string( $fullname );

		$query = mysql_query("SELECT * FROM fullnames WHERE username='$username' LIMIT 1;");
		if ( mysql_num_rows( $query ) == 0 ) {
			mysql_query( "
				INSERT INTO `grader`.`fullnames` (
					`username` ,
					`fullname` ,
					`date` ,
					`date_login`
					)
					VALUES (
					'$username', '$fullname', NOW(), NOW()
					);
			");
		}else {

			if ( $ref == 1 ) {
				mysql_query( "
					UPDATE `grader`.`fullnames`
						SET
							`fullname` = '$fullname',
							`date` = NOW()
						WHERE
							`username` = '$username'
						LIMIT 1;
				");
			}else {
				mysql_query( "
					UPDATE `grader`.`fullnames`
						SET
							`fullname` = '$fullname',
							`date_login` = NOW()
						WHERE
							`username` = '$username'
						LIMIT 1;
				");
			}
		}
		
	}

	function FindFullName( $username ) {

		$username = mysql_real_escape_string( $username );

		$query = mysql_query("SELECT * FROM fullnames WHERE username='$username' LIMIT 1;");
		if ( mysql_num_rows( $query ) == 0 ) {
			return ( false );
		}else {
			$obj = mysql_fetch_array( $query );
			return ( $obj['fullname'] );
		}
		
	}

	function GetLastConnectedUsers( ) {

		$query = mysql_query("SELECT * FROM fullnames ORDER BY date DESC LIMIT 0, 30;");

		if ( mysql_num_rows( $query ) == 0 ) {
			return ( false );
		}else {
			$ret = array();

			while ( $obj = mysql_fetch_array( $query ) ) {
				$ret[] = $obj;
			}

			return ( $ret );
			
		}

	}

	/*
	function FindUserId( $username ) {
		$sql = "SELECT id FROM users WHERE username='$username'";
		$result = mysql_query( $sql );
		$obj = mysql_fetch_array( $result );
		return ( $obj['id'] );
	}*/
	
?>
