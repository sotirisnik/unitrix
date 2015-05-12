<?php

	function compiler_language( $tmp ) {
	
		if ( $tmp == '0' ) {
			return "C++";
		}else if ( $tmp == '1' ) {
			return "Java";
		}else if ( $tmp == '2' ) {
			return "C#";
		}else if ( $tmp == '3' ) {
			return "C";
		}else if ( $tmp == '4' ) {
			return "Python 2.7";
		}else {
			return "-1";
		}
	
	}		

?>
