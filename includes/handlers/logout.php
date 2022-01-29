<?php 
	session_start(); //Starts the user's session
    session_destroy(); //Then destroys it immediately
    header("Location: ../../index.php"); //Brings the user back to the index page
 ?>