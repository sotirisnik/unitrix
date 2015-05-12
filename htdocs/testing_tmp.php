<?php

	include "models/database.php";
	include "models/sourcecode.php";
	include "models/users.php";
	include "models/problems.php"; 
	include "models/languages.php";

	OpenPDODatabase();

	try {

		$conn->beginTransaction();

		$stmt = $conn->query("SELECT id, name FROM languages"); 

		// set the resulting array to associative
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

		$lol = $stmt->fetchAll();
		echo $stmt->rowCount();

		echo $lol[0]['name'];

		foreach( new RecursiveArrayIterator( $stmt->fetchAll() ) as $k => $v ) { 
		    echo "<p>" . $v['id'] . " " . $v['name'] . "</p>";
		}

		$conn->commit();

	}catch(PDOException $e) {
		$conn->rollBack();
		echo "Error: " . $e->getMessage();
	}

	ClosePDODatabase();

?>
