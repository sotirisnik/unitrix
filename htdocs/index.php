<?php
	include "header.php";
?>

<div id="confirmaction" style="position:absolute; left:0px; top:0px; height:100%; width:100%; visibility: hidden;"> 
	<table width="100%" height="100%" background="img/ew.png">
		<tr>
			<td align="center">
				<div style="width:300px;text-align: left;">
					<div style='border: 1px solid #2C71B8; border-radius: 5px 5px 5px 5px;' class="box">
						<div style="padding: 0 10px; margin-top: 15px;">
							<h2 id="actionheader"></h2>
							<p id="actiontext"></p>
						</div>
						<div style="padding: 10px 20px 0;margin-bottom: 25px;">
							<div class="buttons">
								<p>
									<a href="#" id="actionconfirmed" class="greenbtn"></a>
									<a href="#" id="actioncancel" class="bluebtn" onclick="document.getElementById('confirmaction').style.visibility='hidden';return false;">&nbsp;Άκυρο</a>
								</p>
							</div>
						</div>
					</div>
					<div class="boxbottom"></div>
				</div>
	       </td>
	   </tr>
	</table>
</div>

<div id ="left">

	<?php
	
		if (  !isset( $_GET["id"] ) && isset( $_GET["page"] ) && $_GET["page"] == "competitions" ) {

			echo"
			<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Μελλοντικοί Διαγωνισμοί</h2>
				</div>
				<div class='competitions'>
			";
					OpenDatabase();
					$fafc = FindAllFutureContests( );
					if ( $fafc == false ) {
						echo "Δεν υπάρχουν διαγωνισμοί";
					}else {
						echo "<ul>";
						foreach ( $fafc as $obj ) {
							echo "<li>" . $obj['name'] . "( <b>" . $obj['start_date'] . " - " . $obj['finish_date'] . ")</b></li>";
						}
						echo "</ul>";
					}
					CloseDatabase();
			echo "
				</div>
			</div>
			
			<div class='roundbox'>
				<div class='roundtitle' >
					<h2>Ενεργοί Διαγωνισμοί</h2>
				</div>
				<div class='competitions'>
			";
			
				OpenDatabase();
				$faac = FindAllActiveContests( $_SESSION['username'] );
				
				if ( $faac == false ) {
					echo "Δεν υπάρχουν διαγωνισμοί";
				}else {
				
					echo "<ul>";
				
					foreach( $faac as $obj ) {
					
						if ( $obj['limited'] == '1' ) {
							echo "<li>" . $obj['name'] . "( Λήξη " . $obj['finish_date'] . ") - <i>Περιορισμένη πρόσβαση</i></li>";
						}else {
							echo "<li><a href='index.php?page=competitions&id=" . $obj['id'] ."'>" . $obj['name'] . "( Λήξη " . $obj['finish_date'] . ")</a></li>";//( substr($obj['finish_date'],-2,2) ) . " " . Month( substr($obj['finish_date'],-5,2) ) . ", " . $obj['finish_hour'] . ":00)" . "</a></li>";
						}
					
					}
					
					echo "</ul>";
				
				}
				
				CloseDatabase();
			echo "
				</div>
			</div>

			<div class = 'roundbox'>
				<div class='roundtitle'>
					<h2>Προηγούμενοι Διαγωνισμοί</h2>
				</div>
				<div class='competitions''>";
				
					OpenDatabase();
					$fapc = FindAllPreviousContests();
					
					if ( $fapc == false ) {
						echo "Δεν υπάρχουν διαγωνισμοί";
					}else {
						echo "<ul>";
						foreach( $fapc as $obj ) {
							echo "<li><a href='index.php?page=competitions&id=" . $obj['id'] ."'>" . $obj['name'] . "( Λήξη " . $obj['finish_date'] . ")</a></li>";//( substr($obj['finish_date'],-2,2) ) . " " . Month( substr($obj['finish_date'],-5,2) ) . ", " . $obj['finish_hour'] . ":00)" . "</a></li>";
						}
						echo "</ul>";
					}
					
					CloseDatabase();
			
			echo "
				</div>
			</div>
			";
		}else if (  isset( $_GET["id"] ) && isset( $_GET["page"] ) && $_GET["page"] == "competitions" ) {
			
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>
			";
			
			OpenDatabase();
			
			if ( ( IsFutureContest( $_GET['id'] ) || LimitedAccess( $_GET['id'], $_SESSION['username'] ) ) && !$admin ) {
				//redirect
				//echo "has limited access";
				CloseDatabase();
				
			?>
			
			<script>
				window.location = "index.php?error=wrongpage";
			</script>
			
			<?php
				die();
			}else {
				CloseDatabase();
			}
			
			OpenDatabase();
			echo FindContestName( $_GET['id'] );
			CloseDatabase();
			
			echo"
				</h2>
				</div>
				<div class='competitions'>
			";
			
			OpenDatabase();
			$FAP = FindContestProblems( $_GET['id'] );
			
			if ( $FAP != false ) {
			
				$cnt = 1;

				foreach( $FAP as $obj ) {
					echo "<div class='problembox'>
					          <ul>
						      <li>
						          <a href='index.php?page=problems&id=" . $obj['id'] ."'>" . " <h2>Πρόβλημα $cnt </h2> </a>
						      </li>
						      <li>
						          <a href='index.php?page=problems&id=" . $obj['id'] ."'>" . $obj['name'] . "</a>
						      </li>
						  </ul>
					      </div>";
					++$cnt;
				}
			}else {
				echo "Δεν υπάρχουν προβλήματα";
			}
			
			CloseDatabase();
			
			echo "<div class='clear'></div>";

			echo "<hr>";

			echo "<b>Ενεργές υποβολές</b>";
		
			OpenDatabase();
			$fmas = FindMyActiveSubmissions( $_GET['id'], $_SESSION['username'] );
			
			echo "<ul style='clear:both;padding: 10px 20px;'>";
			
			if ( $fmas == 0 ) {
				echo "<li>
					<i>Δεν υπάρχουν ενεργές υποβολές</i>
				      </li>";
			}else {
				foreach( $fmas as $obj ) {
					echo "<li><a href='index.php?page=viewsource&id=". $obj['id'] ."'>" . $obj['short_name'] ."</a></li>";
				}
			}
			
			echo "</ul>";
			
			CloseDatabase();
		
			echo "<a href='index.php?page=mysubmissions&id=" . $_GET['id'] . "'> Προβολή όλων των υποβολών </a>";
		
			echo "<hr>";
		
			OpenDatabase();
			//echo SubmitProblems( $_GET['id'], FindUserId( $_SESSION['username'] ) );
			$tid = FindAllProblemsSubmissionsForUserSelectFirstID( $_GET['id'], $_SESSION['username'] );//"-1";
			$SPMS = SubmitProblems( $_GET['id'], $_SESSION['username'] );
			
			$IsActiveCtest = IsActiveContest( $_GET['id'] );
			
			$GetLang = GetLanguages( );
			$total_lang = TotalLanguages();
			?>
			<script>
				var GetLang = Array();
				<?php
					for ( $i = 0; $i < $total_lang; ++$i ) {
						?>
						GetLang.push( "<?php echo $GetLang[$i]['name']; ?>" );
						<?php
					}
				?>
			</script>
			<?php
			
			if ( $SPMS == false ) {
				echo "Δεν υπάρχουν προβλήματα";
			}else if ( $IsActiveCtest == 1 || $admin ) {
			
				 echo "
				<b>Υποβολή κώδικα για αξιολόγηση<b>
					<form id='codesubmitform' name='codesubmitform' enctype='multipart/form-data' method='post' action='codesubmit.php?id=$tid'>
						<table>
							<tr>
								<td>Πρόβλημα</td>
								<td>";
			
			
				$fapsfus = $SPMS;
				
				if ( $fapsfus == false ) {
					echo "Δεν υπάρχουν προβλήματαf";
				}else {
					
					?>
				
						<select id='lang2' name='lang2' onchange='codesubmitform.action = "codesubmit.php?id=" + this.value;changeLangInput();'>
						<script>
							var ProbLang = new Array();
						</script>
					<?php
				
						foreach ( $fapsfus as $obj ) {
							echo "<option value='" . $obj['id'] . "'>" . $obj['name'] . "</option>";
							?>
							<script>
								ProbLang.push( "<?php echo $obj['accepted_languages']; ?>" );
								console.log( ProbLang );
							</script>
							<?php
						}
					?>
						</select>
					<?php
					
							echo "
							</td>
						</tr>
						<tr>
							<td>Γλώσσα</td>
							<td>
								<select id='lang' name='lang'>";
									?>
									<script>
										var x = document.getElementById("lang");
										for ( var i = 0; i < ProbLang[0].length; ++i ) {
											if ( ProbLang[0][i] == 1 ) {
												var option = document.createElement("option"); 
												option.text = GetLang[ i ];
												option.value = GetLang[ i ];
												try {
												  // for IE earlier than version 8
												  x.add(option,x.options[null]);
												}catch (e) {
												  x.add(option,null);
												}
											}
										}
									</script>
									<?php
									//<option value='C++'> C++ </option>
									//<option value='Java'> Java </option>
									//<option value='C#'> C# </option>
							echo "
								</select>
							</td>
						</tr>
						<tr>
							<td>Αρχείο</td>
							<td>
								<input type='file' name='file' id='file'>
							</td>
						</tr>
						<tr>
							<td></td>
						";
						
						
						?>
						
									<td><a class='greenbtn' name='submit' href='' onclick="document.forms['codesubmitform'].submit();return false;" >Υποβολή</a></td>
						<?php
						echo "
							</tr>
							</table>
						</form>
						";

				
				}

			}	
			//
			
			
			CloseDatabase();
			
		
			echo "
				</div>
			</div>
			";
			
		}else if (  isset( $_GET["id"] ) && isset( $_GET["page"] ) && $_GET["page"] == "problems" ) {
		
			OpenDatabase();
			
			if ( ProblemExist($_GET['id']) ) {
			
				//if ( $admin == 0 )
				if ( ( LimitedAccess( ContestParentId($_GET['id']), $_SESSION['username'] ) || IsFutureContest( ContestParentId($_GET['id']) ) ) && !$admin ) {
					//redirect Yo!
					echo "has limited access";
					CloseDatabase();
					
				?>
				
				<script>
					window.location = "index.php?error=wrongpage";
				</script>
				
				<?php
					die();
				}else {
					CloseDatabase();
				}
			    
			    OpenDatabase();
			
			    $obj = GetProblemData( $_GET['id'] );

				echo "
				<div class='roundbox'>
					<div class='roundtitle'>
						<h2> Πρόβλημα:";
						echo $obj['name'];
				echo "
						</h2>
					</div>
					<div class='competitions'>";
				?>
				<a href="index.php?page=competitions&id=<?php echo $obj['parent_id']; ?>"> <<Επιστροφή στο '<?php echo  FindContestName($obj['parent_id']); ?>' </a>
				<?php
				echo "
						<hr>
						<h2> Εκφώνηση </h2>
						<p>
				";
				
				echo nl2br($obj['intro']);

				if ( $obj['input_info'] != '' ) {
					echo "
							</p>
							<hr>
							<h2> Δεδομένα εισόδου </h2>
							<p>";
				
					echo nl2br($obj['input_info']);
				}

				if ( $obj['output_info'] != '' ) {
					echo "
							</p>
							<hr>
							<h2> Δεδομένα εξόδου </h2>
							<p>";
				
					echo nl2br($obj['output_info']);
				}

				echo "
						</p>
						<hr>
						<h2> Περιορισμοί </h2>
						<ul>
							<li>Όριο εκτέλεσης: 1 sec.</li>
							<li>Όριο μνήμης: 64MB. </li>
						</ul>
						<hr>";

				if ( $obj['sample_input'] != '' ) {
					echo "
							<h2> Παράδειγμα εισόδου </h2>
							<p>";
						
					echo nl2br($obj['sample_input']);
				
					echo"
							</p>
							<hr>
							<h2> Παράδειγμα εξόδου </h2>
							<p>";
				
					echo nl2br($obj['sample_output']);
					
					echo "
							</p>
							<hr>";

				}

				if ( $obj['explanation_output'] != '' ) {
						echo "<h2>Επεξήγηση παραδείγματος</h2>";
						echo "<p>";
						echo nl2br( $obj['explanation_output'] );
						echo "</p>";
						echo "</hr>";
				}

				if ( $obj['sample_input2'] != '' ) {
					echo "
							<h2> Παράδειγμα εισόδου 2</h2>
							<p>";
						
					echo nl2br($obj['sample_input2']);
				
					echo"
							</p>
							<hr>
							<h2> Παράδειγμα εξόδου 2</h2>
							<p>";
				
					echo nl2br($obj['sample_output2']);
					
					echo "
							</p>
							<hr>";

					if ( $obj['explanation_output2'] != '' ) {
						echo "<h2>Επεξήγηση παραδείγματος 2</h2>";
						echo "<p>";
						echo nl2br( $obj['explanation_output2'] );
						echo "</p>";
						echo "</hr>";
					}


				}


				if ( $obj['sample_input3'] != '' ) {
					echo "
							<h2> Παράδειγμα εισόδου 3 </h2>
							<p>";
						
					echo nl2br($obj['sample_input3']);
				
					echo"
							</p>
							<hr>
							<h2> Παράδειγμα εξόδου 3 </h2>
							<p>";
				
					echo nl2br($obj['sample_output3']);
					
					echo "
							</p>
							<hr>";

					if ( $obj['explanation_output3'] != '' ) {
						echo "<h2>Επεξήγηση παραδείγματος 3</h2>";
						echo "<p>";
						echo nl2br( $obj['explanation_output3'] );
						echo "</p>";
						echo "</hr>";
					}


				}


				$tid = $_GET['id'];
				
				//echo $obj['accepted_languages'];
				$GetLang = GetLanguages( );
				$total_lang = TotalLanguages();
				
				$can_submit = 0;
				
				$languages_length = count ( $GetLang );

				for ( $i = 0; $i < min( $languages_length, $total_lang ); ++$i ) {
					if ( $obj['accepted_languages'][$i] == 1 )
						++$can_submit;
				}
				
				$IsActiveCtest = IsActiveContest( ContestParentId($_GET['id']) );
				
				if ( $can_submit != 0 && ( $IsActiveCtest == 1 || $admin ) ) {
					echo "
							<b>Υποβολή κώδικα για αξιολόγηση<b>

							<form id='codesubmitform' name='codesubmitform' enctype='multipart/form-data' method='post' action='codesubmit.php?id=$tid'>
								<table>
									<tr>
										<td>Γλώσσα</td>
										<td>
											<select name='lang'>";
											
											for ( $i = 0; $i < $total_lang; ++$i ) {
												if ( $obj['accepted_languages'][$i] == 1 ) {
													?><option value='<?php echo $GetLang[$i]['name'];?>'> <?php echo $GetLang[$i]['name']; ?> </option><?php
												}
											}
												//<option value='C++'> C++ </option>
												//<option value='Java'> Java </option>
												//<option value='C#'> C# </option>
									echo	"
											</select>
										</td>
									</tr>
									<tr>
										<td>Αρχείο</td>
										<td>
											<input type='file' name='file' id='file'>
										</td>
									</tr>
									<tr>
										<td></td>
							";
							?>
							
										<td><a class='greenbtn' name='submit' href='' onclick="document.forms['codesubmitform'].submit();return false;" >Υποβολή</a></td>
							<?php
							echo "
								</tr>
								</table>
							</form>";
					}else {
						if ( $IsActiveCtest && !$admin ) {
							echo "<b>Δεν έχουν επιλεχθεί γλώσσες για υποβολή.<b>";
						}
					}
					
					echo "		
					</div>
				</div>";
			}else {
			
			CloseDatabase();
			?>
			<script>
				window.location = "index.php?error=wrongpage";
			</script>
			<?php
			die();

				echo "
				<div class='roundbox'>
					<div class='roundtitle'>
						<h2> Άγνωστο Πρόβλημα </h2>
					</div>
					<div class='competitions'>
						Δεν υπάρχει τέτοιο πρόβλημα.
					</div>
				</div>
				";
			
			}
			
			CloseDatabase();
			
		}else if (  isset( $_GET["id"] ) && isset( $_GET["page"] ) && $_GET["page"] == "viewsub" ) {
			echo "
			<div class='roundbox'>
			
				<div class='competitions'>
			";
			
			OpenDatabase();

			if ( isMysubmission( $_GET['id'], $_SESSION['username'] ) == false && !$admin ) {
				CloseDatabase();
				//echo "redirect";
				?>
				<script>
					window.location = "index.php?error=wrongpage";
				</script>
				<?php
				die();
			}


			$gsr = GetSubmissionResults( $_GET['id'] );
			
			if ( $gsr == false ) {
				//redirect
			?>
				<script>
					window.location = "index.php?error=wrongpage";
				</script>
			<?php	
			}else {


				$name = FindContestName( SubmissionContestParentID($_GET['id']) );
	
				echo "<div style='margin-top:5px;'></div>";
			
				echo "<a href='index.php?page=competitions&id=" . FindSourceCodeParent($_GET['id']) . "'><<Επιστροφή στο '" . $name . "'</a>";
			
				echo "<div style='margin-bottom: 10px;'></div>";

				//first object contains (name,compiler_language,compilatiton_language,compilation_error)
				
				echo "<h2 style='margin-top: 10px;' > Αξιολόγηση για το πρόβλημα '" . $gsr[0]['problem_name'] . "' </h2>";
				
				$LANG = "C++";
		
				if ( $gsr[0]['compiler_language'] == '1' ) {
					$LANG = "Java";
				}else if ( $gsr[0]['compiler_language'] == '2' ) {
					$LANG = "C#";
				}else if ( $gsr[0]['compiler_language'] == '3' ) {
					$LANG = "C";
				}else if ( $gsr[0]['compiler_language'] == '4' ) {
					$LANG = "Python 2.7";
				}
				
				echo "<p> <b> Γλώσσα Προγραμματισμού: </b> " . $LANG . " </p>";
				
				if ( $gsr[0]['examined'] == -1 ) {
					echo "<p> <b> Κατάσταση: </b> Δεν έχει γίνει ακόμα η αξιολόγηση</p>";
				}else {
				
					if ( $gsr[0]['compilation_error'] == 0 ) {
						echo "<p> <b> Κατάσταση: <b> Η μεταγλώττιση ήταν επιτυχής. </p>";
						
						echo "<table class='report'>";
						
						$cnt = 0;
						
						foreach( $gsr[1] as $obj ) {
						
							++$cnt;
						
							$state = "Σωστή απάντηση ( 100% )";
							
							$substate = "subactived";
							
							if ( $obj['limit_exceeded'] == 4 || $obj['limit_exceeded'] == 5 ) {
								$state = "Σφάλμα Κατάτμησης";
								$substate = "subfailed";
							}else if ( $obj['limit_exceeded'] == 3 ) {
								$state = "Υπέρβαση χρονικού ορίου";
								$substate = "subfailed";
							}else if ( $obj['exit_status'] != 0 ) {
								$state = "Μη μηδενική έξοδος (".$obj['exit_status'].")";
								$substate = "subfailed";
							}else if ( $obj['passed'] == 0 ) {
								$state = "Λάθος απάντηση";
								$substate = "subfailed";
							}

							$gts = GetTestSelection( $gsr[0]['parent_id'], $obj['test_id'] );
							
							$viewtest = "";
							$viewtest_link = "";

							if ( $gts == '0' ) {
								$viewtest = "";
							}else if ( $gts == '1' ) {
								$viewtest = "Προβολή";
								$viewtest_link = "index.php?page=feedback&test_id=" . GetTestSelectionID( $gsr[0]['parent_id'], $obj['test_id'] ) . "&problem=" . $gsr[0]['parent_id'];
							}else if ( $gts == '2' ) {
								$viewtest = "";
							}

							?>
							<script> var con = <?php echo json_encode( "<pre>" .  Cplusplus( $obj['script_local_error'] ) . "</pre>" ); ?> </script>
							<?php
							
							echo "
										<tr class='" . $substate . " reptest'>
											<td style='width: 50px; text-align: center;'> " . $cnt . " </td>
											<td style='width: 250px; text-align: center;'> " . $state . "</td>
											<td style='width: 130px; text-align: center;'>" . $obj['time'] . " δευτερόλεπτα</td>
											<td style='width: 75px; text-align: right; padding-right: 30px;'> <a href='$viewtest_link'> $viewtest </a> "; ?>

		<?php
			
			if ( strlen( $obj['script_local_error'] ) > 0 ) {
			?>
		
				<a align="right" href='#' onclick="showScriptError( con )">
					<img style='width: 12px; height: 12px;' src='http://unitrix.cs.unipi.gr/img/warning.png'>
				</a> 
		<?php
			}
		?>
						<?php echo " </td>
											
										</tr>
							";
							
						
						}
						
						echo "</table>";
						
					}else {
						echo "<p> <b> Κατάσταση: </b> Η μεταγλώττιση απέτυχε. </p>";
						
						echo "<pre>" . $gsr[0]['compilation_error_text'] . "</pre>";
						
					}
				
				}
				
				echo "
				<div style='margin-top: 10px;' >
						<a href='index.php?page=viewsource&id=" . $_GET['id'] . "'> Προβολή πηγαίου κώδικα </a>
					</div>
				";
			}
			
			CloseDatabase();
			
			echo "		
				</div>
			</div>
			";
			
			
		}else if (  isset( $_GET["id"] ) && isset( $_GET["page"] ) && $_GET["page"] == "mysubmissions" ) {
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>";
			
			OpenDatabase();
			$name = FindContestName( $_GET['id'] );
			echo $name;
			CloseDatabase();
			
			echo" - Υποβολές
				   </h2>
				</div>
				<div class='competitions'>
			";
			
			
			echo "<div style='margin-top:5px;'></div>";
			
			echo "<a href='index.php?page=competitions&id=" . $_GET['id'] . "'><<Επιστροφή στο '" . $name . "'</a>";
			
			echo "<div style='margin-bottom: 10px;'></div>";
			
			OpenDatabase();
			
			$fapsfu = FindAllProblemsSubmissionsForUser( $_GET['id'], $_SESSION['username'] );
			
			$cnt = 1;
			
			if ( $fapsfu == false ) {
				echo "Δεν υπάρχουν προβλήματα<hr>";
			}else {
			
				foreach( $fapsfu as $obj ) {
				
					echo "
					<div class='submissionbox'>
						
							<a href='index.php?page=problems&id=" . $obj['id'] ."'>" . " <h2>Πρόβλημα " . $cnt . " (" . $obj['name'] . ") </h2> </a>";
					
							if ( $obj['last'] == false ){
							    echo "<div align='center'>Δεν έχουν γίνει υποβολές</div>";
					        }else {
								foreach ( $obj['last'] as $tmp_obj ) {
									
									$sub = "subexamine";
									$sub_what = "Αναμονή";
									$active_word = "";
									$Color = "border: 1px solid #a1b4c6;";
									
									if ( $tmp_obj['examined'] == 1 ) {
										if ( $tmp_obj['passed'] == 1 ) {
											$sub = 'subactive';
											$sub_what = "Επιτυχής";
											if ( $tmp_obj['active'] == 0 ) {
												$active_word = "<a style='color:#2C71B8;' href='activate.php?id=" . $tmp_obj['id'] . "'> Activate </a>";
											}else {
												$active_word = "Active";
												$Color = "background-color: #A1D873;";
											}
										}else {
											$sub = 'subfailed';
											$sub_what = "Απέτυχε";
										}
									}
									
									echo "
									
										<div style='$Color' class='submission " . $sub ."'>
										<div style='float:right;width:100px;'><b> $active_word </b></div>		
										<div style='float:left; width:105px;'><a href='index.php?page=viewsub&id=" . $tmp_obj['id'] . "'>Υποβολή " .
										$tmp_obj['id'] . "</a></div>
										
										<div style='float:left; width: 75px; font-size: 95%;' > " . compiler_language( $tmp_obj['compiler_language'] ) ." </div>
										<div style='float:left; width:200px;'> " . $tmp_obj['datetime'] . "</div>" .
											$sub_what . "
										</div>";
								
								}
							}
							
					echo "
						</div>
						<hr>
					";
					
					++$cnt;
				
				}
			
			}
			
			CloseDatabase();
			
			OpenDatabase();
			$tid = FindAllProblemsSubmissionsForUserSelectFirstID( $_GET['id'], $_SESSION['username'] );
			$SPMS = SubmitProblems( $_GET['id'], $_SESSION['username'] );
			
			$IsActiveCtest = IsActiveContest( $_GET['id'] );
			
			$GetLang = GetLanguages( );
			$total_lang = TotalLanguages();
			?>
			<script>
				var GetLang = Array();
				<?php
					for ( $i = 0; $i < $total_lang; ++$i ) {
						?>
						GetLang.push( "<?php echo $GetLang[$i]['name']; ?>" );
						<?php
					}
				?>
			</script>
			<?php
			
			//σημάδι
			
			if ( $SPMS == false ) {
				echo "Δεν υπάρχουν προβλήματα";
			}else if ( $IsActiveCtest == 1 || $admin ) {
			
				 echo "
				<b>Υποβολή κώδικα για αξιολόγηση<b>
					<form id='codesubmitform' name='codesubmitform' enctype='multipart/form-data' method='post' action='codesubmit.php?id=$tid'>
						<table>
							<tr>
								<td>Πρόβλημα</td>
								<td>";
			
			
				$fapsfus = $SPMS;
				
				if ( $fapsfus == false ) {
					echo "Δεν υπάρχουν προβλήματα";
				}else {
					
					?>
				
						<select id='lang2' name='lang2' onchange='codesubmitform.action = "codesubmit.php?id=" + this.value;changeLangInput();'>
				
					<script>
						var ProbLang = new Array();
					</script>
				
					<?php
				
						foreach ( $fapsfus as $obj ) {
							echo "<option value='" . $obj['id'] . "'>" . $obj['name'] . "</option>";
							?>
							<script>
								ProbLang.push( "<?php echo $obj['accepted_languages']; ?>" );
								//console.log( ProbLang );
							</script>
							<?php
						}
					?>
					
						</select>
					<?php
					
							echo "
							</td>
						</tr>
						<tr>
							<td>Γλώσσα</td>
							<td>
								<select id='lang' name='lang'>";
									
									?>
									<script>
										var x = document.getElementById("lang");
										for ( var i = 0; i < ProbLang[0].length; ++i ) {
											if ( ProbLang[0][i] == 1 ) {
												var option = document.createElement("option"); 
												option.text = GetLang[ i ];
												option.value = GetLang[ i ];
												try {
												  // for IE earlier than version 8
												  x.add(option,x.options[null]);
												}catch (e) {
												  x.add(option,null);
												}
											}
										}
									</script>
									<?php
									
							echo "
								</select>
							</td>
						</tr>
						<tr>
							<td>Αρχείο</td>
							<td>
								<input type='file' name='file' id='file'>
							</td>
						</tr>
						<tr>
							<td></td>
						";
						
						
						?>
						
									<td><a class='greenbtn' name='submit' href='' onclick="document.forms['codesubmitform'].submit();return false;" >Υποβολή</a></td>
						<?php
						echo "
							</tr>
							</table>
						</form>
						";

				
				}

			}
			
			CloseDatabase();
			
			echo "
				</div>
			</div>
			";
		}else if (  isset( $_GET["id"] ) && isset( $_GET["page"] ) && $_GET["page"] == "viewsource" ) {
			echo "<div class='roundbox'>";
			
			echo"<div class='competitions'>";
			
			OpenDatabase();

			if ( SourceCodeExist( mysql_real_escape_string($_GET['id']) ) == false ) {
				CloseDatabase();
				//echo "redirect";
				?>
				<script>
					window.location = "index.php?error=wrongpage";
				</script>
				<?php
				die();
			}

			if ( isMysubmission( $_GET['id'], $_SESSION['username'] ) == false && !$admin ) {
				CloseDatabase();
				//echo "redirect";
				?>
				<script>
					window.location = "index.php?error=wrongpage";
				</script>
				<?php
				die();
			}

			$str =  GetSourceCode( $_GET['id'] );
			$str = Cplusplus( $str );
			$lineCode = numlines( $str );
			$cnt = 1;
			
			//OpenDatabase();
			echo "<p> <h2>Προβολή κώδικα για το πρόβλημα <a href='index.php?page=problems&id=" .  FindSourceCodeParentOnly($_GET['id']) . "'>" . GetProblemName( FindSourceCodeParentOnly($_GET['id']) ) . "</a></h2></p>";
			//CloseDatabase();
			$he = GetSourceCode( $_GET['id'] );
			
			$id = $_GET['id'];
			$sql = "SELECT parent_id FROM sources WHERE id='$id' ";
			$res = mysql_query( $sql );
			$OB = mysql_fetch_array( $res );
			
			$sql = "SELECT parent_id FROM problems WHERE id=". $OB['parent_id'];
			$res = mysql_query( $sql );
			$OB = mysql_fetch_array( $res );
			
			$sql = "SELECT id, name FROM competitions WHERE id=". $OB['parent_id'];
			$res = mysql_query( $sql );
			$OB = mysql_fetch_array( $res );
			
			echo "<p>";
			echo "<a style='margin-left:10px;' href='index.php?page=competitions&id=" . $OB['id'] ."'>" . "<<Επιστροφή στο '" . $OB['name'] . "'</a>";
			echo "</p>";
			
			echo "<a href='#' onclick='openWin( ); return false;'> Απλό κείμενο </a>";
			
			$Code = explode("\n", $str);
			
			echo "<table class='CodeView' style='width:100%;'>";
			
				//echo "<td style='border:1px solid black;width:10%;'> 1:</td>";
				
				//echo "<td style='margin:0px;padding:0px;width:90%;' valign='top' rowspan='$lineCode'>" . $Code . "</td>";
			
				for ( $i = 0; $i < $lineCode; ++$i ) {
					echo "<tr>";
					echo "<td style='width:2%; background-color:#456; border-right-style: solid; border-right-color: gray;'>" . $i . "   </td>";
					echo "<td style='background-color:white;' > <pre style='margin: 0px 0px 0px 0px; padding: 0px;'>" . $Code[$i] . "</pre></td>";
					echo "</tr>";
				}
				
			echo "</table>";
			
			//echo "<div class='clear'></div>";
			
			//echo "</code>";
			
			CloseDatabase();
			
			echo "
				</div>
			</div>
			";
		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "manage" && $admin ) {
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Διαχείριση Διαγωνισμών </h2>
				</div>
				<div class='competitions'>
					<div style='font-size:18px;margin-top: 10px;margin-bottom: 10px;'>
						<img src='img/clock_add.png' title='Προσθήκη νέου διαγωνισμού'> <a href='index.php?page=aecompetition' style='margin-right: 10px;' href=''> Προσθήκη νέου διαγωνισμού </a>
						<img src='img/brick_add.png' title='Προσθήκη νέου προβλήματος'> <a style='' href='index.php?page=aeproblem'> Προσθήκη νέου προβλήματος </a>
					</div>
					
				";
				
				OpenDatabase();
				$sql = "SELECT * FROM competitions";
				$res = mysql_query( $sql ) or die( mysql_error() );
				while ( $ob = mysql_fetch_array($res) ) {
					echo "<div><h2 style='display:inline;margin-right:5px;'><a href='index.php?page=competitions&id=" . $ob['id'] . "'>" . $ob['name'] . "</a></h2>";
					if ( $ob['visible'] == 0 ) {
						echo "<a href='contestvisible.php?id=" . $ob['id'] . "'><img src='img/eye.png' style='margin-right:5px;' alt='Ορατός' title='Ορατός'></a>";
					}else {
						echo "<a href='contestvisible.php?id=" . $ob['id'] . "'><img src='img/noteye.png' style='margin-right:5px;' alt='Ορατός' title='Ορατός'></a>";
					}
					echo "<a href='index.php?page=compaccess&id=" . $ob['id'] . "'><img src='img/access.png' style='margin-right:5px;' alt='Πρόσβαση' title='Πρόσβαση'></a>";
					echo "<a href='index.php?page=aecompetition&id=" . $ob['id'] . "'><img src='img/b_edit.png' style='margin-right:5px;' alt='Επεξεργασία' title='Επεξεργασία'></a>";
					echo "<a href='index.php?page=compstats&id=" . $ob['id'] . "'><img width='16' height='16' src='img/stats.png' alt='Στατιστικά' title='Στατιστικά'></a>";?>

					<a href='#' onclick="DeleteContest('<?php echo $ob['name']; ?>' , '<?php echo $ob['id']; ?>')"> <img src='img/b_drop.png'  alt='Διαγραφή' title='Διαγραφή'> </a>

					<?php
				
					echo "</div>";
					
					$SQL = "SELECT * FROM problems WHERE parent_id =". $ob['id'];
					$RES = mysql_query( $SQL ) or die( mysql_error() );
					
					echo "<table class='compbox'>";
					
						echo "<tr class='comphead'>";
						echo "<th width='30'>id</th>";
						echo "<th width='60'>Αρχεία</th>";
						echo "<th width='60'>Λύσεις</th>";
						echo "<th width='70'>Όνομα</th>";
						echo "<th>Τίτλος</th>";
						echo "<th width='60'></th>";	
						echo "</tr>";
					
						$cnt = 0;
					
						while ( $OB = mysql_fetch_array($RES) ) {
						
							if ( $cnt == 0 ) {
								echo "<tr class ='compeven'>";
							}else {
								echo "<tr class='compodd'>";
							}
							
							$cnt = 1 - $cnt;
							
							echo "<td> <a href='index.php?page=problems&id=" . $OB['id'] . "'>" . $OB['id'] . "</a></td>";
							
							$sq = "SELECT * FROM problemtest WHERE problem_id=" . $OB['id'] . " AND points = 0 ";
							$zero = mysql_num_rows( mysql_query( $sq ) );
							$sq = "SELECT * FROM problemtest WHERE problem_id=" . $OB['id'] . " AND points > 0 ";
							$not_zero = mysql_num_rows( mysql_query( $sq ) );
							
							echo "<td> <a href='index.php?page=aetests&id=" . $OB['id'] . "'>" . $zero . "/" . $not_zero . "</a></td>";
							echo "<td> <a href='index.php?page=compsubmissions&id=" . $ob['id'] . "'>". "View" . "</a></td>";
							
							echo "<td> <a href='index.php?page=problems&id=" . $OB['id'] . "'>" . $OB['short_name'] . "</a></td>";
							echo "<td> <a href='index.php?page=problems&id=" . $OB['id'] . "'>" . $OB['name'] . "</a></td>";
							echo "<td>" . "<a href='index.php?page=aeproblem&id=" . $OB['id'] . "'><img alt='Επεξεργασία' title='Επεξεργασία' src='img/b_edit.png'></a>";	?>
							<a href='#' onclick="DeleteProblem( '<?php echo $OB['name']; ?>' , '<?php echo $OB['id']; ?>' );"> <img alt='Διαγραφή' title='Διαγραφή' src='img/b_drop.png'> </a>
							<?php echo "</td>";
							
							echo "</tr>";
						}
					echo "</table>";
					
				}
				CloseDatabase();
				
			echo "
				</div>
			</div>
			";

			echo "<div class='roundbox'>
							<div class='roundtitle'>
								<h2>Άλλες ενέργειες </h2>
							</div>
							<div class='competitions'>
								<a href='index.php?page=addarticle'>Προσθήκη νέου άρθρου</a><br>
								<a href='index.php?page=editarticle'>Επεξεργασία άρθρων</a><br>
								<a href='index.php?page=lastloginedusers'>Πρόσφατοι συνδεδεμένοι χρήστες</a>
							</div>
				  </div>";

		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "aecompetition" && $admin ) {
		
			$fullname = "";
			$duration = "";
			$start = "";
			$finish = "";
			
			if ( isset($_GET['id']) ) {
				OpenDatabase();
				$sql = "SELECT * FROM competitions WHERE id='" . $_GET['id'] . "'";
				$res = mysql_query( $sql );
				$obj = mysql_fetch_array( $res );
				$fullname = $obj['name'];
				$duration = $obj['duration'];
				$start = $obj['start_date'];
				$finish = $obj['finish_date'];
				
				$tmp = explode( " ", $start );
				$Date = explode( "-", $tmp[0] );
				$Time = explode( ":", $tmp[1] );
				$start = $Date[2] . "/" . $Date[1] . "/" . $Date[0];
				$start_hour = $Time[0];
				$start_min = $Time[1];
				
				$tmp = explode( " ", $finish );
				$Date = explode( "-", $tmp[0] );
				$Time = explode( ":", $tmp[1] );
				$finish = $Date[2] . "/" . $Date[1] . "/" . $Date[0];
				$finish_hour = $Time[0];
				$finish_min = $Time[1];
				
				CloseDatabase();
			}
			
			$sid = '';
			
			if ( isset( $_GET['id'] ) ) {
				$sid = $_GET['id'];
			}
		
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Δημιουργία/Επεξεργασία διαγωνισμού</h2>
				</div>
				<div class='competitions'>
				
					<form id='addcomp' class='addcomp' action='addcompetition.php' method='post' name='addcomp'>
						<table class='compcategory'>
							
							<tr>
								<td>
									<input type='hidden' name='sid' value='". $sid . "'>
								</td>
							</tr>
							
							<tr>
								<td class='complabels'> <label class='comptext'> Πλήρες όνομα: </label> </td>
								<td class='compfields'> <input class='compfield' name='contest_name' value='$fullname' type='text'> </td>
							</tr>
							<tr>
								<td class='complabels'> <label class='comptext'> Διάρκεια: </label> </td>
								<td class='compfields'> <input class='compfield' name='contest_duration' value='$duration' type='text'> </td>
							</tr>
							<tr>
								<td class='complabels'> <label class='comptext'> Έναρξη: </label> </td>
								<td class='compfields'>
									 <input class='compfield2' id='dstart' name='dstart' type='text' value='$start' onfocus='this.blur();'>
									 <img id='calendar1' src='img/calendar2.gif' alt='calender' title='calender' style='cursor:pointer; cursor:hand;'>";
							
							echo "<select name='shour'>";
							
								for ( $i = 0; $i <= 23; ++$i ) {
								
									$sel = "";
								
									if ( $i == $start_hour ) {
										$sel = "selected";
									}
								
									echo "<option $sel value='$i'>";
									if ( $i < 10 )
										echo "0" . $i;
									else
										echo $i;
									echo "</option>";
								}
							
							echo "</select>";
							
							echo "<select name='smin'>";
							
								for ( $i = 0; $i <= 59; ++$i ) {
								
									$sel = "";
								
									if ( $i == $start_min ) {
										$sel = "selected";
									}
								
									echo "<option $sel value='$i'>";
									if ( $i < 10 )
										echo "0" . $i;
									else
										echo $i;
									echo "</option>";
								}
							
							echo "</select>";
							
							echo "
								</td>
							</tr>
							<tr>
								<td class='complabels'> <label class='comptext'>Λήξη:</label> </td>
								<td class='compfields'>
									<input class='compfield2' id='dfinish' name='dfinish' type='text' value='$finish' onfocus='this.blur();'>
									<img id='calendar2' src='img/calendar2.gif' alt='calender' title='calender' style='cursor:pointer; cursor:hand;'>";
									
							echo "<select name='fhour'>";
							
								for ( $i = 0; $i <= 23; ++$i ) {
								
									$sel = "";
								
									if ( $i == $finish_hour ) {
										$sel = "selected";
									}
								
									echo "<option $sel value='$i'>";
									if ( $i < 10 )
										echo "0" . $i;
									else
										echo $i;
									echo "</option>";
								}
							
							echo "</select>";
							
							echo "<select name='fmin'>";
							
								for ( $i = 0; $i <= 59; ++$i ) {
								
									$sel = "";
								
									if ( $i == $finish_min ) {
										$sel = "selected";
									}
								
									echo "<option $sel value='$i'>";
									if ( $i < 10 )
										echo "0" . $i;
									else
										echo $i;
									echo "</option>";
								}
							
							echo "</select>";
									
							echo "
								</td>
							</tr>
						</table>
						
						<div style='margin-top: 15px;' align='center'>"; ?>
						
							<a id='actionconfirmed' class='greenbtn' href='#' onclick="document.forms['addcomp'].submit();return false;"> Υποβολή </a>
							
						<?php
						echo "
						</div>
						
					</div>
				</div>
				";
			
			echo "</form>";
			
			echo "
			<script type='text/javascript'>
				Calendar.setup({weekNumbers : false,inputField : 'dstart',ifFormat : '%d/%m/%Y', button : 'calendar1',
				align : 'Bl', singleClick : true});
				Calendar.setup({weekNumbers : false,inputField : 'dfinish',ifFormat : '%d/%m/%Y', button : 'calendar2',
				align : 'Bl', singleClick : true});
			</script>
			";
			
		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "aeproblem" && $admin ) {
		
			$fullname = "";
			$shortname = "";
			$creator = "";
			$source = "";
			$intro = "";
			$input_info = "";
			$output_info = "";
			$sample_input = "";
			$sample_output = "";
			$explanation_output = "";
			$sample_input2 = "";
			$sample_output2 = "";
			$explanation_output2 = "";
			$sample_input3 = "";
			$sample_output3 = "";
			$explanation_output3 = "";
			$time_limit = "";
			$mb_limit = "";
			$limits = "";
			$id = "";
			$tests = 1;

			$accepted_languages = "";
			
			OpenDatabase();
			$total_lang = TotalLanguages();
			CloseDatabase();
			
			for ( $i = 0; $i <= $total_lang; ++$i ) {
				$accepted_languages .= "0";
			}

			if ( isset($_GET['id']) ) {
				OpenDatabase();
				$sql = "SELECT * FROM problems WHERE id='" . $_GET['id'] . "'";
				$res = mysql_query( $sql );
				$obj = mysql_fetch_array( $res );
				$pid = $obj['parent_id'];
				$fullname = $obj['name'];
				$shortname = $obj['short_name'];
				$creator = $obj['creator'];
				$source = $obj['source'];
				
				$time_limit = $obj['time_limit'];
				$mb_limit = $obj['mb_limit'];
				$limits = $obj['limits'];
				
				$intro = $obj['intro'];
				$input_info = $obj['input_info'];
				$output_info = $obj['output_info'];
				
				$sample_input = $obj['sample_input'];
				$sample_output = $obj['sample_output'];
				$explanation_output = $obj['explanation_output'];
				
				$sample_input2 = $obj['sample_input2'];
				$sample_output2 = $obj['sample_output2'];
				$explanation_output2 = $obj['explanation_output2'];
				
				$sample_input3 = $obj['sample_input3'];
				$sample_output3 = $obj['sample_output3'];
				$explanation_output3 = $obj['explanation_output3'];
			
				$accepted_languages = $obj['accepted_languages'];
				
				$tests = $obj['tests'];
				CloseDatabase();
			}
		
			$sid = '';
		
			if ( isset( $_GET['id'] ) ) {
				$sid = $_GET['id'];
			}
		
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Δημιουργία/Επεξεργασία προβλήματος</h2>
				</div>
				<div class='competitions'>
				
					<form id='addprob' class='addcomp' action='addprob.php' method='post' name='addcomp'>
						<table class='compcategory'>
							
							<tr>
								<td>
									<input type='hidden' name='sid' value='". $sid . "'>
								</td>
							</tr>
							
							<tr>
								<td class='complabels'> <label class='comptext'> Πλήρες όνομα: </label> </td>
								<td class='compfields'> <input class='compfield' name='prob_name' value='$fullname' type='text'> </td>
							</tr>
							<tr>
								<td class='complabels'> <label class='comptext'> Μικρό όνομα: </label> </td>
								<td class='compfields'> <input class='compfield' name='short_name' value='$shortname' type='text'> </td>
							</tr>
							<tr>
								<td class='complabels'> <label class='comptext'> Διαγωνισμός: </label> </td>
								<td class='compfields'>";

								echo "<select id='contest' name='contest'>";
								OpenDatabase();
								$sq = "SELECT * FROM competitions";
								$re = mysql_query( $sq );
								while ( $ot = mysql_fetch_array($re) ) {
									$sel = "";
									if ( $pid == $ot['id'] ) {
										$sel = "selected";
									}
									echo "<option $sel value='" . $ot['id'] . "'>" . $ot['name'] . "</option>";
								}
								CloseDatabase();
								echo "</select>";
							echo "
								</td>
							</tr>
							
							<tr>
								<td class='complabels'> <label class='comptext'> Δημιουργός: </label> </td>
								<td class='compfields'> <input class='compfield' name='creator' value='$creator' type='text'> </td>
							</tr>
							
							<tr>
								<td class='complabels'> <label class='comptext'> Πηγή: </label> </td>
								<td class='compfields'> <input class='compfield' name='source' value='$source' type='text'> </td>
							</tr>
							
							<tr>
								<td class='complabels'> <label class='comptext'> Χρόνος εκτέλεσης(sec): </label> </td>
								<td class='compfields'> <input class='compfield' name='time_limit' value='$time_limit' type='text'> </td>
							</tr>
							
							<tr>
								<td class='complabels'> <label class='comptext'> Διαθέσιμη μνήμη(MB): </label> </td>
								<td class='compfields'> <input class='compfield' name='mb_limit' value='$mb_limit' type='text'> </td>
							</tr>
							
						</table>
						
						<table class='compcategory'>
							<tr>
								<td class='complabels'>
									<label class='probtext'> Δεκτές γλώσσες: </label>
									";
						OpenDatabase();
						$GetLan = GetLanguages();
						CloseDatabase();

						$languages_length = count( $GetLan );

						$cnt = 0;

						foreach( $GetLan as $tmp ) {
						
							$checked = "";
							if ( $cnt <= $languages_length && $accepted_languages[$cnt] == 1 ) {
								$checked = "checked";
							}
						
							?>
							<br>
							<input type='checkbox' name='check_list[]' value='<?php echo $tmp['name']; ?>' <?php echo $checked; ?> >
							<?php
							echo $tmp['name'];
							++$cnt;
						}

						$test1 = $test2 = $test3 = "";
			
						if ( $tests == 3 ) {
							$test3 = "selected";
						}else if ( $tests == 2 ) {
							$test2 = "selected";
						}else {
							$test1 = "selected";
						}

						echo
								"</td>
							</tr>
						</table>
						
						<table class='compcategory'>
							
							<tr>
								<td class='complabels'>
									<label class='probtext'> Εκφώνηση: </label>
									<br>
									<textarea class='probarea' name='intro'>$intro</textarea>
									<br>
									<label class='probtext'> Όρια: </label>
									<br>
									<textarea class='probarea' name='requirements'>$limits</textarea>
									<br>
									<label class='probtext'> Μορφή εισόδου: </label>
									<br>
									<textarea class='probarea' name='inputdet'>$input_info</textarea>
									<br>
									<label class='probtext'> Μορφή εξόδου: </label>
									<br>
									<textarea class='probarea' name='outputdet'>$output_info</textarea>
									<br>
									
									Παραδείγματα εισόδου/εξόδου:
									<select name='samplecnt' id='samplecnt' onchange='changeSampleInput();'>
										<option $test1 value='1'>1</option>
										<option $test2 value='2'>2</option>
										<option $test3 value='3'>3</option>
									</select>
									
									<div id='sampleio1' style='display:block;'>
										<label class='probtext'> Παράδειγμα εισόδου 1: </label>
										<br>
										<textarea class='probarea' name='sinput1'>$sample_input</textarea>
										<br>
										<label class='probtext'> Παράδειγμα εξόδου 1: </label>
										<br>
										<textarea class='probarea' name='soutput1'>$sample_output</textarea>
										<br>
										<label class='probtext'> Επεξήγηση παραδείγματος: </label>
										<br>
										<textarea class='probarea' name='sexplanation1'>$explanation_output</textarea>
										<br>
									</div>";
									
									if ( $tests >= 2 ) {
										echo "<div id='sampleio2'>";
									}else {
										echo "<div id='sampleio2' style='display:none;'>";
									}
									
									echo "
										<label class='probtext'> Παράδειγμα εισόδου 2: </label>
										<br>
										<textarea class='probarea' name='sinput2'>$sample_input2</textarea>
										<br>
										<label class='probtext'> Παράδειγμα εξόδου 2: </label>
										<br>
										<textarea class='probarea' name='soutput2'>$sample_output2</textarea>
										<br>
										<label class='probtext'> Επεξήγηση παραδείγματος: </label>
										<br>
										<textarea class='probarea' name='sexplanation2'>$explanation_output2</textarea>
										<br>
									</div>";
									
									if ( $tests >= "3" ) {
										echo "<div id='sampleio3'>";
									}else {
										echo "<div id='sampleio3' style='display:none;'>";
									}
									
									echo "
										<label class='probtext'> Παράδειγμα εισόδου 3: </label>
										<br>
										<textarea class='probarea' name='sinput3'>$sample_input3</textarea>
										<br>
										<label class='probtext'> Παράδειγμα εξόδου 3: </label>
										<br>
										<textarea class='probarea' name='soutput3'>$sample_output3</textarea>
										<br>
										<label class='probtext'> Επεξήγηση παραδείγματος: </label>
										<br>
										<textarea class='probarea' name='sexplanation3'>$explanation_output3</textarea>
										<br>
									</div>
									
								</td>
							</tr>
							
						</table>
						
						<div style='margin-top: 15px;' align='center'>"; ?>
						
							<a id='actionconfirmed' class='greenbtn' href='#' onclick="document.forms['addprob'].submit();return false;"> Υποβολή </a>
							
						<?php
						echo "
						</div>
						
					</div>
				</div>
				";
			
			echo "</form>";
			
		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "compaccess" && $admin ) {
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Διαχείριση Πρόσβασης</h2>
				</div>
				<div class='competitions'>
					<form id='accesssubmitform' name='accesssubmitform' action='updatecompaccess.php' method='post'>
						<table>
						<tr>
							<td><b>Να επιτρέπεται η πρόσβαση:</b> </td>
							<td> Σε όλους";
						
						OpenDatabase();
						$query = "SELECT access FROM competitions WHERE id=" . $_GET['id'];
						$result = mysql_query( $query );

						$OBJ = mysql_fetch_array( $result );
						CloseDatabase();
						
						if ( $OBJ['access'] == 0 ) {
							$first = "checked='checked'";
							$second = "";//checked=checked";////echo "Everyone has access";
							$third = "none";
						}else {
							//echo "Few";
							$first = "";//checked=checked";
							$second = "checked='checked'";
							$third = "";
						}
						
						?>
							<input type="radio" onclick="document.getElementById('compusers').style.display='none';" value="all" <?php echo $first; ?> name="accessby" id="accessall">
						<?php
							echo "
							</td>
							<td> Μόνος στους παρακάτω ";
						?>
						
						<input type="radio" onclick="document.getElementById('compusers').style.display='';" value="few" <?php echo $second; ?> name="accessby" id="accessfew">
						
						<input type="hidden" value=" <?php echo $_GET['id']; ?> " name='get_contest_id' >
						
						<?php
						echo "
						</td>
						</tr>
						</table>
						<table id='compusers' style='width:100%;display:$third;'>
			";
			
			OpenDatabase();
			$query = "SELECT username FROM users";
			$result = mysql_query( $query );
			
			$cnt = 0;
			$open = false;
			$closed = false;
			
			while ( $obj = mysql_fetch_array( $result ) ) {
				
				if ( $cnt == 0 ) {
					echo "<tr>";
					$open = true;
					$closed = false;
				}
				
				echo "<td style='width:50%;'>";
				//echo "<input type='checkbox' id='user" . $obj['id'] . "'";
				//echo "value = ". $obj['id'];
				//echo " name='user" . $obj['id'] . "' >";
				
				$checked = "";
				
				if ( $OBJ['access'] == 1 ) {
				    $query1 = "SELECT * FROM competition_access WHERE user_id='". $obj['username'] . "' AND competition_id='" . $_GET['id'] . "'";
				    $result1 = mysql_query( $query1 );
				    if ( mysql_num_rows( $result1 ) == 1 ) {
						$checked = "checked";
				    }
				}
				
				echo "<input type='checkbox' $checked id='user[]' name='user[]' value='". $obj['username'] . "'>";
				
				echo $obj['username'];
				
				echo "</td>";
				
				if ( $cnt == 1 ) {
					echo "</tr>";
					$open = false;
					$closed = true;
				}
				
				$cnt = 1 - $cnt;
				
			}
			
			if ( $open == true && $closed == false ) {
				echo "<td></td></tr>";
			}
			
			CloseDatabase();
			
			echo "			
						</table>
			";
			?>
					
					<div align="center" style="margin-top: 15px; padding-top: 15px; border-top-style: dotted;">	
						<a class='greenbtn' id='accesssubmit' name='accesssubmit' href='' onclick="document.forms['accesssubmitform'].submit();return false;" >Υποβολή</a>
					</div>
			<?php
			echo "			
					
					</form>
					
				</div>
			</div>
			";
		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "aetests" && $admin ) {
			
			OpenDatabase();
			$gpn = GetProblemName( $_GET['id'] );
			CloseDatabase();
			
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>$gpn - Αρχεία ελέγχου</h2>
				</div>
				<div class='competitions'>
					<h2> Αρχεία Ελέγχου </h2>
					";
					?>
					
					<?php
						
						if ( isset( $_GET['testid'] ) && $_GET['testmode'] ) {
							OpenDatabase();
							UpdateTestSelection( $_GET['testid'], $_GET['testmode'] - 1 );
							CloseDatabase();
						}
						
						OpenDatabase();
						$Gpt = GetProblemTest( $_GET['id'] );
						CloseDatabase();
					
						$cnt = 0;
						$total_points = 0;
					
						foreach( $Gpt as $obj ) {
							++$cnt;
							$total_points += $obj['points'];
							
							$orange = "";
							$yellow = "";
							$green = "";
							$purple = "";
							
							if ( $obj['test_selection'] == '0' ) {
								$orange = "testmodesel";
							}else if ( $obj['test_selection'] == '1' ) {
								$yellow = "testmodesel";
							}else if ( $obj['test_selection'] == '2' ) {
								$green = "testmodesel";
							}else if ( $obj['test_selection'] == '3' ) {
								$purple = "testmodesel";
							}
							
							?>
							
							<div style="border: 1px solid transparent;">
								<a href='index.php?probid=<?php echo $_GET['id']; ?>&test=<?php echo $obj['id']; ?>'> <?php echo $cnt; ?> αρχείο ελέγχου </a>
								<div style='display: inline; margin-left: 50px;'>
									<img alt='orange' onclick="window.location='index.php?page=aetests&amp;id=<?php echo $_GET['id']; ?>&amp;testid=<?php echo $obj['id']; ?>&amp;testmode=1'" class="testmode <?php echo $orange; ?>" src="img/tag_orange.png">
									<img alt='yellow' onclick="window.location='index.php?page=aetests&amp;id=<?php echo $_GET['id']; ?>&amp;testid=<?php echo $obj['id']; ?>&amp;testmode=2'" class="testmode <?php echo $yellow; ?>" src="img/tag_yellow.png">
									<img alt='green' onclick="window.location='index.php?page=aetests&amp;id=<?php echo $_GET['id']; ?>&amp;testid=<?php echo $obj['id']; ?>&amp;testmode=3'" class="testmode <?php echo $green; ?>" src="img/tag_green.png">
									<img alt='purple' onclick="window.location='index.php?page=aetests&amp;id=<?php echo $_GET['id']; ?>&amp;testid=<?php echo $obj['id']; ?>&amp;testmode=4'" class="testmode <?php echo $purple; ?>" src="img/tag_purple.png">
								</div>
								<div style='display: inline; margin-left: 50px;'>
									<a onclick='showChangePoints(<?php echo $obj['id']; ?>,<?php echo $cnt; ?>,<?php echo $obj['points']; ?>);' href='#'> <?php echo $obj['points']; ?> βαθμοί </a>
								</div>
							</div>
							<?php
						}
					
					
					echo "
					<hr>
					
					Συνολική βαθμολογία προβλήματος: <b> $total_points </b> βαθμοί
					";
					?>
					
					<br>
					<img alt='orange' src="img/tag_orange.png"> = Οι υποβολές θα ελεγχθούν με το συγκεκριμένο αρχείο ελέγχου προκειμένου να γίνουν δεκτές.
					<br>
					<img alt='yellow' src="img/tag_yellow.png"> = Οι υποβολές θα ελεγχθούν με το συγκεκριμένο αρχείο ελέγχου προκειμένου να γίνουν δεκτές και οι χρήστες θα μπορουν να δουν το συγκεκριμένο αρχειο ελέγχου.
					<br>
					<img alt='green' src="img/tag_green.png"> = Το αρχείο ελένγχου θα χρησιμοποιηθεί μόνο στην τελική αξιολόγηση.
					<br>
					<img alt='purple' src="img/tag_purple.png"> = Το αρχείο ελέγχου δεν θα χρησιμοποιηθεί για την αξιολόγηση.
					
					<?php
					echo "
					<h2> <a href='addnewtest.php?problemid=" . $_GET['id'] ."' > Εισαγωγή νέου αρχείου ελέγχου </a> </h2>
					";
					?>

					<?php
						/*
						<form id="newtest" enctype="multipart/form-data" method="post" name="newtest" action="testsubmit.php?probid=<?php echo $_GET['id']; ?>" >
						<?php
						echo "
							Αρχείο εισόδου:
							<input type='file' name='infile'>
							<br>
							Αρχείο εξόδου:
							<input type='file' name='outfile'>
							<br>";
							?>
							<a class='greenbtn' onclick="document.forms['newtest'].submit();return false;" href=''> Υποβολή </a>
							<?php
						echo "
						</form>";
						*/
					?>

					<?php

					echo
					"
				</div>
			</div>
			";
		}else if ( !isset($_GET["page"]) && isset( $_GET["probid"] ) && isset($_GET["test"]) && isset($_GET['test_id']) && $admin ) {
			
			OpenDatabase();
			$gpn = GetProblemName( $_GET['probid'] );
			$fftn = FindFileTestFileName( $_GET['test_id']  );
			CloseDatabase();
			
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>$gpn - " . $_GET['test'] . "# Αρχείο ελέγχου - Επεξεργασία περιεχομένου αρχείου</h2>
				</div>";
				?>
				<div class='competitions'>
					<a href="index.php?probid=<?php echo $_GET['probid']; ?>&test=<?php echo $_GET['test']; ?>"> << Επιστροφή </a>
					<h2> Αρχεία εισόδου ( <?php echo $fftn; ?> ) </h2>
					
					<form id="editfiletest" enctype="multipart/form-data" method="post" name="newtest" action="testinout.php?id=<?php echo $_GET['test_id']; ?>" >
						<input type='file' name='infile'>
						<a class='greenbtn' onclick="document.forms['editfiletest'].submit();return false;" href=''> Υποβολή </a>
					</form>
					
					<hr>
					
					<p id='in1'>Εμφάνιση</p>
					<code>
						<?php
							OpenDatabase();
							$ti = GetTestFileContent( $_GET['test_id']  );
							if ( $ti == false ) {
								echo "<i>Δεν έχετε καθορίσει το περιεχόμενο του αρχείου</i>.";
							}else {
								echo $ti;
							}
							CloseDatabase();
						?>
					</code>
					
						<script>
							$("#in1").click(function() {                     
							   $("#in1").next().toggle();
							   if ( $("#in1").text()[0] == "Ε" ) {
							       $("#in1").text("Απόκρυψη");
							   }else {
								   $("#in1").text("Εμφάνιση");
							   }
							});
						</script>
						
						<script>
							$("#in1").next().hide();
						</script>
					
					</div>
				</div>
				<?php
		}else if ( !isset($_GET["page"]) && isset( $_GET["probid"] ) && isset($_GET["test"]) && $admin ) {
			
			OpenDatabase();
			$gpn = GetProblemName( $_GET['probid'] );
			CloseDatabase();
			
			echo "<div class='roundbox'>
				<div class='roundtitle'>

					<h2>$gpn - " . $_GET['test'] . "# Αρχείο ελέγχου</h2>
				</div>";
				?>
				<div class='competitions'>
					
				<a href="index.php?page=aetests&id=<?php echo $_GET['probid']; ?>"> <<Επιστροφή στην σελίδα των αρχείων ελέγχου</a>
					<h2> Αρχεία εισόδου ( standard input ) </h2>
					<form id="newtest" enctype="multipart/form-data" method="post" name="newIntest" action="testsubmit.php?edit=1&probid=<?php echo $_GET['probid']; ?>&testid=<?php echo $_GET["test"]; ?>" >
						<input type='file' name='infile'>
						<a class='greenbtn' onclick="document.forms['newIntest'].submit();return false;" href=''> Υποβολή </a>
					</form>
					
					<h2> Αρχεία εξόδου ( standard output ) </h2>
					<form id="newtest" enctype="multipart/form-data" method="post" name="newOuttest" action="testsubmit.php?edit=2&probid=<?php echo $_GET['probid']; ?>&testid=<?php echo $_GET['test']; ?>" >
						<input type='file' name='infile'>
						<a class='greenbtn' onclick="document.forms['newOuttest'].submit();return false;" href=''> Υποβολή </a>
				    </form>
					
					<h2>Προβολή αρχείου εισόδου ( standard input ) </h2>
					<p id='in1'>Εμφάνιση</p>
					<code>
						<?php
							OpenDatabase();
							$ti = TestInput( $_GET['test']  );
							if ( $ti == false ) {
								echo "Δεν υπάρχει αρχείο εισόδου";
							}else {
								echo $ti;
							}
							CloseDatabase();
						?>
					</code>
					
						<script>
							$("#in1").click(function() {                     
							   $("#in1").next().toggle();
							   if ( $("#in1").text()[0] == "Ε" ) {
							       $("#in1").text("Απόκρυψη");
							   }else {
								   $("#in1").text("Εμφάνιση");
							   }
							});
						</script>
					
					<h2>Προβολή αρχείου εξόδου ( standard output ) </h2>
					
						<p id='in2'>Εμφάνιση</p>
						<code>
							<?php
								OpenDatabase();
								$to = TestOutput( $_GET['test']  );
								if ( $to == false ) {
									echo "Δεν υπάρχει αρχείο εξόδου";
								}else {
									echo $to;
								}
								CloseDatabase();
							?>
						</code>
						
						<script>
							$("#in1").next().hide();
							$("#in2").next().hide();
						</script>
						
						<script>
							$("#in2").click(function() {                     
							   $("#in2").next().toggle();
							   if ( $("#in2").text()[0] == "Ε" ) {
							       $("#in2").text("Απόκρυψη");
							   }else {
								   $("#in2").text("Εμφάνιση");
							   }
							});
						</script>
						
						<hr>
						
						<h2>Προσθήκη νέου αρχείου εισόδου </h2>
						
						<form id="newtestfile" enctype="multipart/form-data" method="post" name="newOuttestFile" action="testsubmit.php?edit=3&probid=<?php echo $_GET['probid']; ?>&testid=<?php echo $_GET['test']; ?>" >
							<b> όνομα αρχείου </b><input name='filenameforinput' type='text'>
							<br>
							<input type='file' name='infile'>
							<a class='greenbtn' onclick="document.forms['newOuttestFile'].submit();return false;" href=''> Υποβολή </a>
						</form>
					
						<hr>
						
						<?php
							OpenDatabase();
							$gafit = GetAllFileInputTest( $_GET['test'] );
							if ( $gafit ) {
								
								echo "<h2> Αρχεία εισόδου </h2>";
								
								echo "<table class='compbox'>";
					
								echo "<tr class='comphead'>";
								echo "<th width='70px'>Όνομα</th>";
								echo "<th width='50px'>Επεξεργασία ονόματος</th>";
								echo "<th width='50px'>Επεξεργασία περιεχομένου</th>";
								echo "<th width='10px'></th>";
								echo "</tr>";
							
								$cnt = 0;
								
								foreach( $gafit as $tmp ) {
								
									if ( $cnt == 0 ) {
										echo "<tr class ='compeven'>";
									}else {
										echo "<tr class='compodd'>";
									}
									
									$cnt = 1 - $cnt;
								
									echo "<td style='max-width:70px;overflow:hidden;' >" . $tmp['filename'] . "</td>";
									echo "<td>";?>
										      <a href='#' onclick='showChangeNameFile( "<?php echo $tmp['id']; ?>", "<?php echo $tmp['filename']; ?>", "<?php echo $tmp['filename']; ?>" );'>
											      <img src='img/b_edit.png' title='Επεξεργασία' alt='Επεξεργασία'>
											  </a>
											  </td>
											  
									<td>
										<a href="index.php?probid=<?php echo $_GET['probid']; ?>&test=<?php echo $_GET['test']; ?>&test_id=<?php echo $tmp['id']; ?>">
											<img src='img/b_edit.png' title='Επεξεργασία' alt='Επεξεργασία'>
										</a>
									</td>
											  
											  <td>
											  <a href='' onclick='deleteFileTest(". $tmp['id'] .");'> <img src='img/b_drop.png' title='Διαγραφή' alt='Διαγραφή'> </a>
										  </td>
									<?php
									echo "</tr>";
								}
								
								echo "</table>";
								
							}
							CloseDatabase();
						?>
						
				   </div>
				</div>
			<?php
		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "compsubmissions" && $admin ) {
			echo "<div class='roundbox'>
				<div class='roundtitle'>";
				
			OpenDatabase();
			$name = FindContestName( $_GET['id'] );
			CloseDatabase();
				
			echo"<h2>$name - Υποβολές</h2>
				</div>
				<div class='competitions'>";
				
				//
				OpenDatabase();
				
				$res = "";

				/*
				if ( !isContestLimited( $_GET['id'] ) ) {
					$res = mysql_query("SELECT * FROM users");
				}else {
					$c = mysql_real_escape_string( $_GET['id'] );
					$res = mysql_query( 
						"SELECT
							users.username, users.fullname
						FROM
							users, competition_access
						WHERE
							users.username = competition_access.user_id
							AND competition_access.competition_id = '$c'" );
				}*/

				$ret = array();
				
				$how_many = 0;
				
				$usoc = UsersSubmitedOnContest( $_GET['id'] );			

				if ( $usoc != false ) {
				
					foreach( $usoc as $tmp ) {//while ( $tmp = mysql_fetch_array($res) ) {
						$tmp['username'] = $tmp['user_id'];
						$fapsfu = FindAllProblemsSubmissionsForUser( $_GET['id'], $tmp['username'] );
					
						$fapsfu['username'] = $tmp['username'];
						$fapsfu['fullname'] = FindFullName( $fapsfu['username'] );
						//$fapsfu['fullname'] = $tmp['fullname'];
					
						if ( $fapsfu != false ) {
							$ret[] = $fapsfu;
							$how_many += 1;
						}
					
					}
				
				}

				//$count_prob = 0;

				$FCP = FindContestProblems( $_GET['id'] );

				//print_r( $FCP );

				$count_prob = count( $FCP );

				//if ( count( $ret ) > 0 ) {
					//echo sizeof( $ret[0] );
					//$count_prob = max( 1, sizeof($ret[0]) - 2 );
				//}

				$cnt = 0;
				
				//echo $how_many . " " . $count_prob . " ";
				
				for ( $j = 0; $j < $count_prob; ++$j ) {
				
					++$cnt;
					//echo "<a href='index.php?page=problems&id=" . $ret[0][$j]['id'] ."'>" . " <h2>Πρόβλημα " . $cnt . " (" . $ret[0][$j]['name'] . ") </h2> </a>";
				
					echo "<a href='index.php?page=problems&id=" . $FCP[$j]['id'] ."'>" . " <h2>Πρόβλημα " . $cnt . " (" . $FCP[$j]['name'] . ") </h2> </a>";


					if ( $how_many == 0 ) {

						echo "
								<div class='submissionbox'>
									<div align='center'>Δεν έχουν γίνει υποβολές για το συγκεκριμένο πρόβλημα.</div>
								</div>
								<hr>
								<br>
						";

						continue;

					}

					for ( $i = 0; $i < $how_many; ++$i ) {
					
						//$tmp = $fapsfu
						$fapsfu = $ret[$i];
						
						//echo $tmp['username'];
						
						if ( $fapsfu == false ) {
							echo "Δεν υπάρχουν προβλήματα<hr>";
						}else {
						
							//foreach( $fapsfu as $obj ) {
							$obj = $ret[$i][$j];
							
							//$pie = explode( " ", $ret[$i]['fullname'] );
							
							$surname = "";//$ret[$i]['surname']
							$name  = "";//$ret[$i]['name']
							
							/*
							if ( isset( $pie[0] ) ) {
								$surname = $pie[0];
							}
							
							if ( isset( $pie[1] ) ) {
								$name = $pie[1];
							}*/
							
								echo "
								<div class='submissionbox'>
									
											<b> " . $name . " " . $surname . " (" . $ret[$i]['username'] . ") ". $fapsfu['fullname'] ."</b>";
											
										if ( $obj['last'] == false ){
											echo "<div align='center'>Δεν έχουν γίνει υποβολές</div>";
										}else {
											foreach ( $obj['last'] as $tmp_obj ) {
												
												$sub = "subexamine";
												$sub_what = "Αναμονή";
												$active_word = "";
												$Color = "border: 1px solid #a1b4c6;";
												
												if ( $tmp_obj['examined'] == 1 ) {
													if ( $tmp_obj['passed'] == 1 ) {
														$sub = 'subactive';
														$sub_what = "Επιτυχής";
														if ( $tmp_obj['active'] == 0 ) {
															$active_word = "<a style='color:#2C71B8;' href='activate.php?id=" . $tmp_obj['id'] . "'> Activate </a>";
														}else {
															$active_word = "Active";
															$Color = "background-color: #A1D873;";
														}
													}else {
														$sub = 'subfailed';
														$sub_what = "Απέτυχε";
													}
												}
												
												echo "
												
													<div style='$Color' class='submission " . $sub ."'>
													<div style='float:right;width:100px;'><b> $active_word </b></div>		
													<div style='float:left; width:105px;'><a href='index.php?page=viewsub&id=" . $tmp_obj['id'] . "'>Υποβολή " .
													$tmp_obj['id'] . "</a></div>
													
													<div style='float:left; width: 75px; font-size: 95%;' > " . compiler_language( $tmp_obj['compiler_language'] ) ." </div>
													<div style='float:left; width:200px;'> " . $tmp_obj['datetime'] . "</div>" .
														$sub_what . "
													</div>";
											
											}
										}
										
								echo "
									</div>
									<hr>
									<br>
								";
								
								//++$cnt;
							
							//}
						
						}
					
					}//end of i
				}//end of j
				
				CloseDatabase();
				
				//
				
			echo "</div>
				</div>";

		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "compstats" && $admin ) {
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Στατιστικά</h2>
				</div>
				<div class='competitions'>
					<h2>Συμμετέχοντες με μία τουλάχιστον υποβολή:</h2>
					
					<table class='compbox' style='font-size:1.1em;'>
						<tr class='comphead'>
							<th style='width: 100px;'>Username</th>";

					OpenDatabase();
					$usoc = UsersSubmitedOnContest( $_GET['id'] );
					$fcp = FindContestProblems( $_GET['id'] );

					//print names of problems
					if ( $fcp != false ) {
						$tmp = $fcp;
						foreach( $tmp as $obj ) {
								echo "<th style='width: 55px;'>" . $obj['short_name'] . "</th>";
						}

					}

					echo "</tr>";

					$cnt = 0;

					if ( $usoc != false )
					foreach( $usoc as $tmp ) {
						//print_r( $tmp );
						echo"<tr>";

						if ( $cnt == 0 ) {
							echo "<tr class ='compeven'>";
						}else {
							echo "<tr class='compodd'>";
						}
						
						$cnt = 1 - $cnt;

							echo "<td style='font-size:0.9em; text-align: justify;'>" . FindFullName($tmp['user_id']) . "(" . $tmp['user_id'] . ")</td>";

							$user = $tmp['user_id'];

							$tmp = $fcp;
							foreach( $tmp as $obj ) {
								$has = HasActiveSubmission( $user, $obj['id'] );
								if ( $has == 1 ) {
									echo "<td style='text-align:center;'><img src='s_success_small.png' alt='Ναι'></td>";
								}else {
									echo "<td style='text-align:center;'><img src='cross.png' alt='Όχι'></td>";
								}
							}
							
						echo "</tr>";
					}
					CloseDatabase();

					echo "
						</tr>
					</table>
					
				</div>
			</div>
			";
		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "feedback" && isset( $_GET["test_id"] ) && isset( $_GET["problem"] ) ) {

			OpenDatabase();

			$gtsf = GetTestSelectionFeedBack( $_GET['problem'], $_GET['test_id'] );

			if ( $gtsf == 0 || $gtsf != 1 ) {
				CloseDatabase();

			?>
			
			<script>
				window.location = "index.php?error=wrongpage";
			</script>
			<?php
			}

			$name = GetProblemName( $_GET['problem'] );
			$input = TestInput( $_GET['test_id'] );
			$output = TestOutput( $_GET['test_id'] );
			CloseDatabase();

			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Αποτελέσματα εκτέλεσης για το " . $name ."</h2>
				</div>
				<div class='competitions'>";
				?>
				<p>Είσοδος:</p>
				<pre><?php echo $input; ?></pre>
				<p>Έξοδος:</p>
				<pre><?php echo $output; ?></pre>
				<?php
				echo "
				</div>
			</div>
			";
	}else if ( isset( $_GET["page"] ) && $_GET["page"] == "guidelines" ) {
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Οδηγίες</h2>
				</div>
				<div class='competitions'>";
				?>
					
					<ol style='font: 1em Tahoma, Arial, sans-serif; text-align: justify;'>
					 	<li>Έχετε δικαίωμα πολλαπλών υποβολών μέχρι το τέλος του διαγωνισμού.</li>
						<li>Έλεγχος τιμών δεν απαιτείται. Οι τιμές των αρχείων ελέγχου είναι πάντα έγκυρες.</li>
						<li>
					Οι επιλογές των μεταγλωττιστών που χρησιμοποιούνται για τη βαθμολόγηση είναι οι εξής:
						<br>
						C: gcc -std=c99 -O2 -DUNITRIX -s -static -lm
						<br>
						C++: g++ -O2 -DUNITRIX -s -static -lm
						<br>
						Python 2.7
						<br>
						Java: gcj-4.7
					</li>
					<li>
					 Τα προγράμματά σας πρέπει να επιστρέφουν ως κωδικό εξόδου το μηδέν:
						Η συνάρτηση main() για κώδικες σε C και C++ πρέπει πάντα να τερματίζει με τις εντολές "return(0);" ή "exit(0);".
    				</li>

					<li>
					Το πρόγραμμα αξιολόγησης θα εξετάσει την τιμή που επιστρέφει το πρόγραμμά σας. Εάν η τιμή αυτή δεν είναι μηδέν, τότε το πρόγραμμα δεν θα βαθμολογηθεί για το συγκεκριμένο test.
					</li>

					<li>
					Η σελίδα χωρίζεται σε <strong>διαγωνισμούς</strong>. Μπορείτε να υποβάλετε κώδικα μονάχα στους <strong>διαγωνισμούς</strong> που έχουν την ένδειξη <strong>ενεργός</strong>.
					Όταν υποβάλετε την λύση σας, μπαίνει στην <strong>αναμονή</strong> και έπειτα από λίγο αφού αξιολογηθεί από το σύστημα έχετε την δυνατότητα να δείτε τα αποτελέσματα της λύσης σας. Οι σωστές λύσεις εμφανίζονται με <font style='padding:2px;background-color:#A1D873'>πράσινο</font> χρώμμα, ενώ οι λάθος λύσεις με <font style='padding:2px;background-color:#F7BDBD'><i>κόκκινο</i></font>. Στο τέλος του διαγωνισμού, <strong><i>εάν κριθεί αναγκαίο</i></b></strong> οι υποβολές μπορεί να τρέξουν σε περισσότερα αρχεία ελένχου από αυτά που σας έχουν ήδη γνωστοποιηθεί. Στην προκειμένη περίπτωση εάν έχετε πολλαπλές σωστές λύσεις, βαθμολογείται αυτή που έχει την ένδειξη <strong><i>ενεργοποιημένη</i></strong>.
					</li>

					<li>
					 <b><i>Μην προσπαθήσετε να πειραμματιστείτε με το σύστημα</i></b>. Κάθε απόπειρα κακόβουλης εισόδου ή ακόμα και εξερεύνησης του συστήματος, θα εντοπίζεται και θα επιβάλλονται κυρώσεις.
					</li>
				</ol>

			<?php
				echo "
				</div>
			</div>

			";
		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "contact" ) {
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Επικοινωνία</h2>
				</div>

				<div class='competitions'>";
				?>

 				Για οποιαδήποτε απορία/πρόβλημα μην διστάσετε να επικοινωνήσετε στο <a href="mailto:sotirisnik@gmail.com">sotirisnik@gmail.com</a>.
				<br>
				Εναλλακτικά μπορείτε να συμβουλευτείτε τις <a href='index.php?page=guidelines'>οδηγίες</a>.

			<?php
				echo "
				</div>
			</div>

			";
		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "addarticle" && $admin ) {
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Προσθήκη επεξεργασία άρθρων</h2>
				</div>

				<div class='competitions'>
					<a href='index.php?page=manage'><< Επιστροφή στην σελίδα διαχείρισης</a>";
				?>

				<?php
					$sid = '';
					$title = '';
					$text = '';
					if ( isset( $_GET['id'] ) ) {
						$sid = $_GET['id'];
						OpenDatabase();
						$obj = GetArticle( $sid );
						CloseDatabase();
						if ( $obj != false ) {
							$title = $obj['title'];
							$text = $obj['text'];
						}
					}
				?>

				<form id="editarticle" action="addarticle.php" method="post">
					<table class="compcategory">
						<tr>
							<td class="complabels">
								Τίτλος:<br>
								<input style='width:100%;' id="maintitle" name="maintitle" type="text" value="<?php echo $title; ?>"><br>
							    Κείμενο<br>
								<textarea class='probarea' id="maintext" name="maintext"><?php echo $text; ?></textarea><br>
								<a class='greenbtn' onclick="document.forms['editarticle'].submit();return false;" href=''> Υποβολή </a>
								<input type='hidden' name='sid' value='<?php echo $sid; ?>'>
							
							</td>
						</tr>
					</table>
				</form>

			<?php
				echo "
				</div>
			</div>

			";
		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "lastloginedusers" && $admin ) {
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Πρόσφατοι συνδεδεμένοι χρήστες</h2>
				</div>

				<div class='competitions'>";
				?>

				<a href='index.php?page=manage'><< Επιστροφή στην σελίδα διαχείρισης</a>

				<table style='font-size:1.2em;' class='compbox'>
					<tr class='comphead'>
						<th>Username</th>
						<th>Ονοματεπώνυμο</th>
						<th>Ημερομημία</th>
						</tr>
					<?php
						OpenDatabase();
						$getlastconnected = GetLastConnectedUsers();
						$cnt = 0;
						foreach( $getlastconnected as $tmp ) {

							if ( $cnt == 0 ) {
								echo "<tr class ='compeven'>";
							}else {
								echo "<tr class='compodd'>";
							}
							
							$cnt = 1 - $cnt;

							echo "<td>" . $tmp['username'] . "</td><td>" . $tmp['fullname'] . "</td><td>" . $tmp['date_login'] . "<br>" . $tmp['date'] . "</td></tr>";
						}

						CloseDatabase();
					?>
				</table>

			<?php
				echo "

				</div>

			</div>



			";
		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "news" && isset( $_GET["id"] )  ) {

			OpenDatabase();
			$gnwi = GetNewsWithID( $_GET['id'] );
			CloseDatabase();

			if ( $gnwi == false ) {
				?>
				<script>
					window.location = "index.php?error=wrongpage";
				</script>
				<?php
				die();
			}

			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>". $gnwi['title'] ."</h2>
				</div>

				<div class='competitions'>";
		
				echo "<a href='index.php'><< Επιστροφή στην αρχική σελίδα</a>";

			echo "<p>" . nl2br($gnwi['text']) . "</p>";

				echo "

				</div>

			</div>



			";
		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "subguide"  ) {

			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Παράδειγμα υποβολής</h2>
				</div>

				<div class='competitions'>";
		
				echo "<a href='index.php'><< Επιστροφή στην αρχική σελίδα</a>";

				echo "<p> Στην είσοδο δίνεται ο αριθμός Ν και στην συνέχεια Ν ακέραιοι αριθμοί. Στην έξοδο ζητείται το άθροισμα των αριθμών σε μία γραμμή</p>";

				echo "<h2>Σε C:</h2>";

				OpenDatabase();
				echo PrintCodeView( Cplusplus( "#include <stdio.h>
#include <stdlib.h>

int n, x, sum;

int main( ) {

	scanf( \"%d\", &n );

	for ( int i = 0; i < n; ++i ) {
		scanf( \"%d\", &x );
		sum += x;
	}

	printf( \"%d\\n\", sum );

	return 0;

}" ) );

				CloseDatabase();

				echo "<h2>Σε C++:</h2>";

				OpenDatabase();
				echo PrintCodeView( Cplusplus( "#include <iostream>

using namespace std;

int n, x, sum;

int main( ) {

	cin >> n;

	for ( int i = 0; i < n; ++i ) {
		cin >> x;
		sum += x;
	}

	cout << sum << endl;

	return 0;

}" ) );

				CloseDatabase();

echo "<h2>Σε Python 2.7:<h2>";

				OpenDatabase();
				echo PrintCodeView( Cplusplus( "n = int( raw_input() )

for i in range( 0, n ):
	x = int( raw_input() )
	sum += x

print \"%d\" % ( sum )
" ) );

				CloseDatabase();

echo "<h2>Σε Java:<h2>";

				OpenDatabase();
				echo PrintCodeView( Cplusplus( "import java.util.*;

class Main {

	public static void main( String args[] ) {

		Scanner fin = new Scanner( System.in );
	
		int N = fin.nextInt();

		int x = 0;
		int sum = 0;

		for ( int i = 0; i < N; ++i ) {
			x = fin.nextInt();
			sum += x;
		}

		fin.close();

		System.out.println( sum );

		System.exit( 0 );

	}

}
" ) );

				CloseDatabase();


				echo "


				</div>

			</div>



			";
		}else if ( isset( $_GET["page"] ) && $_GET["page"] == "editarticle" && $admin ) {
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Επεξεργασία Άρθρων </h2>
				</div>
				<div class='competitions'>
					<a href='index.php?page=manage'><< Επιστροφή στην σελίδα διαχείρισης</a>
				";

			OpenDatabase();
			$GA = GetArticles( );
			CloseDatabase();

			if ( $GA == false ) {
				echo "Δεν υπάρχουν άρθρα";
			}else {

				echo "<table class='compbox'>";
					
				echo "<tr class='comphead'>";
				echo "<th width='30'>id</th>";
				echo "<th>Τίτλος</th>";
				echo "</tr>";
			
				$cnt = 0;
			
				foreach( $GA as $tmp ) {
				
					if ( $cnt == 0 ) {
						echo "<tr class ='compeven'>";
					}else {
						echo "<tr class='compodd'>";
					}
					
					$cnt = 1 - $cnt;
					
					echo "<td style='font-size: 85%;'>" . $tmp['id'] . "</td>";
					echo "<td style='font-size: 85%;'> <a href='index.php?page=addarticle&id=" . $tmp['id'] . "'>" . $tmp['title'] . "</a></td>";

				}

				echo "</table>";

			}

			echo "


				</div>

			</div>

			";

		}else {
			echo "<div class='roundbox'>
				<div class='roundtitle'>
					<h2>Νέα</h2>
				</div>
				<div class='competitions'>";
				OpenDatabase();
				$glnews = GetLatestNews();
				CloseDatabase();
					if ( $glnews == false ) {
						echo "Δεν υπάρχουν νέα";
					}else {
						foreach( $glnews as $tmp ) {
							$tmp['date'] = split(" ", $tmp['date'] )[0];
							$tmp['date'] = split("-", $tmp['date'] );
							$month = $tmp['date'][1];
							$day = $tmp['date'][2];
							?>
							<div class="problembox" style='width:96%;height:50px;padding:10px'>
								<div style='width:20%;float:left;'>
									<?php echo $day . " " . Month($month); ?>
									<br style='margin-bottom: 10px;'>
									<a class='greenbtn' href="index.php?page=news&id=<?php echo $tmp['id']; ?>"> Ανάγνωση </a>
								</div>
								<div style='width:80%;float:left;'>
									<h2 style='margin-top:10px;'><a href="index.php?page=news&id=<?php echo $tmp['id']; ?>"> <?php echo $tmp['title']; ?> </a></h2>
								</div>
							</div>
							<div style='clear:both;'></div>
							<hr>
							<?php
						}
					}
			echo "
				</div>
			</div>
			";
		}
		?>
	
</div>

<div id="right">
	<div class="box">
	
		<?php
				
			if ( !isset($_SESSION["username"]) ) {
			
			?>
				<form id='loginform' method='post' action='login.php' onkeypress="handleKey('loginform',event)">
					<p>
						<b>Είσοδος στο σύστημα</b>
					</p>
					<table>
						<tr>
							<td>
								Όνομα χρήστη
								<br>
								<input type='text' name='username'>
								<br>
							</td>
						</tr>
						<tr>
							<td>
								Κωδικός πρόσβασης
								<br>
								<input type='password' name='password'>
								<br>
							</td>
						</tr>
						<tr>
							<td>
								<div class="buttons">
									<a class='bluebtn' name='submitlogin' href='#'  onclick="document.forms['loginform'].submit();return false;" > Είσοδος </a>
								</div>
							</td>
						</tr>
					</table>
				</form>
				<?php
			}else {
			
				echo "
					<table>
						<td align='center' style='width:80px;'>";
						
						if ( $admin ) {
							echo "
								<img alt='admin' title='admin' height='70' src='img/admin.png'>
							";
						}else {
							echo "
								<img alt='user' title='user' height='70' src='img/neutral_gray_user.png'>
							";
						}
				echo "
						</td>
						
						<td>
							<ul>
								<li style='margin:0px;'><b>Καλωσήρθες</b> ".$_SESSION['username'] ." </li>
					";
								
								/*
								$pieces = explode( " ", $_SESSION['fullname'] );
								$name = $surname = "";
								
								if ( isset( $pieces[0] ) ) {
									$name = $pieces[0];
								}
								
								if ( isset( $pieces[1] ) ) {
									$surname = $pieces[1];
								}*/
								
						  echo"
								<li style='margin-top: 7px;'><b>". $_SESSION['fullname'] ."</b></li>";
								//<li>Επίθετο: ". $surname ."</li>
						 echo "
								<li style='margin-top:10px;'> <a class='bluebtn' href='logout.php'>Έξοδος</a> </li>
							</ul>
						</td>
					</table>";
			}
		?>
	</div>
	
	<?php
		if ( isset( $_SESSION["username"] ) ) {
			?>
			<div class='box'>
				<table>
					<td align="center" style='width:80px;'>
						<img height="70" title="competitions" alt="competitions" src="img/prize.png">
					</td>	
					<td>
						<ul>
							<li>
								<b>Ενεργοί Διαγωνισμοί</b>
							</li>
							<li>
								<?php OpenDatabase();?>
								<p id="CountD"></p>
								<?php CloseDatabase(); ?>	
							</li>
						</ul>
					</td>
				</table>
			</div>
			<?php
		}
	?>
	
</div>

<?php
	include "footer.php"
?>
