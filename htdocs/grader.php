<?php

$command = "";

include "models/database.php";

//echo "ok";

include "models/sourcecode.php";
include "models/users.php";
include "models/tests.php";

define( "TIME_LIMIT_EXCEED", "3" );
define( "MEM_LIMIT_EXCEED", "4" );
define( "RUNTIME_ERROR", "5" );

if ( !isset( $_GET['oker'] ) ) {
	die();
}

$limit_exceeded = 0;

OpenPDODatabase( );

if ( $conn->beginTransaction() ) {
	echo "Transaction started successfully";
}else {
	echo "Unable to use transactions for this database";
	die();
}

try {

	$stmt = $conn->query( "SELECT * FROM sources WHERE examined = '0';" ); 
	//$stmt->execute();

	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 

	$rows = $stmt->fetchAll();

	if ( $stmt->rowCount( ) >= 1 ) {
		//echo "Something is being examined";
		die();
	}

	/*
	foreach( new RecursiveArrayIterator( $stmt->fetchAll() ) as $k => $v ) { 
		echo "<p>" . $v['id'] . " " . $v['name'] . "</p>";
	}*/

	$stmt = $conn->query( "SELECT * FROM sources WHERE examined = '-1';" ); 
	//$stmt->execute();

	$rows = $stmt->fetchAll();

	if ( $stmt->rowCount( ) == 0 ) {
		//echo "Nothing to be examined";
		die();
	}

	$obj = $rows[0];

	$id = $obj['id'];
	$User_id = $obj['user_id'];
	$PARENTID = $obj['parent_id'];
	$compiler = $obj['compiler_language'];

	$TL = 1;

	$content = GetSourceCodePDO( $id );

	//$compiler = GetLanguageName( $_POST['lang'] );

	$name = "tmp.cpp";

	if ( $compiler == 1 ) {
		$name = "Main.java";
	}else if ( $compiler == 2 ) {
		$name = "a.cs";
	}else if ( $compiler == 3 ) {
		$name = "tmp.c";
	}else if ( $compiler == 4 ) {
		$name = "tmp.py";
	}

	chdir('problem_grader_problem');

	file_put_contents( $name, $content );
	chmod( $name, 0777 );

	$Tname = "";
	$java_class_name = "Main";

	if ( $compiler == 1 ) {

		$len = strlen( $content );

		for ( $i = 0; $i < $len; ++$i ) {
			if ( $content[$i] == 'c' && $content[$i+1] == 'l' && $content[$i+2] == 'a' && $content[$i+3] == 's' && $content[$i+4] == 's' ) {
		
				$i += 5;
		
				while ( $content[$i] == " " ) {
					++$i;
				}
		
				while ( $content[$i] != ' ' && $content[$i] != '{' ) {
					$Tname .= $content[$i];
					++$i;
				}
		
				if ( $Tname == "main" ) {
					break;
				}
				
				$java_class_name = $Tname;
				$name = $Tname . ".java";
		
			}
		}

		file_put_contents( $name, $content );
		chmod( $name, 0777 );

	}

	if ( $compiler == "0" || $compiler == "1" || $compiler == "2" || $compiler == "3" || $compiler == "4" ) {

		if ( $compiler == 0 )
			$command = "g++ -O2 -s -static -lm -DUNITRIX $name -o a > report.txt  2>&1";
		else if ( $compiler == 1 )
			$command = "gcj-4.7 --main=" . $java_class_name . " " . $name . " > report.txt  2>&1";
		else if ( $compiler == 2 )
			$command = "gmcs " . $name . " > report.txt  2>&1";
		else if ( $compiler == 3 )
			$command = "gcc -std=c99 -O2 -s -static -lm -DUNITRIX $name -o a > report.txt  2>&1";

		//Get first inputio
		GetTestFileIOPDO( $id );	
		//End of get 1st input files

		shell_exec ( $command );

		//chmod( "a", 0777 );
		//chmod( "a.exe", 0777 );
		chmod( "report.txt", 0777 );

		$Dir1 = "report.txt";
		$F1 = fopen($Dir1,"r");
		$Content1 = fread( $F1, filesize($Dir1) );

		$compilation_error = 0;

		$Len = strlen( $Content1 );

		if ( strpos( $Content1, "error" ) !== false ) {
			$compilation_error = 1;
		}

		fclose($F1);

		if ( $compilation_error == 1 ) {
			//echo "there is compilation error :( " ;
	
			OpenDatabase();		
			$Content1 =  mysql_real_escape_string( $Content1 );
			CloseDatabase();

			$sql = "UPDATE `grader`.`sources` SET `examined` = '1', `passed` = '0', `compilation_error` = '1', `compilation_error_text` = '$Content1'  WHERE `sources`.`id` ='$id'";
	
			$stmt = $conn->query( $sql );
			//$stmt->execute();

			die();
	
		}else {

			$sql = "UPDATE `grader`.`sources` SET `examined` = '1', `compilation_error` = '0', `compilation_error_text` = ''  WHERE `sources`.`id` ='$id'";

			$stmt = $conn->query( $sql );
			//$stmt->execute();

		}

		$Passed = 1;

		$stmt = $conn->query("SELECT user_id, parent_id FROM sources WHERE id='$id'"); 
		//$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);	
		$rows = $stmt->fetchAll();
		$obj_tmp = $rows[0];
		$pid = $obj_tmp['parent_id'];

		$stmt = $conn->query(
						"SELECT
						 	time_limit, mb_limit
						 FROM
						 	problems
						 WHERE
							id = '$pid'
						 LIMIT 1;"); 
		//$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);	
		$rows = $stmt->fetchAll();
		$tmpllll = $rows[0];
		$liMit = $tmpllll['time_limit'];
		$MemliMit = $tmpllll['mb_limit'];

		if ( !isAdminPDO( $obj_tmp['user_id'] ) ) {
			$sql = "SELECT * FROM problemtest WHERE problem_id = '$pid' AND test_selection != '2' ";
		}else {
			$sql = "SELECT * FROM problemtest WHERE problem_id = '$pid' AND test_selection != '4' ";
		}

		$stmt = $conn->query( $sql ); 
		//$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);	
		$rows = $stmt->fetchAll();

		$i = 0;

		foreach ( $rows as $obj ) {
			//Run $i test with input test.in and output its output at test.out
			++$i;
	
			file_put_contents( "test.in", $obj['input'] );
			chmod( "test.in", 0777 );

			file_put_contents( "test.out", $obj['output'] );
			chmod( "test.out", 0777 );		
	
			//make all files io
			$what_obj = $obj['id'];
			OpenDatabase();
			$what_obj = mysql_real_escape_string( $obj['id'] );
			CloseDatabase();

			$stmt_inside = $conn->query( "SELECT*FROM files_io WHERE test_id = '$what_obj'" ); 
			//$stmt_inside->execute();
			$stmt_inside->setFetchMode(PDO::FETCH_ASSOC);	
			$rows_inside = $stmt_inside->fetchAll();

			foreach ( $rows_inside as $t_sql ) {
				//echo $t_sql['filename'];
				file_put_contents( $t_sql['filename'], $t_sql['content'] );
				chmod( $t_sql['filename'], 0777 );
			}
			//end of make all files_io

			$script_local_error = "";//errors of script languages e.x. python$compiler

			$java_MemliMit = 64;
			$csharp_MemliMit = 64;
			$csharp_timeliMit = $liMit;

			if ( $compiler == 1 )
				$java_MemliMit = $MemliMit + 64;

			if ( $compiler == 2 ) {
				$csharp_timeliMit = $liMit + 10;
				$csharp_MemliMit = $MemliMit + 64;
			}

			echo "lololol" . $java_MemliMit . " " . $csharp_MemliMit;// . " " . $command;

			if ( $compiler == 0 || $compiler == 3 )
				$command = "( ./grader ./a $liMit $MemliMit ) < test.in" . " > test_tmp.out";
			else if ( $compiler == 1 )				
				$command = "( ./grader ./a.out $liMit $java_MemliMit java ) < test.in" . " > test_tmp.out";
			else if ( $compiler == 2 )				
				$command = "( ./grader a.exe $csharp_timeliMit $csharp_MemliMit c# ) < test.in > test_tmp.out";
			else if ( $compiler == 4 ) {
				$command = "( ./grader tmp.py $liMit $MemliMit python2.7 ) < test.in" . " > test_tmp.out 2> local_script_error.txt";

				//check local_script_error
				chmod( "local_script_error.txt", 0777 );

				$Dir1 = "local_script_error.txt";
				$F1 = fopen($Dir1,"r");
				$Content1 = fread( $F1, filesize($Dir1) );

				$found_error = 0;

				$Len = strlen( $Content1 );

				if ( strpos( $Content1, "Error" ) !== false ) {
					$found_error = 1;
				}

				if ( $found_error == 1 ) {
					OpenDatabase();
					$script_local_error = mysql_real_escape_string( $Content1 );
					CloseDatabase();
				}

				fclose($F1);

				//end of local_script_error

			}else
				die();

			echo " lol " . $command . " lol ";

			$ret = system ( $command, $return_value );

			chmod( "test_tmp.out", 0777 );
	
			$f = fopen("time_info.txt","r");
			$testtime = fscanf( $f, "%lf" );
			$testtime = $testtime[0];

			if ( $return_value == TIME_LIMIT_EXCEED ) {
				$limit_exceeded = TIME_LIMIT_EXCEED;		
			}else if ( $return_value == MEM_LIMIT_EXCEED || $return_value == RUNTIME_ERROR ) {
				$limit_exceeded = MEM_LIMIT_EXCEED;		
			}else if ( $return_value == RUNTIME_ERROR ) {
				$limit_exceeded = RUNTIME_ERROR;		
			}

			$f = fopen("exit_status.txt","r");
			$exit_status = fread( $f, filesize("exit_status.txt") );

			if ( $exit_status != 0 ) {
				$Passed = 0;
			}
	
			$dir1 = "test.out";
			$dir2 = "test_tmp.out";

			$passed = 0;

			if ( shell_exec( "diff " . $dir1 . " " . $dir2 ) == "" ) {
				echo "Correct Solution<br>";
				$passed = 1;
			}else {
				echo "Wrong Answer<br>";
				$Passed = 0;
			}

			$stmt_inside = $conn->query( "SELECT * FROM feedback WHERE test_id = '$i' AND submit_id='$id'" ); 
			//$stmt_inside->execute();
			$stmt_inside->setFetchMode(PDO::FETCH_ASSOC);	
			$rows_inside = $stmt_inside->fetchAll();

			if ( $stmt_inside->rowCount( ) == 0 ) {

				$sql = "
						INSERT INTO `grader`.`feedback` (`id`, `submit_id`, `test_id`, `passed`, `time`, `script_local_error`, `exit_status`, `limit_exceeded`)
						VALUES (NULL, '$id', '$i', '$passed', '$testtime', '$script_local_error', '$exit_status', '$limit_exceeded' );
				";

				$conn->query( $sql );

			}else {
		
				$sql = "

					UPDATE

						`grader`.`feedback`

					SET

						`passed` = '$passed',
						`time` = '$testtime',

						`script_local_error` = '$script_local_error',
						`exit_status` = '$exit_status',
						`limit_exceeded` = '$limit_exceeded'
					WHERE
						`feedback`.`submit_id` = '$id'

						AND `feedback`.`test_id` = '$i';
					";

				$stmt_inside = $conn->query( $sql );
				//$stmt->execute();

			}
	
		}

	}

	if ( $Passed == 1 ) {
		$sql = "SELECT* FROM sources WHERE user_id='$User_id' AND parent_id='$PARENTID' AND active='1' LIMIT 1 ";

		$stmt_inside = $conn->query( $sql ); 
		//$stmt_inside->execute();
		$stmt_inside->setFetchMode(PDO::FETCH_ASSOC);	
		$rows_inside = $stmt_inside->fetchAll();

		if ( $stmt_inside->rowCount( ) == 1 ) {
			//echo "Yeah exists <br>";
			$sql = "UPDATE `grader`.`sources` SET `active` = '0' WHERE `user_id`='$User_id' AND `parent_id`='$PARENTID' AND `active`='1' ";
			
			$stmt = $conn->query( $sql );
			//$stmt->execute();

		}

		$sql = "UPDATE `grader`.`sources` SET `active` = '1' WHERE `user_id`='$User_id' AND `id`='$id' ";
		
		$stmt = $conn->query( $sql );
		//$stmt->execute();

	}

	$sql = "UPDATE `grader`.`sources` SET `examined` = '1', `passed` = '$Passed' WHERE `sources`.`id` ='$id'";

	$stmt = $conn->query( $sql );
	//$stmt->execute();

	shell_exec( "rm *.txt *.c *.cpp *.java *.in *.out *.h a *.py *.cs a.exe" );

	$conn->commit();

}catch( PDOException $e ) {
	$conn->rollback();
	echo "Error: " . $e->getMessage();
}

ClosePDODatabase();

?>
