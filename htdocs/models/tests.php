<?php

function insertNUllTest( $problem_id ) {

		 $result = mysql_query(
		     "
				INSERT INTO
					problemtest (problem_id,input,output,points,test_selection)
				VALUES
					( '$problem_id', '', '', '0', '2' );
			 "
		 );
		 
		 return ( mysql_affected_rows( ) );

}


function insertTest( $problem_id, $inFile, $outFile ) {

		 $result = mysql_query(
		     "
				INSERT INTO
					problemtest (problem_id,input,output,points,test_selection)
				VALUES
					( '$problem_id', '$inFile', '$outFile', '0', '2' );
			 "
		 );
		 
		 return ( mysql_affected_rows( ) );

}

function insertTestFile( $test_id, $inFile, $filename ) {

		$inFile = mysql_real_escape_string( $inFile );
		$filename = mysql_real_escape_string( $filename );

		 $result = mysql_query(
		     "
				INSERT INTO
					files_io (id,test_id,filename,content,isinput)
				VALUES
					( '', '$test_id', '$filename', '$inFile', '1' );
			 "
		 );
		 
		 return ( mysql_affected_rows( ) );

}

function updateTestInput( $problem_id, $inFile ) {

 		 $inFile = mysql_real_escape_string( $inFile );

		 $result = mysql_query(
		     "
				UPDATE
					problemtest
				SET
					input = '$inFile'
				WHERE
					id = '$problem_id'
				LIMIT 1;
			 "
		 );
		 
		 return ( true );
		 
		 return ( mysql_affected_rows( ) );

}

function UpdateTestFileName( $test_id, $inFile ) {

 		 $inFile = mysql_real_escape_string( $inFile );

		 $result = mysql_query(
		     "
				UPDATE
					files_io
				SET
					filename = '$inFile'
				WHERE
					id = '$test_id'
				LIMIT 1;
			 "
		 );
		 
		 return ( true );
		 
		 return ( mysql_affected_rows( ) );

}

function UpdateTestFileContent( $test_id, $content ) {

 		 //$content = mysql_real_escape_string( $content );

		 $result = mysql_query(
		     "
				UPDATE
					files_io
				SET
					content = '$content'
				WHERE
					id = '$test_id'
				LIMIT 1;
			 "
		 );
		 
		 return ( true );
		 
		 return ( mysql_affected_rows( ) );

}

function updateTestOutput( $problem_id, $inFile ) {

		 $inFile = mysql_real_escape_string( $inFile );

		 $result = mysql_query(
		     "
				UPDATE
					problemtest
				SET
					output = '$inFile'
				WHERE
					id = '$problem_id'
				LIMIT 1;
			 "
		 );
		 
		 return ( true );
		 
		 //return ( mysql_affected_rows( ) );

}

function UpdateTestSelection( $id, $test_selection ) {

		 $id = mysql_real_escape_string( $id );
		 $test_selection = mysql_real_escape_string( $test_selection );

		 $result = mysql_query(
		     "
				UPDATE
					problemtest
				SET
					test_selection = '$test_selection'
				WHERE
					id = '$id'
				LIMIT 1;
			 "
		 );
		 
		 return ( true );
		 //return ( mysql_affected_rows( ) );

}

function GetTestSelection( $problem_id, $id ) {

		 $id = mysql_real_escape_string( $id );
		 $problem_id = mysql_real_escape_string( $problem_id );

		 --$id;

		 $result = mysql_query(
		     "
				SELECT
					test_selection
				FROM
					problemtest
				WHERE
					problem_id = '$problem_id'
				LIMIT $id, 1;
			 "
		 );
		 
		 if ( mysql_num_rows( $result ) == 0 ) {
			return ( 0 );
		 }
		 
		 $obj = mysql_fetch_array( $result );
		 
		 return ( $obj['test_selection'] );

}

function GetTestSelectionID( $problem_id, $id ) {

		 $id = mysql_real_escape_string( $id );
		 $problem_id = mysql_real_escape_string( $problem_id );

		 --$id;

		 $result = mysql_query(
		     "
				SELECT
					id
				FROM
					problemtest
				WHERE
					problem_id = '$problem_id'
				LIMIT $id, 1;
			 "
		 );
		 
		 if ( mysql_num_rows( $result ) == 0 ) {
			return ( 0 );
		 }
		 
		 $obj = mysql_fetch_array( $result );
		 
		 return ( $obj['id'] );

}

function GetTestSelectionFeedBack( $problem_id, $test_id ) {

		 $problem_id = mysql_real_escape_string( $problem_id );
		 $test_id = mysql_real_escape_string( $test_id );

		 $result = mysql_query(
		     "
				SELECT
					test_selection
				FROM
					problemtest
				WHERE
					problem_id = '$problem_id'
					AND id = '$test_id'
				LIMIT 1;
			 "
		 );
		 
		 if ( mysql_num_rows( $result ) == 0 ) {
			return ( 0 );
		 }
		 
		 $obj = mysql_fetch_array( $result );
		 
		 return ( $obj['test_selection'] );

}

function GetTestFileContent( $test_id ) {

		 $result = mysql_query(
		     "
				SELECT
					content
				FROM
					files_io
				WHERE
					id = '$test_id'
				LIMIT 1;
			 "
		 );
		 
		 if ( mysql_num_rows( $result ) == 0 ) {
			return ( false );
		 }
		 
		 $obj = mysql_fetch_array( $result );
		 
		 return ( $obj['content'] );

}

function UpdateTestPoints( $id, $points ) {

		 $result = mysql_query(
		     "
				UPDATE
					problemtest
				SET
					points = '$points'
				WHERE
					id = '$id'
				LIMIT 1;
			 "
		 );
		 
		 
		 return ( mysql_affected_rows( ) );

}

function FindTestProblemParent( $id ) {
	
		$result = mysql_query(
			"
			SELECT
				problem_id
			FROM
				problemtest
			WHERE
				id = '$id'
			LIMIT 1;
			");
		
		if ( mysql_num_rows( $result ) == 0 ) {
			return ( false );
		}
		
		$obj = mysql_fetch_array( $result );
		
		return ( $obj['problem_id'] );
	
}

function TestInput( $id ) {
	
		$id = mysql_real_escape_string( $id );

		$result = mysql_query(
			"
			SELECT
				input
			FROM
				problemtest
			WHERE
				id = '$id'
			LIMIT 1;
			");
		
		if ( mysql_num_rows( $result ) == 0 ) {
			return ( false );
		}
		
		$obj = mysql_fetch_array( $result );
		
		return ( $obj['input'] );
	
}

function TestOutput( $id ) {
	
		$id = mysql_real_escape_string( $id );

		$result = mysql_query(
			"
			SELECT
				output
			FROM
				problemtest
			WHERE
				id = '$id'
			LIMIT 1;
			");
		
		if ( mysql_num_rows( $result ) == 0 ) {
			return ( false );
		}
		
		$obj = mysql_fetch_array( $result );
		
		return ( $obj['output'] );
	
}

function GetAllFileInputTest( $test_id ) {

		$query = mysql_query(
				"SELECT
					id, test_id, filename
				 FROM
					files_io
				 WHERE
					isinput = '1' AND
					test_id = '$test_id';" );
		
		if ( mysql_num_rows($query) == 0 ) {
			return ( false );
		}
		
		$ret = array();
		
		while ( $obj = mysql_fetch_array($query) ) {
			$ret[] = $obj;
		}
		
		return ( $ret );

}

function FindFileTestFileName( $id ) {

		 $query = mysql_query(
				"SELECT
					filename
				 FROM
					files_io
				 WHERE
					test_id = '$id'
				 LIMIT 1;" );
		
		if ( mysql_num_rows($query) == 0 ) {
			return ( false );
		}
		
		$obj = mysql_fetch_array( $query );
		
		return ( $obj['filename'] );

}

function FindTestFileParent( $id ) {

		 $query = mysql_query(
				"SELECT
					test_id
				 FROM
					files_io
				 WHERE
					id = '$id';" );
		
		 if ( mysql_num_rows($query) == 0 ) {
		 	return ( false );
		 }
		
	
		 $obj = mysql_fetch_array($query);
		 
		 return ( $obj['test_id'] );

}

function GetTestFileIO( $id ) {

	$sql1 = "SELECT user_id, parent_id FROM sources WHERE id='$id' ";
	$res1 = mysql_query( $sql1 );
	$obj_tmp = mysql_fetch_array( $res1 );
	$pid = $obj_tmp['parent_id'];
	
	if ( !isAdmin( $obj_tmp['user_id'] ) ) {
		$sql = "SELECT * FROM problemtest WHERE problem_id = '$pid' AND test_selection != '2' ";
	}else {
		$sql = "SELECT * FROM problemtest WHERE problem_id = '$pid' AND test_selection != '4' ";
	}
	
	//echo " " .mysql_num_rows( mysql_query( $sql ) ) . " ";
	$r_sql = mysql_query($sql);
	
	$t_sql = mysql_fetch_array( $r_sql );
	echo $t_sql['id'] . "<br>";
	
	//make all files
	$rr = mysql_query( "SELECT*FROM files_io WHERE test_id=" . $t_sql['id'] );
	while ( $t_sql = mysql_fetch_array($rr) ) {
		//echo $t_sql['filename'];
		file_put_contents( $t_sql['filename'], $t_sql['content'] );
		chmod( $t_sql['filename'], 0777 );
	}

}

function GetTestFileIOPDO( $id ) {

	global $conn;

	$sql = "SELECT user_id, parent_id FROM sources WHERE id='$id' ";
	$stmt = $conn->query( $sql ); 
	//$stmt->execute();
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$rows = $stmt->fetchAll();
	$obj_tmp = $rows[0];
	$pid = $obj_tmp['parent_id'];
	
	if ( !isAdminPDO( $obj_tmp['user_id'] ) ) {
		$sql = "SELECT * FROM problemtest WHERE problem_id = '$pid' AND test_selection != '2' ";
	}else {
		$sql = "SELECT * FROM problemtest WHERE problem_id = '$pid' AND test_selection != '4' ";
	}
	
	$stmt = $conn->query( $sql ); 
	//$stmt->execute();
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$rows = $stmt->fetchAll();
	$t_sql = $rows[0];

	//make all files
	$stmt = $conn->prepare( "SELECT*FROM files_io WHERE test_id=" . $t_sql['id'] );
	$rows = $stmt->fetchAll();
	
	foreach( $rows as $t_sql ) {
		file_put_contents( $t_sql['filename'], $t_sql['content'] );
		chmod( $t_sql['filename'], 0777 );
	}

}


?>
