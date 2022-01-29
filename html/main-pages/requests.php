<?php include("../../includes/main-header.php"); ?><!--Includes the main-pages header file-->

		<div class="main-pages request-page">
			<!-- Friend requests page -->
			<h3>Friend Requests</h3>
			<p>These people want to be friends with you! Decide if you want to accept or reject their request!</p>

			<?php  
				$query = mysqli_query($con, "SELECT * FROM friend_requests WHERE user_to='$userLoggedIn'");
				if(mysqli_num_rows($query) == 0) //Checks if there are friend requests from the table
					echo "You have no friend requests at this time!";
				else {
					while($row = mysqli_fetch_array($query)) { //Shows every query
						$user_from = $row['user_from']; //Checks who sent a friend request
						$user_from_obj = new User($con, $user_from); //Creates a new user object

						?><b><?php echo $user_from_obj->getUsername() . "</b> wants to be your friend!"; //Displays the message

						$user_from_friend_array = $user_from_obj->getFriendArray(); //Checks for the user's friends

						if(isset($_POST['accept_request' . $user_from ])) { //If the user chooses to accept
							$add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array, '$user_from,') WHERE username='$userLoggedIn'"); //Adds to the other user's friend array
							$add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array, '$userLoggedIn,') WHERE username='$user_from'"); //Adds to the current user's friend array

							$delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'"); //Deletes previous friend request
							echo "You are now friends!"; //Success message
							header("Location: requests.php"); //Refreshes page
						}

						if(isset($_POST['ignore_request' . $user_from ])) { //If the user chooses to ignore
							$delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'"); //Deletes friend request
							echo "Request ignored!"; //Success message
							header("Location: requests.php"); //Refreshes page
						}

						?>
						<form action="requests.php" method="POST"><!-- Start of form -->
							<input type="submit" name="accept_request<?php echo $user_from; ?>" id="accept_button" value="Accept"><!-- Accept button -->
							<input type="submit" name="ignore_request<?php echo $user_from; ?>" id="ignore_button" value="Ignore"><!-- Ignore button -->
						</form><!-- End of form -->
						<?php
					}
				}
			?>
		</b>
	</div>
	<script src="../../assets/js/imagePreview.js"></script><!-- For loading image preview -->
    <script src="../../assets/js/post.js"></script><!-- For Post button toggles -->
	<script src="../../assets/js/loader.js"></script><!-- For the loader wrapper to fadeOut -->
	<script src="../../assets/js/nav-bar.js"></script><!-- For the hamburger bar -->
</body>
</html>