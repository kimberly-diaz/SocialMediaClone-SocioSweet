<?php  
	require '../../config/config.php';
	include("../../includes/classes/User.php");
	include("../../includes/classes/Post.php");

	if (isset($_SESSION['username'])) {
		$userLoggedIn = $_SESSION['username'];
		$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
		$user = mysqli_fetch_array($user_details_query);
	}
	else {
		header("Location: ../../index.php");
	}
?>

<html>
<head>
	<title></title>
	<style>
		@import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Poppins&display=swap");

		#outer-container {
			width: 100%;
			height: 100%;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		#container {
			background-color: white;
			border-radius: 15px;
			position: static;
			margin: 0 auto;
			display: grid;
			justify-content: space-between;
		}
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box; /* Considers the padding values */
			font-family: 'Poppins', sans-serif; /* Sets the main font-style */
			color: #3B3B58; /*Sets the standard colour */
		}
		h4 {
			font-family: 'Montserrat', sans-serif; /* Sets the main font-style */
			padding: 20px 0 15px;
			font-size: 18px;
			text-align: center;
			border-bottom: 2px solid #F0F6FD;
			font-weight: normal;
		}
		#comment_form {
			display: grid;
			grid-template-columns: 1fr 100px;
			margin: 20px;
			width: 500px;
		}
		.load-wrapper {
			height: 300px;
		}
		.comment_section {
			margin: 20px;
			width: 500px;
			display: grid;
			grid-template-columns: 50px 1fr 100px;
			font-size: 12px;
		}
		.comment_section img {
			border-radius: 50px;
			margin-right: 15px;
			align-self: center;
		}
		.comment_section a {
			font-size: 14px;
			text-decoration: none;
		}
		.comment_section .text {
			display: grid;
			grid-auto-flow: row;
		}
		textarea {
			width: 100%;
			height: 50px;
			border: 2px solid #F37C7C;
			border-radius: 10px 0 0 10px;
			padding: 5px 7px;
		}
		[type="submit"] {
			border: 2px solid #F37C7C;
			border-radius: 0 10px 10px 0;
			padding: 15px 30px;
			color: white;
			background-color: #F37C7C;
			border: none;
		}
	</style>
</head>
<body>
	<div id="outer-container">
		<div id="container">
			<h4>COMMENTS</h4>
			<?php  
				//Get ID of post
				if(isset($_GET['post_id'])) {
					$post_id = $_GET['post_id'];
				}

				$user_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id='$post_id'");
				$row = mysqli_fetch_array($user_query);

				$posted_to = $row['added_by'];

				if(isset($_POST['postComment' . $post_id])) {
					$post_body = $_POST['post_body'];
					$post_body = mysqli_escape_string($con, $post_body);
					$date_time_now = date("Y-m-d H:i:s");
					$insert_post = mysqli_query($con, "INSERT INTO comments (post_body, posted_by, posted_to, date_added, removed, post_id) VALUES ('$post_body', '$userLoggedIn', '$posted_to', '$date_time_now', 'no', '$post_id')");
				}
			?>

			<!-- Load comments -->
			<div class="load-wrapper">
				<?php  
					$get_comments = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id ASC");
					$count = mysqli_num_rows($get_comments);

					if($count != 0) {

						while($comment = mysqli_fetch_array($get_comments)) {

							$comment_body = $comment['post_body'];
							$posted_to = $comment['posted_to'];
							$posted_by = $comment['posted_by'];
							$date_added = $comment['date_added'];
							$removed = $comment['removed'];

							//Timeframe
							$date_time_now = date("Y-m-d H:i:s");
							$start_date = new DateTime($date_added); //Time of post
							$end_date = new DateTime($date_time_now); //Current time
							$interval = $start_date->diff($end_date); //Difference between dates 
							if($interval->y >= 1) {
								if($interval == 1)
									$time_message = $interval->y . " year ago"; //1 year ago
								else 
									$time_message = $interval->y . " years ago"; //1+ year ago
							}
							else if ($interval->m >= 1) {
								if($interval->d == 0) {
									$days = " ago";
								}
								else if($interval->d == 1) {
									$days = $interval->d . " day ago";
								}
								else {
									$days = $interval->d . " days ago";
								}


								if($interval->m == 1) {
									$time_message = $interval->m . " month". $days;
								}
								else {
									$time_message = $interval->m . " months". $days;
								}

							}
							else if($interval->d >= 1) {
								if($interval->d == 1) {
									$time_message = "Yesterday";
								}
								else {
									$time_message = $interval->d . " days ago";
								}
							}
							else if($interval->h >= 1) {
								if($interval->h == 1) {
									$time_message = $interval->h . " hour ago";
								}
								else {
									$time_message = $interval->h . " hours ago";
								}
							}
							else if($interval->i >= 1) {
								if($interval->i == 1) {
									$time_message = $interval->i . " minute ago";
								}
								else {
									$time_message = $interval->i . " minutes ago";
								}
							}
							else {
								if($interval->s < 30) {
									$time_message = "Just now";
								}
								else {
									$time_message = $interval->s . " seconds ago";
								}
							}

							$user_obj = new User($con, $posted_by);

							?>
							<div class="comment_section">
								<a href="<?php echo $posted_by?>" target="_parent">
									<img src="<?php echo $user_obj->getProfilePic();?>" title="<?php echo $posted_by; ?>" height="30">
								</a>
								<div class="text">
									<a href="<?php echo $posted_by?>" target="_parent">
										<b><?php echo $user_obj->getUsername(); ?></b>
									</a>
									<p><?php echo $comment_body; ?></p>
								</div>
								
								<p><?php echo $time_message; ?></p>
							</div>
							<?php
						}
					}
					else {
						echo "<center style='margin-top: 20px;'>No Comments to Show!</center>";
					}
				?>
			</div>
			
			<div class="comment_frame">
				<form action="comment_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST">
					<div class="textarea-wrapper"><textarea name="post_body"></textarea></div>
					<input type="submit" name="postComment<?php echo $post_id; ?>" value="Post">
				</form>
			</div>
		</div>
	</div>
</body>
</html>