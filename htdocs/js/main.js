function changeSampleInput( ) {
	
	var curcount = document.getElementById('samplecnt').options[ document.getElementById('samplecnt').selectedIndex ].value;
	
	if ( curcount >= 2 ) {
		document.getElementById('sampleio2').style.display = '';
	}else {
		document.getElementById('sampleio2').style.display = 'none';
	}
	
	if ( curcount >= 3 ) {
		document.getElementById('sampleio3').style.display = '';
	}else {
		document.getElementById('sampleio3').style.display = 'none';
	}
	
}

function changeLangInput( ) {

	var y = document.getElementById('lang2').selectedIndex;
	var x = document.getElementById("lang");
	
	var comboBox = document.getElementById('lang');
	
	while( comboBox.options.length > 0 ) {                
		comboBox.remove(0);
	}
	
	for ( var i = 0; i < ProbLang[ y ].length; ++i ) {
		if ( ProbLang[ y ][i] == 1 ) {
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
	
}

function showChangePoints(id,name,val) {
	document.getElementById('actionheader').innerHTML="Αλλαγή βαθμολογίας";
	document.getElementById('actiontext').innerHTML="Βαθμολογία για το <b>"+name+"ο</b> αρχείο ελέγχου:<br /><br /><input id=\"testpoints\" type=\"text\" value=\"" + val + "\" style=\"width: 50px;\"></input> βαθμοί";
	document.getElementById('actionconfirmed').innerHTML="&nbsp;Εντάξει&nbsp;&nbsp;";
	document.getElementById('actionconfirmed').href = "javascript:window.location='testpoints.php?id=" + id + "&points=' + document.getElementById('testpoints').value;";
	document.getElementById('confirmaction').style.visibility='';
}

function showChangeNameFile(id,name,val) {
	document.getElementById('actionheader').innerHTML="Αλλαγή ονόματος";
	document.getElementById('actiontext').innerHTML="Επεξεργασία αρχείου <b>"+name+"</b>:<br /><br /><b>όνομα αρχείου</b><input id=\"testpoints\" type=\"text\" value=\"" + val + "\" style=\"width: 100px;\"></input>";
	document.getElementById('actionconfirmed').innerHTML="&nbsp;Εντάξει&nbsp;&nbsp;";
	document.getElementById('actionconfirmed').href = "javascript:window.location='testname.php?id=" + id + "&name=' + document.getElementById('testpoints').value;";
	document.getElementById('confirmaction').style.visibility='';
}

function showChangeContentFile(id,name,val) {
	document.getElementById('actionheader').innerHTML="Αλλαγή περιεχομένου";
	document.getElementById('actiontext').innerHTML="Επεξεργασία αρχείου <b>"+name+"</b>:<br /><br /><b><form href='testinout.php' method='get' name='myformsub' enctype='multipart/form-data' >Αναζήτηση αρχείου</b><input id=\"intfile\" type=\"file\" value=\"" + val + "\" style=\"width: 100px;\"></input></form>";
	document.getElementById('actionconfirmed').innerHTML="&nbsp;Εντάξει&nbsp;&nbsp;";
	document.getElementById('actionconfirmed').href = "javascript: myformsub.submit();";
	document.getElementById('confirmaction').style.visibility='';
}

function deleteFileTest( val ) {

}

function DeleteProblem( name, id ) {
	document.getElementById('actionheader').innerHTML="Διαγραφή προβλήματος;";
	document.getElementById('actiontext').innerHTML="Είστε σίγουροι ότι θέλετε να διαγράψετε το πρόβλημα <b>"+name+"</b>;<br/>(Μπορείτε εναλλακτικά να το μετακινήσετε εκτός διαγωνισμού)";
	document.getElementById('actionconfirmed').innerHTML="&nbsp;Εντάξει&nbsp;&nbsp;";
	document.getElementById('actionconfirmed').href = "deleteproblem.php?id="+id;
	document.getElementById('confirmaction').style.visibility='';
}

function DeleteContest( name, id ) {
	document.getElementById('actionheader').innerHTML="Διαγραφή διαγωνισμού;";
	document.getElementById('actiontext').innerHTML="Είστε σίγουροι ότι θέλετε να διαγράψετε το διαγωνισμό <b>"+name+"</b>;<br/>(Μπορείτε εναλλακτικά να τον επεξεργαστείτε)";
	document.getElementById('actionconfirmed').innerHTML="&nbsp;Εντάξει&nbsp;&nbsp;";
	document.getElementById('actionconfirmed').href = "deletecontest.php?id="+id;
	document.getElementById('confirmaction').style.visibility='';
}

function handleKey( id, e ) {

	 var x = document.getElementById( id );

	 if ( window.event ) {
		keynum = e.keyCode;
	 }else if ( e.which ) {
		keynum = e.which;
	 }
	 
	 if ( keynum == 13 ) {
		x.submit();
	 }
	 
	 return ( true );
	 
}
