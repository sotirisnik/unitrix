<?php

	function GetRemainTime( $id ) {
		$sql = "SELECT
					id,name,finish_date,access
				FROM
					competitions
				WHERE
					( NOW() BETWEEN start_date-INTERVAL 1 SECOND AND finish_date )
					AND visible='0'";//DATE(start_date) <= DATE(NOW()) AND DATE(NOW()) <= DATE(finish_date) AND CURTIME() < finish_hour";
		
		$result = mysql_query( $sql ) or die( mysql_error() );
		$num = mysql_num_rows( $result );
			
		if ( $num == 0 ) {
			return ( false );
		}

		$how_many_days = array( 0,31,28,31,30,31,30,31,31,30,31,30,31);

		$ret = array();
		
		$t = time();
		$From = date( 'Y-m-d H:i:s', $t);
		
		$first = new DateTime( $From );
		
		//echo $From;
		
		while ( $obj = mysql_fetch_array( $result ) ) {
		
			$fl = true;
			
			if ( $obj['access'] == 1 ) {
				//SELECT id,name,finish_date,access
				$Sql = "SELECT * FROM competition_access WHERE user_id='$id' AND competition_id=" . $obj['id'];// ."'";
				
				if ( mysql_num_rows( mysql_query($Sql) ) == 1 ) {
					$fl = false;
				}
			}
			
			if ( $obj['access'] == '1'  && $fl == true && isAdmin( $_SESSION['username'] ) == false ) {
				continue;
			}
			
			//$Answer .= "<li><a href='index.php?page=competitions&id=" . $obj['id'] ."'>" . $obj['name'] . "</a></li>";
			
			$val = $obj['finish_date'];// . " " . $obj['finish_hour'] . ":00:00";
			
			//echo "Val := " . $val . "<br>";
			
			//echo "f";
			
			//date_default_timezone_set('Europe/Athens');
			$timezone = new DateTimeZone('Europe/Athens');
			//$n = $first;//new DateTime('now', $timezone);			
	//		$n = new DateTime();
			$Second = new DateTime( $val );
			//print_r($n);
			//echo "<br>";
			//print_r($Second);
			//echo "<br>";
			//print_r( new DateTime() );
			
			//echo $Second->format( 'Y-m-d h:i:s' );
			//$diff = $first->diff($Second);
			
			$diff = $Second->diff($first);//$n);
			
			//echo print_r($diff);
			

			$tmp_months_val = 0;
			$rem_months = $diff->format( '%m' );
			//echo $rem_months . "<br>";
			$getM = date ( 'm' );
			$d = 0;
			while ( $rem_months > 0 ) {
				//echo "loop<br>";
				--$rem_months;
				++$getM;
				if ( $getM > 12 ) {
					$getM = 1;
				}
				$tmp_months_val += 3600*24*$how_many_days[$getM];
				$d += $how_many_days[$getM];
			}

			//echo $d . "<br>";


			$remain_days = $diff->format( '%D' );
			$remain_hours = $diff->format( '%H' );
			//echo  date( 'a', $t);
			
			if ( date( 'a', $t) == 'pm' ) {
				//$remain_hours -= 12;
			}
			
			//echo $obj['name'];
			if ( $remain_days == 1 ) {
				$remain_hours += 24;
			}else if ( $remain_days > 1 ) {
				$remain_hours += 24*($remain_days-1);
			}
			//echo "Yeah := " . $remain_hours . "<br>";
			//if ( $diff->format( '%H:%I:%S' ) != '00:00:00' )
			
			$rem = ($remain_hours < 10 ? '0' . $remain_hours : $remain_hours) . $diff->format( ':%I:%S' );
			
			if ( $rem != '00:00:00' ) {
				//$Answer .= "<li> Απομένουν: " . ($remain_hours < 10 ? '0' . $remain_hours : $remain_hours) . $diff->format( ':%I:%S' ) . "<li>";
				$tmp['time'] =  ( $tmp_months_val + $remain_hours*3600 + $diff->format('%I')*60 + $diff->format('%S') );
				$tmp['id'] = $obj['id'];
				$tmp['name'] = $obj['name'];
				$ret[] = $tmp;
			}
		}
		
		return ( $ret );
	}
	
?>	
				
