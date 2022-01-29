<?php  
if(isset($_POST['update_details'])) { //If the update-details button was clicked
	//Checks for the user's values & stores it in variables
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];
	$phase = $_POST['phases'];
	$quote1 = $_POST['quote1'];
	$quote2 = $_POST['quote2'];

	$email_check = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
	$row = mysqli_fetch_array($email_check);

	//Hides unnecessary error message from user
	error_reporting(0);
	ini_set('display_errors', 0);

	$matched_user = $row['username'];

	if($matched_user == "" || $matched_user == $userLoggedIn) { //If there are no other users with the same first and last name
		$message = "Details updated!<br><br>"; //Success message
		$username = strtolower($first_name . "-" . $phase); //Concatonates first name and phase
		
		//Updates the entire database where the username is found
		$query = mysqli_query($con, "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email', username='$username', phase='$phase', quote1='$quote1', quote2='$quote2' WHERE username='$userLoggedIn'");
		$query = mysqli_query($con, "UPDATE posts SET added_by='$username' WHERE added_by='$userLoggedIn'"); 
		$query = mysqli_query($con, "UPDATE posts SET user_to='$username' WHERE user_to='$userLoggedIn'"); 
		$query = mysqli_query($con, "UPDATE likes SET username='$username' WHERE username='$userLoggedIn'");
		$query = mysqli_query($con, "UPDATE friend_requests SET user_to='$username' WHERE user_to='$userLoggedIn'");
		$query = mysqli_query($con, "UPDATE friend_requests SET user_from='$username' WHERE user_from='$userLoggedIn'");
		$query = mysqli_query($con, "UPDATE comments SET posted_by='$username' WHERE posted_by='$userLoggedIn'");
		$query = mysqli_query($con, "UPDATE comments SET posted_to='$username' WHERE posted_to='$userLoggedIn'");

		$_SESSION['username'] = $username; //Sets the new username variable
		header("Location: settings.php"); //Refreshes the whole page to update the session variable
	}
	else {
		$message = "That email is already in use!<br><br>"; //Error message
	}
}
else {
	$message = ""; //Displays empty message
}

// To reset the password
if(isset($_POST['update_password'])) {

	$old_password = strip_tags($_POST['old_password']);
	$new_password_1 = strip_tags($_POST['new_password_1']);
	$new_password_2 = strip_tags($_POST['new_password_2']);

	$password_query = mysqli_query($con, "SELECT password FROM users WHERE username='$userLoggedIn'");
	$row = mysqli_fetch_array($password_query);
	$db_password = $row['password'];

	if(md5($old_password) == $db_password) {

		if($new_password_1 == $new_password_2) {

			if(strlen($new_password_1) <= 8) {
				$password_message = "Sorry, your password must be greater than 8 characters<br><br>";
			}	
			else {
				$new_password_md5 = md5($new_password_1);
				$password_query = mysqli_query($con, "UPDATE users SET password='$new_password_md5' WHERE username='$userLoggedIn'");
				$password_message = "Password has been changed!<br><br>";
			}
		}
		else {
			$password_message = "Your two new passwords need to match!<br><br>";
		}
	}
	else {
			$password_message = "The old password is incorrect! <br><br>";
	}
}
else {
	$password_message = "";
}

if(isset($_POST['close_account'])) { //If the close account button is clicked
	header("Location: close-account.php"); //Redirects the user to the close account page
}
?>