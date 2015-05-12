<?php

	session_start();

	if ( !isset($_GET['id'] ) ) {
		die();
	}
	
	if ( !isset( $_POST['lang'] ) ) {
		die();
	}

	//echo $_POST['lang'] . " ";
	
	/*
	if ( $_POST['lang'] == "C++" )
		$compiler = 0;
	else if ( $_POST['lang'] == "Java" )
		$compiler = 1;
	else if ( $_POST['lang'] == "C#" )
		$compiler = 2;
	else if ( $_POST['lang'] == "C" )
		$compiler = 3;
	else if ( $_POST['lang'] == "Python 2.7" )
		$compiler = 4;
	else
		$compiler = 5;
	*/

	include "models/database.php";
	include "models/sourcecode.php";
	include "models/users.php";
	include "models/problems.php"; 
	include "models/languages.php";

	OpenDatabase();	
	$compiler = GetLanguageID( $_POST['lang'] );
	CloseDatabase();

	if ( $compiler === false ) {
		die();
	}

	OpenDatabase();
	$obj = GetProblemData( $_GET['id'] );
	CloseDatabase();
	
	$allowedExts = array();
	//"cpp", "java", "c", "");
	
	OpenDatabase();
	$GetLang = GetLanguages( );
	$total_lang = TotalLanguages();
	CloseDatabase();
	
	$can_submit = 0;
				
	for ( $i = 0; $i < $total_lang; ++$i ) {
		if ( $obj['accepted_languages'][$i] == 1 )
			++$can_submit;
		}

	//if there are not available languages
	if ( $can_submit == 0 ) {
		header( "Location: index.php?error=wrongpage" );
		die();
	}
	
	for ( $i = 0; $i < $total_lang; ++$i ) {
		if ( $obj['accepted_languages'][$i] == 1 ) {
			if ( $GetLang[$i]['name'] == "C++" ) {
				$allowedExts[] = "cpp";
			}else if ( $GetLang[$i]['name'] == "Java" ) {
				$allowedExts[] = "java";
			}else if ( $GetLang[$i]['name'] == "C#" ) {
				$allowedExts[] = "cs";
			}else if ( $GetLang[$i]['name'] == "C" ) {
				$allowedExts[] = "c";
			}else if ( $GetLang[$i]['name'] == "Python 2.7" ) {
				$allowedExts[] = "py";
			}
		}
	}

	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
	
	if ( !in_array($extension, $allowedExts ) ) {
		//echo "Not allowed";
		header( "Location: index.php?error=wrongpage" );
		die();
	}
	
	if ( $_FILES["file"]["error"] > 0 ) {
		//echo "Error: " . $_FILES["file"]["error"] . "<br>";
		header( "Location: index.php?error=wrongpage" );
		die();
	}else {
	  //echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	  //echo "Type: " . $_FILES["file"]["type"] . "<br>";
	  //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	  //echo "Stored in: " . $_FILES["file"]["tmp_name"];
	  //echo "<p>";
	  //$homepage = file_get_contents( $_FILES["file"]["tmp_name"] );
	  //echo $homepage;  
	  
	  $tmpName = $_FILES["file"]["tmp_name"];
	  $fp = fopen($tmpName, 'r');
      $content = fread($fp, filesize($tmpName));
	  OpenDatabase();
      $content = mysql_real_escape_string($content);
      fclose($fp);

      if(!get_magic_quotes_gpc()){
          $fileName = mysql_real_escape_string($fileName);
      }
	 
	  CloseDatabase();
	  //echo $content;
     
	  OpenDatabase();
	  
	  $query = "SELECT parent_id FROM problems WHERE id=" . mysql_real_escape_string($_GET['id']);
	  $result = mysql_query( $query );
	  $obj = mysql_fetch_array( $result );
	  
	  insertSourceCode( $_SESSION['username'], $_GET['id'], $content, $compiler );
	  CloseDatabase();
	
      $file_info = pathinfo($_FILES['file']['name']); 
 	  
	  header( "Location: index.php?page=mysubmissions&id=" . $obj['parent_id'] . "&insert=1 " );
	  
 	  //echo "</p>";
  	}
	
?> 
