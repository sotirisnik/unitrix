<?php

	function GetSourceCode( $n ) {
		$sql = "SELECT * FROM sources WHERE id=$n ";
		$result = mysql_query( $sql );
		
		$num = mysql_num_rows( $result );
	
		if ( $num == 0 )
			return "";
	
		$obj = mysql_fetch_array( $result );
		
		return ( $obj['source_code'] );
	
	}

	function GetSourceCodePDO( $n ) {

		global $conn;

		$sql = "SELECT * FROM sources WHERE id=$n ";
		
		$stmt = $conn->query( $sql ); 

		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

		$rows = $stmt->fetchAll();

		if ( $stmt->rowCount( ) == 0 )
			return "";
	
		$obj = $rows[0];
		
		return ( $obj['source_code'] );
	
	}

	function insertSourceCode( $user_id, $parent_id, $text, $compiler ) {
		$sql = "
		INSERT INTO `grader`.`sources` (
			`id` ,
			`user_id` ,
			`parent_id`,
			`source_code` ,
			`datetime`,
			`examined` ,
			`passed` ,
			`compiler_language`
			)
			VALUES (
			'', '$user_id', $parent_id, '$text', NOW(), '-1', '0', '$compiler'
			);
		";
		
		$result = mysql_query( $sql ) or die( );
		
		return ( true );
		
	}
	
	function prev_character( $tmp, $i ) {
	
		     if ( $i <= 0 ) {
				return ( 1 );
			 }
			 
			 //echo " Index := " . $i . $tmp[$i-1] . "lol <br>";
			 return ( true );
			 return ( ($tmp[$i-1] == ' ') || ($tmp[$i-1] == '\n')  );
	
	}
	
	function next_character( $tmp, $i, $len ) {
	
			 //if ( int($i+1) > $len )
				//return ( true );
			
			 return ( $tmp[$i] == ' ' );
			 //if ( $tmp[$i+1] != ' ' )
				return ( false );
			
			 return ( true );
	
	}
	
	function check( $tmp, $i, $pattern ) {
	
			$len = strlen( $pattern );
	
		    for ( $j = 0; $j < $len; ++$j )
				if ( $tmp[$i+$j] != $pattern[$j] )
					return ( false );
			
			return ( true );
			
	}
	
	function numlines( $tmp ) {
	
		$n = strlen( $tmp );
		
		$ret = 0;
		
		for ( $i = 0; $i < $n; ++$i )
			if ( $tmp[$i] == "\n" ) {
				++$ret;
			}
		
		return ( $ret );
	
	}
	
	function Cplusplus( $tmp ) {
	
			 $n = strlen( $tmp );
			 $len = $n;
			 $Code = "";
			 
			 /*
			 for ( $i = 0; $i < $n; ++$i ) {
			 
				if ( $tmp[$i] == "\"" ) {
					$start = $i;
					$end = $i;
					
					while ( $end+1 < $n && $tmp[$end+1] != "\"" ) {
						++$end;
					}
					
					echo $start . " " . $end . "<br>";
					
					$i = $end+1;
					
				}
			 }*/
			 
			 $open = 0;
			 $put_less = 0;
			 
			 for ( $i = 0; $i < $n; ++$i ) {
				if ( $tmp[$i] == "\"" ) {
					if ( $open == 0 ) {
						$Code .= "<span style='color:#800000;'>" . "\"". "</span><span style='color:#000080;'>";
						$open = 1;
					}else {
						$Code .= "</span><span style='color:#800000;'>" . "\"". "</span>";
						$open = 0;	
					}
				}else if ( $tmp[$i] == ' ' ) {
					if ( $put_less == 1 ) {
						$Code .= "</span>";
						$put_less = 0;
					}
					$Code .= ' ';
				}else if ($tmp[$i] == '+' ) {
					$Code .= "<span style='color:#800080;'>+</span>";
				}else if ($tmp[$i] == '-' ) {
					$Code .= "<span style='color:#800080;'>-</span>";
				}else if ($tmp[$i] == '>' ) {
					if ( $put_less == 1 ) {
						$Code .= "</span>";
						$put_less = 0;
					}
					$Code .= "<span style='color:#800080;'>></span>";
				}else if ($tmp[$i] == '<' ) {
					if ( $put_less == 1 ) {
						$Code .= "</span>";
						$put_less = 0;
					}
					$Code .= "<span style='color:#800080;'>&lt</span>";
					$Code .= "<span style='color:#603000;'>";
					$put_less = 1;
				}else if ($tmp[$i] == '=' ) {
					$Code .= "<span style='color:#800080;'>=</span>";
				}else if ($tmp[$i] == '{' ) {
					$Code .= "<span style='color:#800080;'>{</span>";
				}else if ($tmp[$i] == '}' ) {
					$Code .= "<span style='color:#800080;'>}</span>";
				}else if ($tmp[$i] == ';' ) {
					$Code .= "<span style='color:#800080;'>;</span>";
				}else if ( $tmp[$i] == '0' ) {
					$Code .= "<span style='color:#008c00;'>0</span>";
				}else if ( $tmp[$i] == '1' ) {
					$Code .= "<span style='color:#008c00;'>1</span>";
				}else if ( $tmp[$i] == '2' ) {
					$Code .= "<span style='color:#008c00;'>2</span>";
				}else if ( $tmp[$i] == '3' ) {
					$Code .= "<span style='color:#008c00;'>3</span>";
				}else if ( $tmp[$i] == '4' ) {
					$Code .= "<span style='color:#008c00;'>4</span>";
				}else if ( $tmp[$i] == '5' ) {
					$Code .= "<span style='color:#008c00;'>5</span>";
				}else if ( $tmp[$i] == '6' ) {
					$Code .= "<span style='color:#008c00;'>6</span>";
				}else if ( $tmp[$i] == '7' ) {
					$Code .= "<span style='color:#008c00;'>7</span>";
				}else if ( $tmp[$i] == '8' ) {
					$Code .= "<span style='color:#008c00;'>8</span>";
				}else if ( $tmp[$i] == '9' ) {
					$Code .= "<span style='color:#008c00;'>9</span>";
				}else if ( $i+4 < $n && check($tmp, $i, "throw" ) && prev_character( $tmp, $i ) && next_character( $tmp, $i+5, $len ) ) {
					$Code .= "<span style='color:#800000;'>throw</span>";
					$i += 4;
				}else if ( $i+4 < $n && check($tmp, $i, "class" ) && prev_character( $tmp, $i ) && next_character( $tmp, $i+5, $len ) ) {
					$Code .= "<span style='color:#800000;'>class</span>";
					$i += 4;
				}else if ( $i+4 < $n && check($tmp, $i, "using" ) && prev_character( $tmp, $i ) && next_character( $tmp, $i+5, $len ) ) {
					$Code .= "<span style='color:#800000;'>using</span>";
					$i += 4;
				}else if ( $i+8 < $n && check($tmp, $i, "namespace" ) && prev_character( $tmp, $i ) && next_character( $tmp, $i+9, $len ) ) {
					$Code .= "<span style='color:#800000;'>namespace</span>";
					$i += 8;
				}else if ( $i+4 < $n && check($tmp, $i, "while" ) && prev_character( $tmp, $i ) && next_character( $tmp, $i+5, $len ) ) {
					$Code .= "<span style='color:#800000;'>while</span>";
					$i += 5;
				}else if ( $i+2 < $n && check($tmp, $i, "for" ) && prev_character( $tmp, $i ) && next_character( $tmp, $i+3, $len ) ) {
					$Code .= "<span style='color:#800000;'>for</span>";
					$i += 3;
				}else if ( $i+1 < $n && check($tmp, $i, "do" ) && prev_character( $tmp, $i ) && next_character( $tmp, $i+2, $len ) ) {
					$Code .= "<span style='color:#800000;'>do</span>";
					$i += 2;
				}else if ( $i+5 < $n && check($tmp,$i,"return" ) && prev_character( $tmp, $i ) && next_character( $tmp, $i+6, $len ) ) {
					$Code .= "<span style='color:#800000; font-weight:bold; '>return</span>";
					$i += 5;
				}else if ( $i+2 < $n && check($tmp,$i,"int" ) && prev_character($tmp,$i) && next_character($tmp,$i+3,$len) ) {
					$Code .= "<span style='color:#800000;'>int</span>";
					$i += 2;
				}else if ( $i+5 < $n && check($tmp,$i,"double" ) && prev_character($tmp,$i) && next_character($tmp,$i+6,$len) ) {
					$Code .= "<span style='color:#800000;'>double</span>";
					$i += 5;
				}else if ( $i+3 < $n && check($tmp,$i,"long" ) && prev_character($tmp,$i) && next_character($tmp,$i+4,$len) ) {
					$Code .= "<span style='color:#800000;'>long</span>";
					$i += 3;
				}else if ( $i+7 < $n && check($tmp,$i,"#include" ) && prev_character($tmp,$i) && next_character($tmp,$i+8,$len) ) {
					$Code .= "<span style='color:#004a43;'>#include</span>";
					$i += 7;
				}else if ( $i+4 < $n && check($tmp,$i,"scanf" ) && prev_character($tmp,$i) && next_character($tmp,$i+5,$len) ) {
					$Code .= "<span style='color:#603000;'>scanf</span>";
					$i += 4;
				}else if ( $i+5 < $n && check($tmp,$i,"printf" ) && prev_character($tmp,$i) && next_character($tmp,$i+6,$len) ) {
					$Code .= "<span style='color:#603000;'>printf</span>";
					$i += 5;
				}else {
					$Code .= $tmp[$i];
				}
			 }
	
			 return ( $Code );
	
	}

	function FindSourceCodeParent( $id ) {

		$id = mysql_real_escape_string($id);

		$query = mysql_query( "
				SELECT
					problems.parent_id
				FROM
					sources, problems
				WHERE
					sources.parent_id = problems.id
					AND sources.id = '$id'
				LIMIT 1;" );

		if ( mysql_num_rows( $query ) == 0 ) {
			return ( false );
		}

		$obj = mysql_fetch_array( $query );

		return ( $obj['parent_id'] );

	}

	function FindSourceCodeParentOnly( $id ) {

		$id = mysql_real_escape_string($id);

		$query = mysql_query( "
				SELECT
					sources.parent_id
				FROM
					sources
				WHERE
					sources.id = '$id'
				LIMIT 1;" );

		if ( mysql_num_rows( $query ) == 0 ) {
			return ( false );
		}

		$obj = mysql_fetch_array( $query );

		return ( $obj['parent_id'] );

	}

	function SourceCodeExist( $id ) {

		$id = mysql_real_escape_string( $id );

		$query = mysql_query( "
				SELECT
					id
				FROM
					sources
				WHERE
					sources.id = '$id'
				LIMIT 1;" );

		if ( mysql_num_rows( $query ) == 0 ) {
			return ( false );
		}

		return ( true );

	}

	function PrintCodeView( $source_code ) {

		$Code = explode("\n", $source_code );
		$lineCode = numlines( $source_code );

		echo "<table class='CodeView' style='width:100%;'>";
	
			for ( $i = 0; $i < $lineCode; ++$i ) {
				echo "<tr>";
				echo "<td style='width:2%; background-color:#456; border-right-style: solid; border-right-color: gray;'>" . $i . "   </td>";
				echo "<td style='background-color:white;' > <pre style='margin: 0px 0px 0px 0px; padding: 0px;'>" . $Code[$i] . "</pre></td>";
				echo "</tr>";
			}
			
		echo "</table>";

	}

?>
