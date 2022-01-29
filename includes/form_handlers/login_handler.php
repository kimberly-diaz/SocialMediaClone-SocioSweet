<?php  
	if(isset($_POST['login_button'])) { //Checks if the Login button was clicked

		$email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL); //Sanitizes the email

		$_SESSION['log_email'] = $email; //Stores the email into the session variable 
		$password = md5($_POST['log_password']); //Gets password, encrypts it using MD5 algorithm and stores in variable

		// Selects all from users table where the email & password is similar
		$check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND password='$password'");
		$check_login_query = mysqli_num_rows($check_database_query);

		if($check_login_query == 1) { //If there was an entry
			$row = mysqli_fetch_array($check_database_query);
			$username = $row['username'];

			$user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'"); //Checks in database if user closed their account
			if(mysqli_num_rows($user_closed_query) == 1) {
				$reopen_account = mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$email'"); //Reopens the account if they did
			}

			$_SESSION['username'] = $username;
			$_SESSION['start'] = time();
			header("Location: ../main-pages/main.php"); //Links to main page
			exit();
		}
		else {
			array_push($error_array, "Email or password was incorrect<br>"); //Error message
		}
	}
?>