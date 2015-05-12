<?php

	function ContestParentId( $id ) {

		$id = mysql_real_escape_string( $id );

		$sql = "SELECT parent_id FROM problems WHERE id='$id'";
		$result = mysql_query( $sql );
		
		$obj = mysql_fetch_array( $result );
		
		return ( $obj['parent_id'] );
		
	}
	
	function FindContestName( $n ) {
		//return contest_name, "Λάθος Διαγωνισμός" on failure

		$n = mysql_real_escape_string( $n );

		$sql = "SELECT name FROM competitions WHERE id='$n'";
		$result = mysql_query( $sql );
		
		$num = mysql_num_rows( $result );
		
		$Answer = "";
		
		if ( $num == 0 ) {
			$Answer = "Λάθος Διαγωνισμός";
			return ( $Answer );
		}
		
		$obj = mysql_fetch_array( $result );
		$Answer = $obj['name'];
		
		return ( $Answer );
		
	}
	
	function FindAllFutureContests( ) {
	
		$result = mysql_query(
			"SELECT
				id,name,start_date,finish_date
			FROM
				competitions
			WHERE
				NOW() < start_date
				AND NOW() < finish_date
				AND visible='0';"
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
	
	function FindAllActiveContests( $username ) {
	
		$username = mysql_real_escape_string( $username );

		$id = $username;
		
		$result = mysql_query(
			"SELECT
				id,name,finish_date,access
			FROM
				competitions
			WHERE
				start_date <= NOW()
				AND finish_date >= NOW()
				AND visible='0';"
		);
		
		$num = mysql_num_rows( $result );
		
		$Answer = "";
		
		if ( $num == 0 ) {
			return ( false );
		}
		
		$rows = array();
		
		while ( $obj = mysql_fetch_array( $result ) ) {
		
			$fl = true;
		
			if ( $obj['access'] == 1 ) {
				$Sql = "SELECT * FROM competition_access WHERE user_id='$id' AND competition_id=" . $obj['id'];// ."'";
				
				if ( mysql_num_rows( mysql_query($Sql) ) == 1 ) {
					$fl = false;
				}
			}
		
			if ( $obj['access'] == '1' && $fl == true ) {
				$obj['limited'] = true;
				//$Answer .= "<li>" . $obj['name'] . "( Λήξη " . $obj['finish_date'] . ") - <i>Περιορισμένη πρόσβαση</i></li>";
			}else {
				$obj['limited'] = false;
				//$Answer .= "<li><a href='index.php?page=competitions&id=" . $obj['id'] ."'>" . $obj['name'] . "( Λήξη " . $obj['finish_date'] . ")</a></li>";//( substr($obj['finish_date'],-2,2) ) . " " . Month( substr($obj['finish_date'],-5,2) ) . ", " . $obj['finish_hour'] . ":00)" . "</a></li>";
			}
			
			$rows[] = $obj;
		}
		
		return ( $rows );
		
	}
	
	function FindAllPreviousContests( ) {
		
		$result = mysql_query(
			"SELECT
				id,name,finish_date
			 FROM
				competitions
			 WHERE
				NOW() >= finish_date
				AND visible ='0';"
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
	
	function IsActiveContest( $id ) {
	
		$id = mysql_real_escape_string( $id );

		$result = mysql_query(
			"SELECT
				id,name,finish_date,access
			FROM
				competitions
			WHERE
				start_date <= NOW()
				AND finish_date >= NOW()
				AND visible='0'
				AND id = '$id'
			LIMIT 1;"
		);
		
		return ( mysql_num_rows($result) );
	
	}

	function IsPreviousContest( $id ) {
	
		$id = mysql_real_escape_string( $id );

		$result = mysql_query(
			"SELECT
				id,name,finish_date,access
			FROM
				competitions
			WHERE
				NOW() >= finish_date
				AND visible ='0'
				AND id = '$id'
			LIMIT 1;"
		);
		
		return ( mysql_num_rows($result) );
	
	}

	function IsFutureContest( $id ) {
	
		$id = mysql_real_escape_string( $id );

		$result = mysql_query(
			"SELECT
				id,name,finish_date,access
			FROM
				competitions
			WHERE
				NOW() < start_date
				AND NOW() < finish_date
				AND visible ='0'
				AND id = '$id'
			LIMIT 1;"
		);
		
		return ( mysql_num_rows($result) );
	
	}

	function isContestLimited( $id ) {

		$id = mysql_real_escape_string( $id );

		$result = mysql_query(
			"SELECT
				*
			FROM
				competition_access
			WHERE
				competition_id = '$id'
			LIMIT 1;"
		);
		
		return ( mysql_num_rows($result) == 1 );

	}

	function DeleteContest( $id ) {

		$id = mysql_real_escape_string( $id );

		$fcp = FindContestProblems( $id );

		foreach ( $fcp as $tmp ) {
			//echo $tmp['id'] . "<br>";
			DeleteProblem( $tmp['id'] );
			$i = $tmp['id'];
			//delete competitions access
			mysql_query(
				"DELETE FROM
				 	competition_access
				 WHERE
					competition_id = '$id' "
				);
		}

		//delete competition
		mysql_query(
			"DELETE FROM
			 	competitions
			 WHERE
				id = '$id' "
			);

	}

function UsersSubmitedOnContest( $id ) {

		 //$id is the id of the contest
		 //return all distinct usernames who have submitted on the contest

		 $sql = mysql_real_escape_string( $id );

		 $res = mysql_query( 
				"SELECT
				 	DISTINCT sources.user_id
				 FROM
				 	problems, sources
				 WHERE
				 	problems.parent_id = '$id'
					AND sources.parent_id = problems.id" );

		if ( mysql_num_rows( $res ) == 0 ) {
			return ( false );
		}

		$ret = array();

		while ( $obj = mysql_fetch_array( $res ) ) {
			$ret[] = $obj;
		}

		return ( $ret );

}

?>
