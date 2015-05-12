<?php

	include "models/database.php";
	include "models/tests.php";
	
	OpenDatabase();
	
	$id = $_GET['probid'];
	$edit = $_GET['edit'];
	
	if ( isset( $_GET['testid'] ) ) {
		$testid = $_GET['testid'];
	}
	
	//echo $id . " " . $edit . " " . $testid . "<br>";
	
	if ( $_FILES["infile"]["error"] > 0 ) {
		//echo "Error: " . $_FILES["infile"]["error"] . "<br>";
		?>
		<script>
			window.location = "index.php?error=wrongpage";
		</script>
		<?php
	}else if ( !isset($edit) && $_FILES["outfile"]["error"] > 0 ) {
		//echo "Error: " . $_FILES["outfile"]["error"] . "<br>";
		?>
		<script>
			window.location = "index.php?error=wrongpage";
		</script>
		<?php
	}else {
	  
	    $tmpName = $_FILES["infile"]["tmp_name"];
		$fp = fopen($tmpName, 'r');
		  
		$content_in = fread($fp, filesize($tmpName));
		$content_in = addslashes($content_in);
		  
		fclose($fp);
		//edit = 1, means that we need to update input
		//edit = 2, means that we need to update output
		//!isset($edit), mean insert new test
		
		if ( !isset( $edit ) ) {
			$tmpName = $_FILES["outfile"]["tmp_name"];
			$fp = fopen($tmpName, 'r');
			  
			$content_out = fread($fp, filesize($tmpName));
			$content_out = addslashes($content_out);
			  
			fclose($fp);
		}
		
		//echo "<br>" . $content_in . "<br>" . $content_out;  
	
		
		//echo $content_in . "<br>" . $testid;
		//$content_in = mysql_real_escape_string( $content_in );
		//die();
	
		if ( $edit == 3 && insertTestFile( $testid, $content_in, $_POST['filenameforinput'] ) ) {
			//echo "Success";
			//die();
		}else if ( $edit == 1 && updateTestInput( $testid, $content_in ) ) {
			//echo "Success";
			//die();
		}else if ( $edit == 2 && updateTestOutput( $testid, $content_in ) ) {
			//echo "Success";
		}else if ( !isset($edit) && insertTest( $id, $content_in, $content_out ) ) {
			//echo "Success";
		}else {
			echo "Fail";
			die();
			CloseDatabase();
			?>
			<script>
				window.location = "index.php?error=wrongpage";
			</script>
			<?php
		}

		
		if ( isset( $_GET['testid'] ) ) {
		
			?>					
			<script>
				window.location = "index.php?probid=" + <?php echo $_GET['probid']; ?> + "&test=" + <?php echo $_GET['testid']; ?> + "&insert=4";
			</script>
			<?php
		
		}
		
	}
 
    CloseDatabase();

	?>
	
	<script>
		window.location = "index.php?page=aetests&id=" + <?php echo $id; ?>;
	</script>
	
	<?php
	
?>
