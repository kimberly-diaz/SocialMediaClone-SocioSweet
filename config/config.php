<?php
	ob_start(); //Turns on output buffering 
	session_start(); //Starts the user's session

	$timezone = date_default_timezone_set("Europe/London"); //UK time zone because UK assessment

	$con = mysqli_connect("localhost", "root", "", "social"); //Connection variable

	if(mysqli_connect_errno()) {
		echo "Failed to connect: " . mysqli_connect_errno(); //Error message
	}

?>