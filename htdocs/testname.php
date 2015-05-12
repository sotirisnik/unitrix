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
		$name = $_GET['name'];
		
		$test_id = FindTestFileParent( $id );
		
		$parent_test_id = FindTestFileParent( $test_id );
		
		$ftpp = FindTestProblemParent($id);
		
		if ( UpdateTestFileName( $id, $name ) ) {
			CloseDatabase();
			?>
			<script>
				window.location = "index.php?probid=" + <?php echo $ftpp; ?> + "&test=" + <?php echo $test_id; ?>;
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
