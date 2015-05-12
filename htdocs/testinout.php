<?php

	session_start();

	include "models/database.php";
	include "models/users.php";
	include "models/tests.php";
	
	$admin = 0;
	$admin_header = "";
	
	if ( isset($_SESSION['username']) ) {
		OpenDatabase();
		
		$admin = isAdmin( $_SESSION['username'] );
		
		CloseDatabase();
		
		if ( $admin != 1 ) {
			?>
			<script>
				window.location = "index.php?error=wrongpage";
			</script>
			<?php
			die();
		}
		
		OpenDatabase();
		
		$id = $_GET['id'];
		
		echo $id;
		
		//echo $content;
		
		
		if ( $_FILES["infile"]["error"] > 0 ) {
		echo "Error: " . $_FILES["infile"]["error"] . "<br>";
		?>
		<script>
			window.location = "index.php?error=wrongpage";
		</script>
		<?php
		}
		
		$tmpName = $_FILES["infile"]["tmp_name"];
		$fp = fopen($tmpName, 'r');

		$content_in = "";

		while ( !feof( $fp ) ) {
			$content_in .= fgets($fp);
		}
$content_in = "lol";
		/*		  
		$content_in = fread($fp, filesize($tmpName));
		$content_in = mysql_escape_string($content_in);
		*/
 
		$content_in = mysql_escape_string($content_in);
		
		fclose($fp);
		
		$test_id = FindTestFileParent( $id );
		
		$parent_test_id = FindTestFileParent( $test_id );
		
		$ftpp = FindTestProblemParent($id);
		
		if ( UpdateTestFileContent( $id, $content_in ) ) {
			CloseDatabase();
			?>
			<script>
				window.location = "index.php?probid=" + <?php echo $ftpp; ?> + "&test=" + <?php echo $test_id; ?> + "&test_id=" + <?php echo $id; ?>;
			</script>
			<?php
		}
		
	}
	
	?>
	<script>
		window.location = "index.php?error=wrongpage";
	</script>
	<?php
		
	
?>
