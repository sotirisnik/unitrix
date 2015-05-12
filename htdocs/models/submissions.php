<?php

	function FindMyActiveSubmissions( $n, $user_id ) {
	
		$n = mysql_real_escape_string( $n );
		$user_id = mysql_real_escape_string( $user_id );

		$result = mysql_query( 
			"SELECT
				sources.id, problems.short_name
			FROM
				problems,sources
			WHERE
				problems.parent_id = '$n'
				AND sources.user_id = '$user_id'
				AND sources.parent_id = problems.id
				AND sources.active = '1'
			ORDER
				BY problems.id, sources.id DESC;"
			);
			
		if ( mysql_num_rows( $result ) == 0 ) {
			return ( false );
		}
		
		$rows = array();
		
		while ( $obj = mysql_fetch_array( $result ) ) {	
			$rows[] = $obj;
		}
		
		return ( $rows );
		
	}
	
	function FindAllProblemsSubmissionsForUser( $n, $user_id ) {
		
		$n = mysql_real_escape_string( $n );
		$user_id = mysql_real_escape_string( $user_id );

		$result = mysql_query(
			"SELECT
				id,name
			 FROM
				problems
			 WHERE
				parent_id = '$n';"
			);
				
		if ( mysql_num_rows( $result ) == 0 ) {
			return ( false );
		}
		
		$rows = array();
		
		while ( $obj = mysql_fetch_array( $result ) ) {
			$obj['last'] = FindProblemSubmissions( $obj['id'], $user_id );
			$rows[] = $obj;
		}
		
		return ( $rows );
		
	}
	
	function FindProblemSubmissions( $n, $user ) {
	
		$n = mysql_real_escape_string( $n );
		$user = mysql_real_escape_string( $user );

		$result = mysql_query(
			"SELECT
				*
			FROM
				sources
			WHERE
				parent_id = '$n'
				AND user_id = '$user'
			ORDER BY
				datetime
			DESC;"
			);
		
		if ( mysql_num_rows( $result ) == 0 ) {
			return ( false );
		}
		
		$rows = array();
		
		while ( $obj = mysql_fetch_array( $result ) ) {
			$rows[] = $obj;
		}
		
		return ( $rows );
		
	}
	
	function FindAllProblemsSubmissionsForUserSelect( $n, $user_id ) {
		
		$n = mysql_real_escape_string( $n );
		$user_id = mysql_real_escape_string( $user_id );

		$result = mysql_query(
			"SELECT
				id,name,accepted_languages
			 FROM
				problems
			 WHERE
				parent_id = '$n';"
			);
		
		$num = mysql_num_rows( $result );
	
		if ( $num == 0 ) {
			return ( false );
		}
		
		$rows = array();
		
		while ( $obj = mysql_fetch_array( $result ) ) {
			$rows[] = $obj;
		}
		
		return ( $rows );
		
	}
	
	function FindAllProblemsSubmissionsForUserSelectFirstID( $n, $user_id ) {
	
		$n = mysql_real_escape_string( $n );
		$user_id = mysql_real_escape_string( $user_id );

		$result = mysql_query(
			"SELECT
				id,name
			 FROM
				problems
			 WHERE
				parent_id = '$n'
			 LIMIT 1;"
		);
		
		if ( mysql_num_rows( $result ) == 0 ) {
			return ( false );
			//$Answer = "<option>Δεν υπάρχουν προβλήματα</option>";
			//return ( $Answer );
		}
		
		$obj = mysql_fetch_array( $result );
		
		return ( $obj['id'] );//$Answer .= "<option value='" . $obj['id'] . "'>" . $obj['name'] . "</option>";
		
	}
	
	function GetSubmissionResults( $n ) {
		
		$n = mysql_real_escape_string( $n );

		$res = mysql_query(
		     "SELECT
			      parent_id, examined, compilation_error, compilation_error_text, compiler_language
		      FROM
			      sources
		      WHERE
		          sources.id = '$n';"
		      );
		
		if ( mysql_num_rows( $res ) == 0 ) {
			return ( false );
		}
		
		$rows = array();
		
		$rows2 = array();
		
		$ob = mysql_fetch_array( $res );
	
		$ob['problem_name'] = GetProblemName($ob['parent_id']);
		
		$result = mysql_query(
		       "SELECT
					*
				FROM
				    feedback
				WHERE
					submit_id = '$n';"
			);
		
		$num = mysql_num_rows( $result );
		
		while ( $obj = mysql_fetch_array( $result ) ) {
			$rows2[] = $obj;	
		}
		
		$rows[] = $ob;
		$rows[] = $rows2;
		
		return ( $rows );
		
		
	}

	function HasActiveSubmission( $username, $problem_id ) {
		$sql = mysql_query(
				"SELECT
					id
				 FROM
					sources
				 WHERE
					user_id = '$username'
				 	AND parent_id ='$problem_id'
				 	AND active = '1'
				 LIMIT 1;" );
		return ( mysql_num_rows( $sql ) );
	}
	
	function isMysubmission( $id, $username ) {
		
		$id = mysql_real_escape_string( $id );
		$username = mysql_real_escape_string( $username );

		$sql = mysql_query( 
			"SELECT
				*
			 FROM
			 	sources
			 WHERE
				id = '$id'
				AND user_id ='$username'
			 LIMIT 1;" );

		return ( mysql_num_rows( $sql ) == 1 );

	}

	function SubmissionContestParentID( $id ) {
		
		$id = mysql_real_escape_string( $id );

		$sql = mysql_query( 
			"SELECT
				parent_id
			 FROM
			 	sources
			 WHERE
				id = '$id'
			 LIMIT 1;" );

		$obj = mysql_fetch_array( $sql );

		$id = $obj['parent_id'];

		$sql = mysql_query( 
			"SELECT
				parent_id
			 FROM
			 	problems
			 WHERE
				id = '$id'
			 LIMIT 1;" );
		
		$obj = mysql_fetch_array( $sql );

		return ( $obj['parent_id'] );

	}

?>
