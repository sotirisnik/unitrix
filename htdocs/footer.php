			<div id="footer">
				<a href='index.php?page=guidelines'> Όδηγίες </a>
				<a style='margin-left:5px;' href='index.php?page=subguide'> Παράδειγμα υποβολής </a>
				<br>
				© Copyright 2013 <a href="mailto:sotirisnik@gmail.com">Νικολουτσόπουλος Σωτήριος</a>
			</div>

		</div>
		
		
		<?php
			include "models/time.php";
			OpenDatabase();
			
			?>
			<script>
				var compCountersID = Array();
				var compCountersOID = Array();
				var compCountersTIME = Array();
				var compCountersNAME = Array();
			</script>
			
			<?php
			if ( isset( $_SESSION['username'] ) ) {
			
				$GRT = GetRemainTime( ($_SESSION['username']) );	
				//echo date( 'Y-m-d H:i:s a', time() );
				if ( $GRT != false ) {
				
				echo "<script>";
				//<?php
				
				foreach( $GRT as $tmp ) {
					?>
					t = 'compcounter' + <?php echo $tmp['id']; ?>;
					compCountersID.push( t );
					compCountersOID.push( <?php echo $tmp['id']; ?> );
					compCountersTIME.push( <?php echo $tmp['time']; ?> );
					compCountersNAME.push( "<?php echo $tmp['name']; ?>" );
					
					$('#CountD').append("<span id="+t+" ></span>");
					
					<?php
				}
				?>
				
				</script>
				
				<?php

				}
				
			}
			
			CloseDatabase();
		?>
		
		<script>
			
			<?php
				$date = new DateTime();
				$serverdate = $date->format('Y,m-1,d,H,i,s');
				$zone = $date->format('T');
			?>

			var CurrentTime = <?php print "new Date($serverdate)"; ?>;

			var DayArray=new Array("Κυριακή", "Δευτέρα","Τρίτη","Τετάρτη","Πέμπτη","Παρασκευή","Σάββατο");
			var MonthArray=new Array("Ιανουαρίου","Φεβρουαρίου","Μαρτίου","Απριλίου","Μαΐου","Ιουνίου","Ιουλίου","Αυγούστου","Σεπτεμβρίου","Οκτωβρίου","Νοεμβρίου","Δεκεμβρίου");
			var ServerDate=new Date(CurrentTime);
			
			function padlength( what ) {
				var output = (what.toString().length==1) ? "0" + what : "" + what;
				return ( output );
			}
			
			function DisplayTime( ) {
				ServerDate.setSeconds( ServerDate.getSeconds()+1 );
				var Daystring = DayArray[ ServerDate.getDay() ];
				var Datestring = padlength(ServerDate.getDate()) + " " + MonthArray[ServerDate.getMonth()] + " " + ServerDate.getFullYear();
				var Timestring = padlength(ServerDate.getHours()) + ":" + padlength(ServerDate.getMinutes()) + ":" + padlength(ServerDate.getSeconds());

				document.getElementById("timeplace").innerHTML = Daystring + ", " + Datestring + " - " + Timestring;
				//alert( compCountersID.length );

				<?php
					if ( isset( $_SESSION['username'] ) ) {
				?>
				if ( compCountersID.length == 0 ) {
					document.getElementById( "CountD" ).innerHTML = "Δεν υπάρχουν διαγωνισμοί.<br>";
				}else {
					for ( i = 0; i < compCountersID.length; ++i ) {
						ctime = document.getElementById( compCountersID[i] );
						//console.log( 'ctime := ' + ctime );
						//console.log( compCountersTIME[i] );
						if ( compCountersTIME[i] > 0 ) {
							ctime.innerHTML = "<li><a href='index.php?page=competitions&id=" + compCountersOID[i] + "'>" + compCountersNAME[i] + "</a></li>";
							ctime.innerHTML += "Απομένουν: " + padlength(Math.floor(compCountersTIME[i]/3600)) + ":" + padlength(Math.floor((compCountersTIME[i]/60)%60) ) + ":" + padlength(compCountersTIME[i]%60) + "<br>";
							--compCountersTIME[i];
						}else {
							ctime.innerHTML = "Ο χρόνος τελείωσε.<br>";
						}
					}
				}

				<?php
					}
				?>

			}
			
			window.onload = function() {
				DisplayTime();
				setInterval( "DisplayTime()", 1000 );
				
			}

		</script>

                <?php
		/*//Store for next christmas
                
		<script src="jquery.snow.js"></script>
		<script>
		$(document).ready( function(){
				$.fn.snow();
		});
		</script>*/
                ?>
		<?php
                ?>

	</body>

</html>
