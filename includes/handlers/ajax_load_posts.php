<?php  
    // Includes configuration file and all classes
    include("../../config/config.php");
    include("../classes/User.php");
    include("../classes/Post.php");

    $limit = 10; //Number of posts to be loaded per call

    $posts = new Post($con, $_REQUEST['userLoggedIn']); //Creates a new Post object

    if($_SESSION["url"] == "profile") {
        $username = $_SESSION["profile"];
        $posts->loadPostsFriends($_REQUEST, $limit, $username); //Calls the loadPostFriends() and passes the variables
    }
    else if ($_SESSION["url"] == "main") {
        $posts->loadPosts($_REQUEST, $limit); //Calls the loadPostFriends() and passes the variables
    }
    else if ($_SESSION["url"] == "food") {
        $posts->loadFoodPosts($_REQUEST, $limit); //Calls the loadPostFriends() and passes the variables
    }
    else if ($_SESSION["url"] == "animals") {
        $posts->loadAnimalsPosts($_REQUEST, $limit); //Calls the loadPostFriends() and passes the variables
    }
    else if ($_SESSION["url"] == "aesthetic") {
        $posts->loadAestheticPosts($_REQUEST, $limit); //Calls the loadPostFriends() and passes the variables
    }
    else if ($_SESSION["url"] == "mini") {
        $posts->loadMiniPosts($_REQUEST, $limit); //Calls the loadPostFriends() and passes the variables
    }
    else if ($_SESSION["url"] == "slime") {
        $posts->loadSlimePosts($_REQUEST, $limit); //Calls the loadPostFriends() and passes the variables
    }
    else if ($_SESSION["url"] == "nature") {
        $posts->loadNaturePosts($_REQUEST, $limit); //Calls the loadPostFriends() and passes the variables
    }
?>