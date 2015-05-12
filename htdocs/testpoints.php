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
		$points = $_GET['points'];
		
		$ftpp = FindTestProblemParent($id);
		
		if ( UpdateTestPoints( $id, $points ) ) {
			CloseDatabase();
			?>
			<script>
				window.location = "index.php?page=aetests&id=" + <?php echo $ftpp; ?>;
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