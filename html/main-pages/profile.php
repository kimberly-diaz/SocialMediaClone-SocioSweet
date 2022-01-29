<?php 
    include("../../includes/main-header.php"); // Includes the main-pages header file
    $_SESSION["url"] = "profile";

	if(isset($_GET['profile_username'])) {
		// Creates the variables
		$username = $_GET['profile_username'];
        $_SESSION["profile"] = $username;
        $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
		$user_array = mysqli_fetch_array($user_details_query);
		$num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
	}

	// If these buttons are clicked
	if(isset($_POST['remove_friend'])) {
		$user = new User($con, $userLoggedIn); //Creates a new User object
		$user->removeFriend($username); //Executes remove friend function
        header("Location: $username");
	}
	if(isset($_POST['add_friend'])) {
		$user = new User($con, $userLoggedIn); //Creates a new User object
		$user->sendRequest($username); //Executes send request function
        header("Location: $username");
	}
	if(isset($_POST['respond_request'])) {
		header("Location: requests.php"); //Sends the user to request page
	}
?>
		<div class="main-pages profile-page">
			<div class="left">
                <div class="posts_area"></div><!-- Container for all the posts to load -->
                <img id="loading" src="../../assets/images/icons/loading.gif"><!-- Loading gif for waiting for the posts to load -->
            </div>
			<div class="right" id="right">
				<!-- List of user details -->
				<div class="user-details">
                    <a href="<?php echo $user_array['username']; ?>" class="img"><img src="<?php echo $user_array['profile_pic']; ?>"></a><!-- Shows the profile picture -->
                    <a href="<?php echo $user_array['username']; ?>" class="username"><?php echo $user_array['username']; ?></a><!-- Shows the username -->
                    <?php if ($user_array['username'] == $userLoggedIn) { ?><button class="edit-btn" onclick="location.href='settings.php'">Edit</button><!-- Edit button --><?php } ?>
                    
					<!-- List of statistics -->
                    <div class="stats">
                        <div class="posts"><?php echo $user_array['num_posts'] ?><p>Posts</p></div>
                        <div>•</div>
                        <div class="posts"><?php echo $user_array['num_likes'] ?><p>Likes</p></div>
                        <div>•</div>
                        <div class="posts"><?php echo $num_friends ?><p>Friends</p></div>
                    </div>
                    <a href="<?php echo $userLoggedIn; ?>" class="name"><?php echo $user_array['first_name'] . " " . $user_array['last_name']; ?></a><!-- Shows the first and last name -->
                    <div class="quotes"><!-- Shows the user's quotes -->
                        <p class="quote1">"<?php echo $user['quote1'] ?>"</p>
                        <p class="quote2">"<?php echo $user['quote2'] ?>"</p>
                    </div>
					
					<form action="<?php echo $username; ?>" method="POST" id="friend_form"><!-- Start of form -->
						<?php 
							$profile_user_obj = new User($con, $username); 
							if($profile_user_obj->isClosed()) { //Checks if the user account is closed
								header("Location: user-closed.php"); //Redirects user to user-closed page
							}

							$logged_in_user_obj = new User($con, $userLoggedIn);  //Else, creates a new object

							if($userLoggedIn != $username) { //Checks if its not the user
								if($logged_in_user_obj->isFriend($username)) { //If so, checks if they're their friend
									echo '<input type="submit" name="remove_friend" class="post_remove" value="Remove Friend"><br>';
								}
								else if ($logged_in_user_obj->didReceiveRequest($username)) { //Else if, they've received a friend request
									echo '<input type="submit" name="respond_request" class="post_respond" value="Respond to Request"><br>';
								}
								else if ($logged_in_user_obj->didSendRequest($username)) {//Else if, they've sent a friend request
									echo '<input type="submit" name="" class="post_sent" value="Request Sent"><br>';
								}
								else { //Else, if they want to be friends
									echo '<input type="submit" name="add_friend" class="post_add" value="Add Friend"><br>';
								}
							}
						?>
					</form><!-- End of form -->
                </div>
			</div>
		</div>
	</div>
	
	<!-- Javascripts -->
	<script> //Used to load posts
            var userLoggedIn = '<?php echo $userLoggedIn; ?>';

            $(document).ready(function() {
                $('#loading').show();

                //Original ajax request for loading first posts 
                $.ajax({
                    url: "../../includes/handlers/ajax_load_posts.php",
                    type: "POST",
                    data: "page=1&userLoggedIn=" + userLoggedIn,
                    cache: false,

                    success: function(data) {
                        $('#loading').hide();
                        $('.posts_area').html(data);
                    }
                });

                $(window).scroll(function() {
                    var height = $('.posts_area').height(); //Div containing posts
                    var scroll_top = $(this).scrollTop();
                    var page = $('.posts_area').find('.nextPage').val();
                    var noMorePosts = $('.posts_area').find('.noMorePosts').val();

                    if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
                        $('#loading').show();

                        var ajaxReq = $.ajax({
                            url: "../../includes/handlers/ajax_load_posts.php",
                            type: "POST",
                            data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
                            cache: false,

                            success: function(response) {
                                $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
                                $('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 

                                $('#loading').hide();
                                $('.posts_area').append(response);
                            }
                        });

                    } //End of if statement
                    return false;
                }); //End of (window).scroll(function())
            });
	    </script>
	<script src="../../assets/js/imagePreview.js"></script><!-- For loading image preview -->
    <script src="../../assets/js/post.js"></script><!-- For Post button toggles -->
	<script src="../../assets/js/loader.js"></script><!-- For the loader wrapper to fadeOut -->
    <script src="../../assets/js/sticky.js"></script><!-- For the sticky element -->
    <script src="../../assets/js/nav-bar.js"></script><!-- For the hamburger bar -->
</body>
</html>