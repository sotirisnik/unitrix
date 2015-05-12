<?php

	if ( !isset( $_POST['sid'] ) ) {
		die();
	}
	
	$insert = 1;
	
	if ( $_POST['sid'] != '' ) {
		$insert = 0;
	}

	if ( !isset( $_POST['prob_name'] ) || !isset( $_POST['contest'] ) || !isset(  $_POST['creator'] ) )
		die();
	if ( !isset( $_POST['source'] ) || !isset( $_POST['time_limit'] ) || !isset(  $_POST['mb_limit'] ) )
		die();
	if ( !isset( $_POST['intro'] ) || !isset( $_POST['requirements'] ) || !isset(  $_POST['inputdet'] ) )
		die();
	if ( !isset( $_POST['outputdet'] ) || !isset( $_POST['samplecnt'] ) || !isset(  $_POST['sinput1'] ) )
		die();
	if ( !isset( $_POST['soutput1'] ) || !isset( $_POST['sexplanation1'] ) || !isset(  $_POST['sinput2'] ) )
		die();
	if ( !isset( $_POST['soutput2'] ) || !isset( $_POST['sexplanation2'] ) || !isset(  $_POST['sinput3'] ) )
		die();
	if ( !isset( $_POST['soutput3'] ) || !isset( $_POST['sexplanation3'] ) || !isset( $_POST['short_name'] ) )
		die();
	
	$creator = mysql_real_escape_string($_POST['creator']);
	$name = mysql_real_escape_string($_POST['prob_name']);
	$shortname = mysql_real_escape_string($_POST['short_name']);
	$parent_id = mysql_real_escape_string($_POST['contest']);
	$source = mysql_real_escape_string($_POST['source']);
	$time_limit = mysql_real_escape_string($_POST['time_limit']);
	$mb_limit = mysql_real_escape_string($_POST['mb_limit']);
	$intro = mysql_real_escape_string($_POST['intro']);
	$input_info = mysql_real_escape_string($_POST['inputdet']);
	$output_info = mysql_real_escape_string($_POST['outputdet']);
	
	$sample_input = mysql_real_escape_string($_POST['sinput1']);
	$sample_output = mysql_real_escape_string($_POST['soutput1']);
	$explanation_output = mysql_real_escape_string($_POST['sexplanation1']);
	
	$sample_input2 = mysql_real_escape_string($_POST['sinput2']);
	$sample_output2 = mysql_real_escape_string($_POST['soutput2']);
	$explanation_output2 = mysql_real_escape_string($_POST['sexplanation2']);
	
	$sample_input3 = mysql_real_escape_string($_POST['sinput3']);
	$sample_output3 = mysql_real_escape_string($_POST['soutput3']);
	$explanation_output3 = mysql_real_escape_string($_POST['sexplanation3']);
	$requirements = mysql_real_escape_string($_POST['requirements']);
	$tests = mysql_real_escape_string($_POST['samplecnt']);
	
	include "models/database.php";
	include "models/languages.php";
	
	$accepted_languages = "";
	OpenDatabase();
	$total_lang = TotalLanguages();
	CloseDatabase();
	
	for ( $i = 0; $i < $total_lang; ++$i ) {
		$accepted_languages .= "0";
	}
	
	OpenDatabase();
	$map_lang = MapLanguages( );
	CloseDatabase();
	
	if( !empty($_POST['check_list'])) {
		foreach($_POST['check_list'] as $check) {
				$accepted_languages[ $map_lang[$check] ] = 1;
		}
	}
	
	//echo $accepted_languages;
	//die();
	
	OpenDatabase();
	
	$sql = "";
	
	if ( $_POST['sid'] == '' ) {
	
		$sql = "
		
			INSERT INTO `grader`.`problems` (
				`id`,
				`parent_id`,
				`name`,
				`short_name`,
				`creator`,
				`source`,
				`time_limit`,
				`mb_limit`,
				`intro`,
				`input_info`,
				`output_info`,
				`sample_input`,
				`sample_output`,
				`explanation_output`,
				`sample_input2`,
				`sample_output2`,
				`explanation_output2`,	
				`sample_input3`,
				`sample_output3`,
				`explanation_output3`,
				`limits`,
				`tests`,
				`accepted_languages`
				)
				VALUES (
				'', '$parent_id', '$name', '$shortname','$creator','$source','$time_limit','$mb_limit','$intro',
				'$input_info','$output_info','$sample_input','$sample_output','$explanation_output','$sample_input2','$sample_output2',
				'$explanation_output2',	'$sample_input3','$sample_output3',
				'$explanation_output3',`limits`='$requirements',`tests`='$tests',`accepted_languages`='$accepted_languages'
				);
		
		";
	
	}else {
	
			$sql = "		
				UPDATE `grader`.`problems`

				SET `parent_id`='$parent_id',
					`name`='$name',
					`short_name`='$shortname',
					`creator`='$creator',
					`source`='$source',
					`time_limit`='$time_limit',
					`mb_limit`='$mb_limit',
					`intro`='$intro',
					`input_info`='$input_info',
					`output_info`='$output_info',
					`sample_input`='$sample_input',
					`sample_output`='$sample_output',
					`explanation_output`='$explanation_output',
					`sample_input2`='$sample_input2',
					`sample_output2`='$sample_output2',
					`explanation_output2`='$explanation_output2',
					`sample_input3`='$sample_input3',
					`sample_output3`='$sample_output3',
					`explanation_output3`='$explanation_output3',
					`limits`='$requirements',
					`tests`='$tests',
					`accepted_languages`='$accepted_languages'
				
				WHERE
					`id` = '" . $_POST['sid'] . "'
			";
	
	}
	
	$res = mysql_query( $sql ) or die( mysql_error() );
	
	CloseDatabase();
	
	header( "Location: index.php?page=manage&insert=3" );
	
?>
