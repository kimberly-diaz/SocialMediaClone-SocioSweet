<?php 
    include("../../includes/main-header.php"); // Includes the main-pages header file
    $_SESSION["url"] = "main";
?>

        <div class="main-pages main-page"><!-- Start of main-page div -->
            <div class="left">
                <div class="categories">
                    <div class="item food" onclick="location.href='main-food.php'"><div class="img"><img src="../../assets/images/icons/food.png" alt="food-icon"></div><p>FOOD</p></div>
                    <div class="item animals" onclick="location.href='main-animals.php'"><div class="img"><img src="../../assets/images/icons/animals.png" alt="food-icon"></div><p>ANIMALS</p></div>
                    <div class="item aesthetic" onclick="location.href='main-aesthetic.php'"><div class="img"><img src="../../assets/images/icons/aesthetic.png" alt="food-icon"></div><p>AESTHETIC</p></div>
                    <div class="item mini" onclick="location.href='main-mini.php'"><div class="img"><img src="../../assets/images/icons/mini.png" alt="food-icon"></div><p>MINI</p></div>
                    <div class="item slime" onclick="location.href='main-slime.php'"><div class="img"><img src="../../assets/images/icons/slime.png" alt="food-icon"></div><p>SLIME</p></div>
                    <div class="item nature" onclick="location.href='main-nature.php'"><div class="img"><img src="../../assets/images/icons/nature.png" alt="food-icon"></div><p>NATURE</p></div>
                </div>
                <div class="posts_area"></div><!-- Container for all the posts to load -->
                <img id="loading" src="../../assets/images/icons/loading.gif"><!-- Loading gif for waiting for the posts to load -->
            </div>

            <div class="right" id="right">
				<!-- List of user details -->
				<div class="user-details">
                    <a href="<?php echo $userLoggedIn; ?>" class="img"><img src="<?php echo $user['profile_pic']; ?>"></a><!-- Shows the profile picture -->
                    <a href="<?php echo $userLoggedIn; ?>" class="username"><?php echo $user['username']; ?></a><!-- Shows the username -->
					<!-- List of statistics -->
                    <div class="stats">
                        <div class="posts"><?php echo $user['num_posts'] ?><p>Posts</p></div>
                        <div>•</div>
                        <div class="posts"><?php echo $user['num_likes'] ?><p>Likes</p></div>
                        <div>•</div>
                        <div class="posts"><?php echo $num_friends ?><p>Friends</p></div>
                    </div>
                    <a href="<?php echo $userLoggedIn; ?>" class="name"><?php echo $user['first_name'] . " " . $user['last_name']; ?></a><!-- Shows the first and last name -->
                    <div class="quotes"><!-- Shows the user's quotes -->
                        <p class="quote1">"<?php echo $user['quote1'] ?>"</p>
                        <p class="quote2">"<?php echo $user['quote2'] ?>"</p>
                    </div>
                </div>
			</div>
        </div>

        <!-- Javascripts -->
        <script> //Used to load posts
            $(function(){
                var userLoggedIn = '<?php echo $userLoggedIn; ?>';
                var inProgress = false;

                loadPosts(); //Load first posts

                $(window).scroll(function() {
                    var bottomElement = $(".status_post").last();
                    var noMorePosts = $('.posts_area').find('.noMorePosts').val();            
                
                    // isElementInViewport uses getBoundingClientRect(), which requires the HTML DOM object, not the jQuery object. The jQuery equivalent is using [0] as shown below.
                    if (isElementInView(bottomElement[0]) && noMorePosts == 'false') {
                        loadPosts();
                    }
                });

                function loadPosts() {
                    if(inProgress) { //If it is already in the process of loading some posts, just return
                        return;
                    }
                    
                    inProgress = true;
                    $('#loading').show();

                    var page = $('.posts_area').find('.nextPage').val() || 1; //If .nextPage couldn't be found, it must not be on the page yet (it must be the first time loading posts), so use the value '1'

                    $.ajax({
                        url: "../../includes/handlers/ajax_load_posts.php",
                        type: "POST",
                        data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
                        cache:false,

                        success: function(response) {
                            $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage
                            $('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage

                            $('#loading').hide();
                            $(".posts_area").append(response);

                            inProgress = false;
                        }
                    });
                }

                //Check if the element is in view
                function isElementInView (el) {
                    if(el == null) {
                        return;
                    }

                    var rect = el.getBoundingClientRect();

                    return (
                        rect.top >= 0 &&
                        rect.left >= 0 &&
                        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && //* or $(window).height()
                        rect.right <= (window.innerWidth || document.documentElement.clientWidth) //* or $(window).width()
                    );
                }
                });
	    </script>
        <script src="../../assets/js/imagePreview.js"></script><!-- For loading image preview -->
        <script src="../../assets/js/post.js"></script><!-- For Post button toggles -->
        <script src="../../assets/js/loader.js"></script><!-- For the loader wrapper to fadeOut -->
        <script src="../../assets/js/sticky.js"></script><!-- For the sticky element -->
        <script src="../../assets/js/nav-bar.js"></script><!-- For the hamburger bar -->
	</div>
</body>
</html>