=4567441=<?php
class Post {
	private $user_obj;
	private $con;

	public function __construct($con, $user){
		$this->con = $con;
		$this->user_obj = new User($con, $user);
	}

	public function submitPost($body, $user_to, $imageName, $imageSize, $tag) {
		$body = strip_tags($body); //removes html tags 
		$body = mysqli_real_escape_string($this->con, $body);
		$check_empty = preg_replace('/\s+/', '', $body); //Deletes all spaces
      
		if($check_empty != "" || $imageName != "") {
			//Current date and time
			$date_added = date("Y-m-d H:i:s");
			//Get username
			$added_by = $this->user_obj->getUsername();

			//If user is on own profile, user_to is 'none'
			if($user_to == $added_by) {
				$user_to = "none";
			}

			//Insert post 
			$query = mysqli_query($this->con, "INSERT INTO posts (body, added_by, user_to, date_added, user_closed, deleted, likes, image_name, image_size, tag) VALUES ('$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0', '$imageName', '$imageSize', '$tag')");
			$returned_id = mysqli_insert_id($this->con);

			//Update post count for user 
			$num_posts = $this->user_obj->getNumPosts();
			$num_posts++;
			$update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");

		}
	}

	public function loadPosts($data, $limit) {
		$page = $data['page']; 
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0) {
			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$imagePath = $row['image_name'];
				$imageSize = $row['image_size']; 
				$tag = $row['tag']; 

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					continue;
				}

				if($num_iterations++ < $start) {
					continue; 
				}

				//Once 10 posts have been loaded, break
				if($count > $limit) {
					break;
				}
				else {
					$count++;
				}

				if($userLoggedIn == $added_by)
					$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
				else {
					$delete_button = "";
				}

				$user_details_query = mysqli_query($this->con, "SELECT username, profile_pic FROM users WHERE username='$added_by'");
				$user_row = mysqli_fetch_array($user_details_query);
				$username = $user_row['username'];
				$profile_pic = $user_row['profile_pic'];

				?>
					<script> 
						function toggle<?php echo $id; ?>() {
							var element = document.getElementById("toggleComment<?php echo $id; ?>");
							var body = document.getElementById('body');

							if(element.style.display === "block") {
								element.style.display = "none";
								body.style.overflow = "visible";
							}
							else {
								element.style.display = "block";
								body.style.overflow = "hidden";
							}
						}
					</script>
					<?php

					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);

					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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
							$time_message = $interval->m . " month ". $days;
						}
						else {
							$time_message = $interval->m . " months ". $days;
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

					if($imagePath != "") {
						$imageDiv = "<div class='post_image'>
										<img src='$imagePath'>
									</div>";
					}
					else {
						$imageDiv = "";
					}

					if ($imageSize == "medium") {
						$str .= "<div class='status_post medium' id='status_post medium'>";
					}
					else if ($imageSize == "large") {
						$str .= "<div class='status_post large' id='status_post large'>";
					}
					else {
						$str .= "<div class='status_post small' id='status_post small'>";
					}

					$str .= "	<div class='status_header'>
									<div class='post_profile_pic'>
										<img src='$profile_pic'>
									</div>
									<div class='posted_by'>
										<a href='$added_by'>$username</a> $user_to
										<p class='tag'>#$tag</p>
									</div>
									$delete_button
								</div>
								$imageDiv
								<div class='post_like'>
									<iframe src='like.php?post_id=$id' scrolling='no' loading='lazy'></iframe>
								</div>
								<div id='post_body' onClick='javascript:toggle$id()' style='cursor: pointer;'>
									<img src='../../assets/images/icons/quote.png' class='quote-img'>$body
								</div>
								<div>
									$time_message
								</div>
							</div>

							<div class='post_comment' id='toggleComment$id'>
								<div onClick='javascript:toggle$id()' class='comment-close'><img src='../../assets/images/icons/close.png' alt='close-btn'></div>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
							</div>";
			?>
				<script>
					$(document).ready(function() {
						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("../../includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});
					});
				</script>
				<?php

			} //End while loop

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre; margin: 15px;'> No more posts to show! </p>";
		}

		echo $str;
	}

	public function loadPostsFriends($data, $limit, $username) {
		$page = $data['page']; 
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0 && $username == $userLoggedIn) {
			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$imagePath = $row['image_name'];
				$imageSize = $row['image_size']; 
				$tag = $row['tag'];

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					continue;
				}

				$user_logged_obj = new User($this->con, $userLoggedIn);
				if($user_logged_obj->isFriend($added_by)){

					if($num_iterations++ < $start)
						continue; 


					//Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}

					if($userLoggedIn == $added_by)
						$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					else 
						$delete_button = "";


						$user_details_query = mysqli_query($this->con, "SELECT username, profile_pic FROM users WHERE username='$added_by'");
						$user_row = mysqli_fetch_array($user_details_query);
						$username = $user_row['username'];
						$profile_pic = $user_row['profile_pic'];


					?>
					<script> 
						function toggle<?php echo $id; ?>() {
							var element = document.getElementById("toggleComment<?php echo $id; ?>");
							var body = document.getElementById('body');

							if(element.style.display === "block") {
								element.style.display = "none";
								body.style.overflow = "visible";
							}
							else {
								element.style.display = "block";
								body.style.overflow = "hidden";
							}
						}
					</script>
					<?php

					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);

					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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
							$time_message = $interval->m . " month ". $days;
						}
						else {
							$time_message = $interval->m . " months ". $days;
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

					if($imagePath != "") {
						$imageDiv = "<div class='post_image'>
										<img src='$imagePath'>
									</div>";
					}
					else {
						$imageDiv = "";
					}

					if ($imageSize == "medium") {
						$str .= "<div class='status_post medium' id='status_post medium'>";
					}
					else if ($imageSize == "large") {
						$str .= "<div class='status_post large' id='status_post large'>";
					}
					else {
						$str .= "<div class='status_post small' id='status_post small'>";
					}

					$str .= "	<div class='status_header'>
									<div class='post_profile_pic'>
										<img src='$profile_pic'>
									</div>
									<div class='posted_by'>
										<a href='$added_by'>$username</a> $user_to
										<p class='tag'>#$tag</p>
									</div>
									$delete_button
								</div>
								$imageDiv
								<div class='post_like'>
									<iframe src='like.php?post_id=$id' scrolling='no' loading='lazy'></iframe>
								</div>
								<div id='post_body' onClick='javascript:toggle$id()' style='cursor: pointer;'>
									<img src='../../assets/images/icons/quote.png' class='quote-img'>$body
								</div>
								<div>
									$time_message
								</div>
							</div>

							<div class='post_comment' id='toggleComment$id'>
								<div onClick='javascript:toggle$id()' class='comment-close'><img src='../../assets/images/icons/close.png' alt='close-btn'></div>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
							</div>";
				}

			?>
				<script>
					$(document).ready(function() {
						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("../../includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});
					});

				</script>
				<?php

			} //End while loop

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre; margin: 15px;'> No more posts to show! </p>";
		}
		else {
			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$imagePath = $row['image_name'];
				$imageSize = $row['image_size']; 
				$tag = $row['tag'];

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					continue;
				}

				if($username == $added_by){ //Checks if the username is equal to who added it
					if($num_iterations++ < $start)
						continue; 


					//Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}

					if($userLoggedIn == $added_by)
						$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					else 
						$delete_button = "";

						$user_details_query = mysqli_query($this->con, "SELECT username, profile_pic FROM users WHERE username='$added_by'");
						$user_row = mysqli_fetch_array($user_details_query);
						$username = $user_row['username'];
						$profile_pic = $user_row['profile_pic'];
					?>
					<script> 
						function toggle<?php echo $id; ?>() {
							var element = document.getElementById("toggleComment<?php echo $id; ?>");
							var body = document.getElementById('body');

							if(element.style.display === "block") {
								element.style.display = "none";
								body.style.overflow = "visible";
							}
							else {
								element.style.display = "block";
								body.style.overflow = "hidden";
							}
						}
					</script>
					<?php

					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);

					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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
							$time_message = $interval->m . " month ". $days;
						}
						else {
							$time_message = $interval->m . " months ". $days;
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

					if($imagePath != "") {
						$imageDiv = "<div class='post_image'>
										<img src='$imagePath'>
									</div>";
					}
					else {
						$imageDiv = "";
					}

					if ($imageSize == "medium") {
						$str .= "<div class='status_post medium' id='status_post medium'>";
					}
					else if ($imageSize == "large") {
						$str .= "<div class='status_post large' id='status_post large'>";
					}
					else {
						$str .= "<div class='status_post small' id='status_post small'>";
					}

					$str .= "	<div class='status_header'>
									<div class='post_profile_pic'>
										<img src='$profile_pic'>
									</div>
									<div class='posted_by'>
										<a href='$added_by'>$username</a> $user_to
										<p class='tag'>#$tag</p>
									</div>
									$delete_button
								</div>
								$imageDiv
								<div class='post_like'>
									<iframe src='like.php?post_id=$id' scrolling='no' loading='lazy'></iframe>
								</div>
								<div id='post_body' onClick='javascript:toggle$id()' style='cursor: pointer;'>
									<img src='../../assets/images/icons/quote.png' class='quote-img'>$body
								</div>
								<div>
									$time_message
								</div>
							</div>

							<div class='post_comment' id='toggleComment$id'>
								<div onClick='javascript:toggle$id()' class='comment-close'><img src='../../assets/images/icons/close.png' alt='close-btn'></div>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
							</div>";
				}

			?>
				<script>

					$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("../../includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});
					});

				</script>
				<?php

			} //End while loop

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre; margin: 15px;'> No more posts to show! </p>";

		}

		echo $str;
	}

	public function loadFoodPosts($data, $limit) {
		$page = $data['page']; 
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0) {
			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$imagePath = $row['image_name'];
				$imageSize = $row['image_size']; 
				$tag = $row['tag']; 

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					continue;
				}

				if($row['tag'] == 'food'){

					if($num_iterations++ < $start)
						continue; 


					//Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}

					if($userLoggedIn == $added_by)
						$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					else 
						$delete_button = "";


						$user_details_query = mysqli_query($this->con, "SELECT username, profile_pic FROM users WHERE username='$added_by'");
						$user_row = mysqli_fetch_array($user_details_query);
						$username = $user_row['username'];
						$profile_pic = $user_row['profile_pic'];


					?>
					<script> 
						function toggle<?php echo $id; ?>() {
							var element = document.getElementById("toggleComment<?php echo $id; ?>");
							var body = document.getElementById('body');

							if(element.style.display === "block") {
								element.style.display = "none";
								body.style.overflow = "visible";
							}
							else {
								element.style.display = "block";
								body.style.overflow = "hidden";
							}
						}
					</script>
					<?php

					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);

					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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
							$time_message = $interval->m . " month ". $days;
						}
						else {
							$time_message = $interval->m . " months ". $days;
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

					if($imagePath != "") {
						$imageDiv = "<div class='post_image'>
										<img src='$imagePath'>
									</div>";
					}
					else {
						$imageDiv = "";
					}

					if ($imageSize == "medium") {
						$str .= "<div class='status_post medium' id='status_post medium'>";
					}
					else if ($imageSize == "large") {
						$str .= "<div class='status_post large' id='status_post large'>";
					}
					else {
						$str .= "<div class='status_post small' id='status_post small'>";
					}

					$str .= "	<div class='status_header'>
									<div class='post_profile_pic'>
										<img src='$profile_pic'>
									</div>
									<div class='posted_by'>
										<a href='$added_by'>$username</a> $user_to
										<p class='tag'>#$tag</p>
									</div>
									$delete_button
								</div>
								$imageDiv
								<div class='post_like'>
									<iframe src='like.php?post_id=$id' scrolling='no' loading='lazy'></iframe>
								</div>
								<div id='post_body' onClick='javascript:toggle$id()' style='cursor: pointer;'>
									<img src='../../assets/images/icons/quote.png' class='quote-img'>$body
								</div>
								<div>
									$time_message
								</div>
							</div>

							<div class='post_comment' id='toggleComment$id'>
								<div onClick='javascript:toggle$id()' class='comment-close'><img src='../../assets/images/icons/close.png' alt='close-btn'></div>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
							</div>";
				}

			?>
				<script>

					$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("../../includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});
					});

				</script>
				<?php

			} //End while loop

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre; margin: 15px;'> No more posts to show! </p>";
		}

		echo $str;
	}

	public function loadAnimalsPosts($data, $limit) {
		$page = $data['page']; 
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0) {
			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$imagePath = $row['image_name'];
				$imageSize = $row['image_size']; 
				$tag = $row['tag']; 

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					continue;
				}

				if($row['tag'] == 'animals'){

					if($num_iterations++ < $start)
						continue; 


					//Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}

					if($userLoggedIn == $added_by)
						$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					else 
						$delete_button = "";


						$user_details_query = mysqli_query($this->con, "SELECT username, profile_pic FROM users WHERE username='$added_by'");
						$user_row = mysqli_fetch_array($user_details_query);
						$username = $user_row['username'];
						$profile_pic = $user_row['profile_pic'];


					?>
					<script> 
						function toggle<?php echo $id; ?>() {
							var element = document.getElementById("toggleComment<?php echo $id; ?>");
							var body = document.getElementById('body');

							if(element.style.display === "block") {
								element.style.display = "none";
								body.style.overflow = "visible";
							}
							else {
								element.style.display = "block";
								body.style.overflow = "hidden";
							}
						}
					</script>
					<?php

					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);

					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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
							$time_message = $interval->m . " month ". $days;
						}
						else {
							$time_message = $interval->m . " months ". $days;
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

					if($imagePath != "") {
						$imageDiv = "<div class='post_image'>
										<img src='$imagePath'>
									</div>";
					}
					else {
						$imageDiv = "";
					}

					if ($imageSize == "medium") {
						$str .= "<div class='status_post medium' id='status_post medium'>";
					}
					else if ($imageSize == "large") {
						$str .= "<div class='status_post large' id='status_post large'>";
					}
					else {
						$str .= "<div class='status_post small' id='status_post small'>";
					}

					$str .= "	<div class='status_header'>
									<div class='post_profile_pic'>
										<img src='$profile_pic'>
									</div>
									<div class='posted_by'>
										<a href='$added_by'>$username</a> $user_to
										<p class='tag'>#$tag</p>
									</div>
									$delete_button
								</div>
								$imageDiv
								<div class='post_like'>
									<iframe src='like.php?post_id=$id' scrolling='no' loading='lazy'></iframe>
								</div>
								<div id='post_body' onClick='javascript:toggle$id()' style='cursor: pointer;'>
									<img src='../../assets/images/icons/quote.png' class='quote-img'>$body
								</div>
								<div>
									$time_message
								</div>
							</div>

							<div class='post_comment' id='toggleComment$id'>
								<div onClick='javascript:toggle$id()' class='comment-close'><img src='../../assets/images/icons/close.png' alt='close-btn'></div>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
							</div>";
				}

			?>
				<script>

					$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("../../includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});
					});

				</script>
				<?php

			} //End while loop

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre; margin: 15px;'> No more posts to show! </p>";
		}

		echo $str;
	}

	public function loadAestheticPosts($data, $limit) {
		$page = $data['page']; 
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0) {
			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$imagePath = $row['image_name'];
				$imageSize = $row['image_size']; 
				$tag = $row['tag']; 

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					continue;
				}

				if($row['tag'] == 'aesthetic'){

					if($num_iterations++ < $start)
						continue; 


					//Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}

					if($userLoggedIn == $added_by)
						$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					else 
						$delete_button = "";


						$user_details_query = mysqli_query($this->con, "SELECT username, profile_pic FROM users WHERE username='$added_by'");
						$user_row = mysqli_fetch_array($user_details_query);
						$username = $user_row['username'];
						$profile_pic = $user_row['profile_pic'];


					?>
					<script> 
						function toggle<?php echo $id; ?>() {
							var element = document.getElementById("toggleComment<?php echo $id; ?>");
							var body = document.getElementById('body');

							if(element.style.display === "block") {
								element.style.display = "none";
								body.style.overflow = "visible";
							}
							else {
								element.style.display = "block";
								body.style.overflow = "hidden";
							}
						}
					</script>
					<?php

					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);

					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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
							$time_message = $interval->m . " month ". $days;
						}
						else {
							$time_message = $interval->m . " months ". $days;
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

					if($imagePath != "") {
						$imageDiv = "<div class='post_image'>
										<img src='$imagePath'>
									</div>";
					}
					else {
						$imageDiv = "";
					}

					if ($imageSize == "medium") {
						$str .= "<div class='status_post medium' id='status_post medium'>";
					}
					else if ($imageSize == "large") {
						$str .= "<div class='status_post large' id='status_post large'>";
					}
					else {
						$str .= "<div class='status_post small' id='status_post small'>";
					}

					$str .= "	<div class='status_header'>
									<div class='post_profile_pic'>
										<img src='$profile_pic'>
									</div>
									<div class='posted_by'>
										<a href='$added_by'>$username</a> $user_to
										<p class='tag'>#$tag</p>
									</div>
									$delete_button
								</div>
								$imageDiv
								<div class='post_like'>
									<iframe src='like.php?post_id=$id' scrolling='no' loading='lazy'></iframe>
								</div>
								<div id='post_body' onClick='javascript:toggle$id()' style='cursor: pointer;'>
									<img src='../../assets/images/icons/quote.png' class='quote-img'>$body
								</div>
								<div>
									$time_message
								</div>
							</div>

							<div class='post_comment' id='toggleComment$id'>
								<div onClick='javascript:toggle$id()' class='comment-close'><img src='../../assets/images/icons/close.png' alt='close-btn'></div>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
							</div>";
				}

			?>
				<script>

					$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("../../includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});
					});

				</script>
				<?php

			} //End while loop

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre; margin: 15px;'> No more posts to show! </p>";
		}

		echo $str;
	}

	public function loadMiniPosts($data, $limit) {
		$page = $data['page']; 
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0) {
			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$imagePath = $row['image_name'];
				$imageSize = $row['image_size']; 
				$tag = $row['tag']; 

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					continue;
				}

				if($row['tag'] == 'mini'){

					if($num_iterations++ < $start)
						continue; 


					//Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}

					if($userLoggedIn == $added_by)
						$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					else 
						$delete_button = "";


						$user_details_query = mysqli_query($this->con, "SELECT username, profile_pic FROM users WHERE username='$added_by'");
						$user_row = mysqli_fetch_array($user_details_query);
						$username = $user_row['username'];
						$profile_pic = $user_row['profile_pic'];


					?>
					<script> 
						function toggle<?php echo $id; ?>() {
							var element = document.getElementById("toggleComment<?php echo $id; ?>");
							var body = document.getElementById('body');

							if(element.style.display === "block") {
								element.style.display = "none";
								body.style.overflow = "visible";
							}
							else {
								element.style.display = "block";
								body.style.overflow = "hidden";
							}
						}
					</script>
					<?php

					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);

					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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
							$time_message = $interval->m . " month ". $days;
						}
						else {
							$time_message = $interval->m . " months ". $days;
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

					if($imagePath != "") {
						$imageDiv = "<div class='post_image'>
										<img src='$imagePath'>
									</div>";
					}
					else {
						$imageDiv = "";
					}

					if ($imageSize == "medium") {
						$str .= "<div class='status_post medium' id='status_post medium'>";
					}
					else if ($imageSize == "large") {
						$str .= "<div class='status_post large' id='status_post large'>";
					}
					else {
						$str .= "<div class='status_post small' id='status_post small'>";
					}

					$str .= "	<div class='status_header'>
									<div class='post_profile_pic'>
										<img src='$profile_pic'>
									</div>
									<div class='posted_by'>
										<a href='$added_by'>$username</a> $user_to
										<p class='tag'>#$tag</p>
									</div>
									$delete_button
								</div>
								$imageDiv
								<div class='post_like'>
									<iframe src='like.php?post_id=$id' scrolling='no' loading='lazy'></iframe>
								</div>
								<div id='post_body' onClick='javascript:toggle$id()' style='cursor: pointer;'>
									<img src='../../assets/images/icons/quote.png' class='quote-img'>$body
								</div>
								<div>
									$time_message
								</div>
							</div>

							<div class='post_comment' id='toggleComment$id'>
								<div onClick='javascript:toggle$id()' class='comment-close'><img src='../../assets/images/icons/close.png' alt='close-btn'></div>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
							</div>";
				}

			?>
				<script>

					$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("../../includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});
					});

				</script>
				<?php

			} //End while loop

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre; margin: 15px;'> No more posts to show! </p>";
		}

		echo $str;
	}

	public function loadSlimePosts($data, $limit) {
		$page = $data['page']; 
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0) {
			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$imagePath = $row['image_name'];
				$imageSize = $row['image_size']; 
				$tag = $row['tag']; 

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					continue;
				}

				if($row['tag'] == 'slime'){

					if($num_iterations++ < $start)
						continue; 


					//Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}

					if($userLoggedIn == $added_by)
						$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					else 
						$delete_button = "";


						$user_details_query = mysqli_query($this->con, "SELECT username, profile_pic FROM users WHERE username='$added_by'");
						$user_row = mysqli_fetch_array($user_details_query);
						$username = $user_row['username'];
						$profile_pic = $user_row['profile_pic'];


					?>
					<script> 
						function toggle<?php echo $id; ?>() {
							var element = document.getElementById("toggleComment<?php echo $id; ?>");
							var body = document.getElementById('body');

							if(element.style.display === "block") {
								element.style.display = "none";
								body.style.overflow = "visible";
							}
							else {
								element.style.display = "block";
								body.style.overflow = "hidden";
							}
						}
					</script>
					<?php

					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);

					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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
							$time_message = $interval->m . " month ". $days;
						}
						else {
							$time_message = $interval->m . " months ". $days;
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

					if($imagePath != "") {
						$imageDiv = "<div class='post_image'>
										<img src='$imagePath'>
									</div>";
					}
					else {
						$imageDiv = "";
					}

					if ($imageSize == "medium") {
						$str .= "<div class='status_post medium' id='status_post medium'>";
					}
					else if ($imageSize == "large") {
						$str .= "<div class='status_post large' id='status_post large'>";
					}
					else {
						$str .= "<div class='status_post small' id='status_post small'>";
					}

					$str .= "	<div class='status_header'>
									<div class='post_profile_pic'>
										<img src='$profile_pic'>
									</div>
									<div class='posted_by'>
										<a href='$added_by'>$username</a> $user_to
										<p class='tag'>#$tag</p>
									</div>
									$delete_button
								</div>
								$imageDiv
								<div class='post_like'>
									<iframe src='like.php?post_id=$id' scrolling='no' loading='lazy'></iframe>
								</div>
								<div id='post_body' onClick='javascript:toggle$id()' style='cursor: pointer;'>
									<img src='../../assets/images/icons/quote.png' class='quote-img'>$body
								</div>
								<div>
									$time_message
								</div>
							</div>

							<div class='post_comment' id='toggleComment$id'>
								<div onClick='javascript:toggle$id()' class='comment-close'><img src='../../assets/images/icons/close.png' alt='close-btn'></div>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
							</div>";
				}

			?>
				<script>

					$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("../../includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});
					});

				</script>
				<?php

			} //End while loop

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre; margin: 15px;'> No more posts to show! </p>";
		}

		echo $str;
	}

	public function loadNaturePosts($data, $limit) {
		$page = $data['page']; 
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0) {
			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$imagePath = $row['image_name'];
				$imageSize = $row['image_size']; 
				$tag = $row['tag']; 

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					continue;
				}

				if($row['tag'] == 'nature'){

					if($num_iterations++ < $start)
						continue; 


					//Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}

					if($userLoggedIn == $added_by)
						$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					else 
						$delete_button = "";


						$user_details_query = mysqli_query($this->con, "SELECT username, profile_pic FROM users WHERE username='$added_by'");
						$user_row = mysqli_fetch_array($user_details_query);
						$username = $user_row['username'];
						$profile_pic = $user_row['profile_pic'];


					?>
					<script> 
						function toggle<?php echo $id; ?>() {
							var element = document.getElementById("toggleComment<?php echo $id; ?>");
							var body = document.getElementById('body');

							if(element.style.display === "block") {
								element.style.display = "none";
								body.style.overflow = "visible";
							}
							else {
								element.style.display = "block";
								body.style.overflow = "hidden";
							}
						}
					</script>
					<?php

					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);

					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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
							$time_message = $interval->m . " month ". $days;
						}
						else {
							$time_message = $interval->m . " months ". $days;
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

					if($imagePath != "") {
						$imageDiv = "<div class='post_image'>
										<img src='$imagePath'>
									</div>";
					}
					else {
						$imageDiv = "";
					}

					if ($imageSize == "medium") {
						$str .= "<div class='status_post medium' id='status_post medium'>";
					}
					else if ($imageSize == "large") {
						$str .= "<div class='status_post large' id='status_post large'>";
					}
					else {
						$str .= "<div class='status_post small' id='status_post small'>";
					}

					$str .= "	<div class='status_header'>
									<div class='post_profile_pic'>
										<img src='$profile_pic'>
									</div>
									<div class='posted_by'>
										<a href='$added_by'>$username</a> $user_to
										<p class='tag'>#$tag</p>
									</div>
									$delete_button
								</div>
								$imageDiv
								<div class='post_like'>
									<iframe src='like.php?post_id=$id' scrolling='no' loading='lazy'></iframe>
								</div>
								<div id='post_body' onClick='javascript:toggle$id()' style='cursor: pointer;'>
									<img src='../../assets/images/icons/quote.png' class='quote-img'>$body
								</div>
								<div>
									$time_message
								</div>
							</div>

							<div class='post_comment' id='toggleComment$id'>
								<div onClick='javascript:toggle$id()' class='comment-close'><img src='../../assets/images/icons/close.png' alt='close-btn'></div>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
							</div>";
				}

			?>
				<script>

					$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("../../includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});
					});

				</script>
				<?php

			} //End while loop

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre; margin: 15px;'> No more posts to show! </p>";
		}

		echo $str;
	}
}

?>