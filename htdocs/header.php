<?php

	session_start();

	date_default_timezone_set('Europe/Athens');
	
	if ( !isset($_SESSION['username']) ) {
		//echo "you are not loggined <br>";
		
		if ( isset( $_GET['page'] ) ) {
			if ( $_GET['page'] != 'contact' && $_GET['page'] != 'guidelines' && $_GET['page'] != 'subguide' && $_GET['page'] != 'contact' && $_GET['page'] != 'news' ) {
				header( "Location: index.php?error=3" );
			}
		}
		
	}
	
	include "models/database.php";
	include "models/users.php";
	include "models/contests.php";
	include "models/problems.php";
	include "models/submissions.php";
	include "models/access.php";
	include "models/sourcecode.php";
	include "models/tests.php";
	include "models/general.php";
	include "models/languages.php";
	include "models/news.php";
	include "models/days.php";
	include "models/articles.php";

	if ( isset( $_SESSION['username'] ) )  {
		OpenDatabase();
		addFullname( $_SESSION['username'], $_SESSION['fullname'], 1 );
		CloseDatabase();
	}
	
	$admin = 0;
	$admin_header = "";
	
	if ( isset($_SESSION['username']) ) {
		OpenDatabase();
		
		$admin = isAdmin( $_SESSION['username'] );
		
		CloseDatabase();
		
		
		if ( $admin == 1 ) {
			$admin_header = "<li><a href='index.php?page=manage'>Διαχείριση</a></li>";
		}
	}
	
?>

<!DOCTYPE html>

	<head>

		<title> Unitrix Contest </title>
		<meta name="keywords" content="unitrix, contest, cs, unipi, gr">
		<meta name="author" content="Sotirios Nikoloutsopoulos">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="unitrix.css" >
	
		<link rel="shortcut icon" href="img/duck_logo.ico" />
	
		<script src="jquery-1.8.0.min.js"></script>
		<script src="js/calendar.js"></script>
		<script src="js/calendar-el.js"></script>
		<script src="js/calendar-setup_stripped.js"></script>
		<script src="js/main.js"></script>
	
		<script>
			function openWin( ) {
				var id = "<?php				
					if ( isset( $_GET['id'] ) ) {
						Print($_GET['id']);
					}else {
						echo "-1";
					}?>";
				myWindow=window.open('codeview.php?id=' +  id,'','width=800,height=600');
				myWindow.focus();
			}
			function showScriptError( content ) {
				document.getElementById('actionheader').innerHTML="Σφάλμα σκριπτογλώσσας";
				document.getElementById('actiontext').innerHTML=content;
				document.getElementById('actionconfirmed').innerHTML="&nbsp;Εντάξει&nbsp;&nbsp;";

				$('#actionconfirmed').click(function() {
					document.getElementById('confirmaction').style.visibility='hidden';
					return ( false );
			 	});
				
				document.getElementById('actioncancel').style.visibility='hidden';
				document.getElementById('actioncancel').innerHTML='';

				document.getElementById('confirmaction').style.visibility='';
			}
		</script>
	
	</head>
	
	<html>
	
		<body>
			
			<div id="container">
			
				<ul id="header">
						<li style='border: 0px solid black;'> <a href="http://www.unipi.gr"> <img height="72" alt="PAPEI" src="unipi.gif.png" /> </a> </li>
						<li style='border: 0px solid black;'><img src='img/unitrixlogo.png'></li>
				</ul>
				
				<div class="clear">
				
				</div>
				
				<ul id="tabs">
						<li><a href="index.php">Αρχική</a></li>
						<?php
							if ( isset( $_SESSION['username'] ) ) {
								?><li><a href="index.php?page=competitions">Διαγωνισμοί</a></li><?php
						}
						?>
						<?php echo $admin_header; ?>

						<li><a href="index.php?page=guidelines">Οδηγίες</a></li>
						<li><a href="index.php?page=contact">Επικοινωνία</a></li>
				</ul>
				
				<div class="clear">
				
				</div>
				
				<div id="timeboxfather">
					<div id="timebox">
						
						<div>
							<b>Ώρα συστήματος</b>
						</div>
						<div id="timeplace">						
						</div>
					</div>
				</div>
				
				<div class="clear">
				
				</div>
				
				
				<?php
				
					if ( isset( $_GET["page"] ) ) {
						$a = $_GET["page"] == "manage" || $_GET["page"] == "aecompetition" || $_GET["page"] == "aeproblem"
						|| $_GET["page"] == "compaccess";
						
						if ( $a && !$admin ) {
							?>
							<script>
								window.location = "index.php?error=wrongpage";
							</script> 
							<?php
							//die();
						}
						
					}

					echo "
 					<div class='gbox warningrmsg'>

									<img class='gboximg' src='img/warning.png' alt='warning'>

								        <p>Τυπώστε μόνο ότι ζητείται στα δεδομένα εξόδου.</p>

								    </div>";
					
				
					if ( isset( $_GET["error" ] ) ) {
						$error = $_GET["error"];
							if ( $error == "wrongpage" ) {
								echo "
								    <div class='gbox errormsg'>
									<img class='gboximg' src='img/block.png' alt='fail'>
								        <p>Η σελίδα που προσπαθείτε να δείτε δεν είναι έγκυρη.</p>
								    </div>
								";
							}else if ( $error == "1" ) {
								echo "
								    <div class='gbox errormsg'>
									<img class='gboximg' src='img/block.png' alt='fail'>
								        <p>Ο κωδικός που εισάγατε δεν είναι έγκυρος</p>
								    </div>
								";
							}else if ( $error == "2" ) {
								echo "
								    <div class='gbox errormsg'>
									<img class='gboximg' src='img/block.png' alt='fail'>
								        <p>Τα στοιχεία που δώσατε δεν είναι έγκυρα</p>
								    </div>
								";
							}else if ( $error == "3" ) {
								echo "
								    <div class='gbox errormsg'>
									<img class='gboximg' src='img/block.png' alt='fail'>
								        <p>Η σελίδα είναι ορατή μονάχα σε συνδεδεμένους χρήστες</p>
								    </div>
								";
							}
					}
					
					if ( isset( $_GET["insert" ] ) ) {
						$insert =  $_GET["insert"];
							if ( $insert == "1" ) {
								echo "
								    <div class='gbox successmsg'>
										<img height='32' width='32' class='gboximg' src='s_success.png' alt='success'>
								        <p>Η υποβολή σας καταχωρήθηκε επιτυχώς</p>
								    </div>
								";
							}else if ( $insert == "2" ) {
								echo "
								    <div class='gbox successmsg'>
										<img class='gboximg' src='accept2.png' alt='success'>
								        <p>Ο διαγωνισμός αποθηκεύτηκε επιτυχώς</p>
								    </div>
								";
							}else if ( $insert == "3" ) {
								echo "
								    <div class='gbox successmsg'>
										<img class='gboximg' src='accept2.png' alt='success'>
								        <p>Το πρόβλημα αποθηκεύτηκε επιτυχώς</p>
								    </div>
								";
							}else if ( $insert == "4" ) {
								echo "
								    <div class='gbox successmsg'>
										<img class='gboximg' src='accept2.png' alt='success'>
								        <p>Το αρχείο αποθηκεύτηκε επιτυχώς</p>
								    </div>
								";
							}else if ( $insert == "5" ) {
								echo "

								    <div class='gbox successmsg'>

										<img class='gboximg' src='accept2.png' alt='success'>

								        <p>Το πρόβλημα διαγράφτηκε επιτυχώς</p>

								    </div>

								";
							}else if ( $insert == "6" ) {
								echo "
								    <div class='gbox successmsg'>
										<img class='gboximg' src='accept2.png' alt='success'>
								        <p>Ο διαγωνισμός διαγράφτηκε επιτυχώς</p>
								    </div>
								";
							}else if ( $insert == "7" ) {
								echo "

								    <div class='gbox successmsg'>

										<img class='gboximg' src='accept2.png' alt='success'>

								        <p>Το άρθρο αποθηκεύτηκε επιτυχώς</p>

								    </div>

								";
							}else if ( $insert == "8" ) {
								echo "
								    <div class='gbox errormsg'>
										<img class='gboximg' src='img/block.png' alt='fail'>
								        <p>Εσφαλμένο id άρθρου</p>
								    </div>
								";
							}
					}
				?>
