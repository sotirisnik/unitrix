<?php

	function FindContestProblems( $n ) {
		
		$n = mysql_real_escape_string( $n );

		$result = mysql_query(
			"SELECT
				id,name,short_name
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
		
		while ( $row = mysql_fetch_array( $result ) ) {
			$rows[] = $row;
		}
		
		return ( $rows );
		
	}
	
	function GetProblemName( $n ) {
	
		$n = mysql_real_escape_string( $n );

		$result = mysql_query(
			"SELECT
				name
			FROM
				problems
			WHERE
				id = '$n'
			LIMIT 1;"
		);
		
		$num = mysql_num_rows( $result );
	
		if ( $num == 0 )
			return "";
	
		$obj = mysql_fetch_array( $result );
		
		return ( $obj['name'] );
	
	}
	
	function ProblemExist( $n ) {

		$n = mysql_real_escape_string( $n );

		$result = mysql_query(
			"SELECT
				id
			FROM
				problems
			WHERE
				id = '$n';"
			);
		return ( mysql_num_rows( $result ) == 1 );
	}
	
	function GetProblemData( $n ) {

		$n = mysql_real_escape_string( $n );

		$result = mysql_query( 
			"SELECT
				parent_id, name, intro, input_info, output_info,
				sample_input, sample_input2, sample_input3, 
				sample_output, sample_output2, sample_output3,
				accepted_languages, explanation_output,explanation_output2,explanation_output3
			FROM
				problems
			WHERE
				id = '$n';"
			);
		
		$obj = mysql_fetch_array( $result );
		
		return ( $obj );
		
	}
	
	
	function SubmitProblems( $id, $myID ) {
	
		//$tid = FindAllProblemsSubmissionsForUserSelectFirstID( $id, $myID );

		$id = mysql_real_escape_string( $id );
		$myID = mysql_real_escape_string( $myID );

		$sql = "SELECT id,name FROM problems WHERE parent_id='$id'";
		$num = mysql_num_rows( mysql_query( $sql ) );
		
		if ( $num == 0 ) {
			return ( false );
		}
		
		$fapsfus = FindAllProblemsSubmissionsForUserSelect( $id, $myID );
			
		return ( $fapsfus );
						
	}

	function GetProblemTest( $id ) {
	
		$id = mysql_real_escape_string( $id );

		$result = mysql_query(
			"
			SELECT
				id, points, test_selection
			FROM
				problemtest
			WHERE
				problem_id = '$id';
			");
		
		$rows = array();
		
		while ( $obj = mysql_fetch_array( $result ) ) {
			$rows[] = $obj;
		}
		
		return ( $rows );
	
	}

	function DeleteProblem( $id ) {
		//Delete problem

		$id = mysql_real_escape_string( $id );

		$sql = mysql_query(
			"DELETE FROM
				`grader`.`problems`
			 WHERE
				`problems`.`id` = '$id'" );

		$sql = mysql_query(
			"SELECT
			 	id
			 FROM
				`grader`.`sources`
			 WHERE
				`sources`.`parent_id` = '$id'" );
		//Delete all examined tests
		while ( $obj = mysql_fetch_array($sql) ) {

			$tmp = $obj['id'];
			mysql_query(
				"DELETE FROM
					`grader`.`feedback`
				 WHERE
					`feedback`.`submit_id` = '$tmp'" );

		}

		$sql = mysql_query(
			"SELECT
			 	id
			 FROM
				`grader`.`problemtest`
			 WHERE
				`problemtest`.`problem_id` = '$id'" );

		//Delete all problem file input/output tests
		while ( $obj = mysql_fetch_array($sql) ) {

			$tmp = $obj['id'];
			mysql_query(
				"DELETE FROM
					`grader`.`files_io`
				 WHERE
					`files_io`.`test_id` = '$tmp'" ) or die( mysql_error() );

		}

		//Delete tests for this problem
		$sql = mysql_query(
			"DELETE FROM
				`grader`.`problemtest`
			 WHERE
				`problemtest`.`problem_id` = '$id'" );

		//Delete submissions codes for this problem
		$sql = mysql_query(
			"DELETE FROM
				`grader`.`sources`
			 WHERE
				`sources`.`parent_id` = '$id'" );

	}

?>
