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

    //Get id of post
	if(isset($_GET['post_id'])) {
		$post_id = $_GET['post_id'];
	}

    $get_likes = mysqli_query($con, "SELECT likes, added_by FROM posts WHERE id='$post_id'");
	$row = mysqli_fetch_array($get_likes);
	$total_likes = $row['likes']; 
	$user_liked = $row['added_by'];

	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$user_liked'");
	$row = mysqli_fetch_array($user_details_query);
	$total_user_likes = $row['num_likes'];

	//Like button
	if(isset($_POST['like_button'])) {
		$total_likes++;
		$query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
		$total_user_likes++;
		$user_likes = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
		$insert_user = mysqli_query($con, "INSERT INTO likes VALUES('', '$userLoggedIn', '$post_id')");
	}
	//Unlike button
	if(isset($_POST['unlike_button'])) {
		$total_likes--;
		$query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
		$total_user_likes--;
		$user_likes = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
		$insert_user = mysqli_query($con, "DELETE FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
	}

    //Check for previous likes
	$check_query = mysqli_query($con, "SELECT * FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
	$num_rows = mysqli_num_rows($check_query);

    if($num_rows > 0) {
		echo '<form action="like.php?post_id=' . $post_id . '" method="POST" id="like_form">
				<button type="submit" class="comment_like" name="unlike_button"><img src="../../assets/images/icons/heart-2.png"></button>
				<div class="like_value">'. $total_likes .' Like/s</div>
			</form>
		';
	}
	else {
		echo '<form action="like.php?post_id=' . $post_id . '" method="POST" id="like_form">
				<button type="submit" class="comment_like" name="like_button""><img src="../../assets/images/icons/heart-1.png"></button>
				<div class="like_value">'. $total_likes .' Like/s</div>
			</form>
		';
	}
?>

<html>
<head>
	<title></title>
	<style>
		@import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Poppins&display=swap");
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box; /* Considers the padding values */
			font-family: 'Poppins', sans-serif; /* Sets the main font-style */
			color: #3B3B58; /*Sets the standard colour */
		}
		#like_form {
			display: grid;
			grid-template-columns: 20px auto;
			grid-gap: 10px;
			align-items: center;
		}

		.comment_like {
			background-color: white;
			border: none;
			width: 20px;
			height: 17px;
			padding: 0;
			cursor: pointer;
		}

		.comment_like img {
			width: 100%;
			height: 100%;
		}

		.like_value {
			display: inline;
			font-size: 14px;
		}
	</style>
</head>
<body>
</body>
</html>