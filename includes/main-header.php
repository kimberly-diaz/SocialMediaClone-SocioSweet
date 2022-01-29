<?php  
    require '../../config/config.php'; //Requiring the configuration code
    include("../../includes/classes/User.php"); //Including the Users' handler
    include("../../includes/classes/Post.php"); //Including the Post handler

    // Checks if there is a user in session
    if (isset($_SESSION['username'])) {
        $userLoggedIn = $_SESSION['username']; //Stores the username in userLoggedIn variable
        $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'"); //Gets the data from the database
        $user = mysqli_fetch_array($user_details_query); //Executes the query

        $num_friends = (substr_count($user['friend_array'], ",")) - 1; //Checks for friend count
    }
    else {
        header("Location: ../../index.php"); //If not, brings the user back to the start
    }

    $cur_page_name = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); //Gets the current page

    // POST button
    if(isset($_POST['post'])){ //If the user clicks on the Post button
        //Sets the variables
        $uploadOk = 1;
        $imageName = $_FILES['fileToUpload']['name'];
        $imageSize = $_POST['size'];
        $tag = $_POST['tag'];
        $errorMessage = "";

        // Stores the files
        $targetDir = "../../assets/images/posts/";
        $imageName = $targetDir . uniqid() . basename($imageName);
        $imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);

        if($_FILES['fileToUpload']['size'] > 10000000) { //If the size is too big
            $errorMessage = "Sorry your file is too large! Try another one!"; //Error message
            $uploadOk = 0; //Then, image did not upload
        }
        if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg") { //If the files are not these extensions
            $errorMessage = "Sorry, only files with extension jpeg, jpg and png files are allowed..."; //Error message
            $uploadOk = 0; //Then, image did not upload
        }

        if($uploadOk) {
            if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imageName)) { //Checks if the file is moved to the folder
                $post = new Post($con, $userLoggedIn);
                $post->submitPost($_POST['post_text'], 'none', $imageName, $imageSize, $tag);
                header("Location: main.php");
            }
            else { //Else, image did not upload
                $uploadOk = 0;
                echo "<div style='text-align:center;' class='alert alert-danger'>
                        $errorMessage
                    </div>";
            }   
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocioSweet</title> <!-- Title of website -->
    <link rel="icon" type="image/x-icon" href="../../assets/images/icons/favicon.png"><!-- Favicon of site -->

    <!-- Loads main AJAX file-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> 
    
    <!-- Link to all the system Javascripts -->
    <script src="../../assets/js/system/bootbox.min.js"></script>
    <script src="../../assets/js/system/bootstrap.js"></script>
	<script src="../../assets/js/system/jcrop_bits.js"></script>
	<script src="../../assets/js/system/jquery.Jcrop.js"></script>
    
    <!-- Link to the main SASS stylesheet -->
    <link rel="stylesheet" href="../../assets/sass/app.css">
</head>
<body id="body">
    <div class="loader">
        <div class="content">
            <div class="content__container">
                <p class="content__container__text">Welcome</p>

                <ul class="content__container__list">
                    <li class="content__container__list__item">world !</li>
                    <li class="content__container__list__item">sister !</li>
                    <li class="content__container__list__item">users !</li>
                    <li class="content__container__list__item">everybody !</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Post button container -->
    <div class="outer-modal-container" id="outer_modal_container">
        <div class="modal-container">
            <form class="post-form" action="#" method="POST" enctype="multipart/form-data"><!-- Start of Post form -->
                <div class="modal">
                    <div class="first-page" id="first-page"><!-- First page -->
                        <div class="post-header"><!-- Close button & Next button -->
                            <span class="close-btn" id="close-btn"><img src="../../assets/images/icons/close.png" alt="close-btn" width="20px"></span>
                            <h3>CREATE POST</h3><!-- Create post title -->
                            <div class="page-next-btn" id="post-next-btn">NEXT</div>
                        </div>
                        <div class="post-form-1">
                            <div class="post-illustration"><img src="../../assets/images/illustration-5.svg" alt="upload illustration"></div><!-- Illustration for visuals -->
                            <input type="file" name="fileToUpload" id="fileToUpload" required><!-- Upload button -->
                        </div>
                    </div>
                    <div class="second-page" id="second-page">
                        <div class="post-header"><!-- Close button & Next button -->
                            <span class="close-btn" id="close-btn-2"><img src="../../assets/images/icons/close.png" alt="close-btn" width="20px"></span>
                            <h3>CREATE POST</h3><!-- Create post title -->
                            <div class="page-next-btn-2" id="post-next-btn-2">BACK</div>
                        </div>
                        <div class="post-preview"><!-- Previews the Image -->
                            <div class="image-preview" id="imagePreview">
                                <img src="" alt="Image Preview" class="image-preview__image">
                                <span class="image-preview__default-text">Image Preview</span>
                            </div>
                            <div class="post-form-2"><!-- Other functionalities -->
                                <div>
                                    <textarea name="post_text" id="post_text" class="post-text" placeholder="Got something to say?"></textarea><!-- Post text -->
                                </div>
                                <div class="post-size-submit">
                                    <label class="select" for="slct"><!-- Custom select button -->
                                        <select name="size" id="slct" required><!-- Card size options -->
                                            <option value="" disabled="disabled" selected="selected">Select card size</option>
                                            <option value="small">Small</option>
                                            <option value="medium">Medium</option>
                                            <option value="large">Large</option>
                                        </select>
                                        <svg><use xlink:href="#select-arrow-down"></use></svg>
                                        <!-- SVG Sprites for dropdown arrow -->
                                        <svg class="sprites">
                                            <symbol id="select-arrow-down" viewbox="0 0 10 6">
                                                <polyline points="1 5 5 1 9 5"></polyline>
                                            </symbol>
                                        </svg>
                                    </label>
                                    <label class="select2" for="slct2"><!-- Custom select button -->
                                        <select name="tag" id="slct2" required><!-- Card size options -->
                                            <option value="" disabled="disabled" selected="selected">Select tag</option>
                                            <option value="general">#General</option>
                                            <option value="food">#Food</option>
                                            <option value="animals">#Animals</option>
                                            <option value="aesthetic">#Aesthetic</option>
                                            <option value="mini">#Mini</option>
                                            <option value="slime">#Slime</option>
                                            <option value="nature">#Nature</option>
                                        </select>
                                        <svg><use xlink:href="#select-arrow-down"></use></svg>
                                        <!-- SVG Sprites for dropdown arrow -->
                                        <svg class="sprites">
                                            <symbol id="select-arrow-down" viewbox="0 0 10 6">
                                                <polyline points="1 5 5 1 9 5"></polyline>
                                            </symbol>
                                        </svg>
                                    </label>
                                    <input type="submit" name="post" id="post_button" class="create-post-btn" value="Post"><!-- Submits post -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    
    <div class="main-nav-bar">
        <h3 onClick="location.href='main.php'">SOCIOSWEET</h3>
        <div>
            <div class="post-btn-wrapper">
                <button class="post-btn" id="post-btn">Create Post</button>
            </div>

            <button class="menu-burger"><img src="../../assets/images/icons/menu-burger.png" alt="menu-burger" width="35px"></button>

            <div class="nav-icons">
                <button class="friend-btn" onClick="location.href='requests.php'">
                    <?php if($cur_page_name == "requests.php") {?><img src="../../assets/images/icons/friend-2.png" alt="friend-btn" width="35px"><?php } else { ?> <img src="../../assets/images/icons/friend.png" alt="friend-btn" width="35px"> <?php } ?>
                </button>
                <button class="settings-btn" onClick="location.href='settings.php'">
                    <?php if($cur_page_name == "settings.php") {?><img src="../../assets/images/icons/settings-2.png" alt="settings-btn" width="35px"><?php } else { ?> <img src="../../assets/images/icons/settings.png" alt="settings-btn" width="35px"> <?php } ?>
                </button>
                <button class="prof-btn" onClick="location.href='<?php echo $userLoggedIn?>'"> 
                    <?php if($cur_page_name == "profile.php") {?><img src="../../assets/images/icons/user-2.png" alt="prof-btn" width="35px"><?php } else { ?><img src="../../assets/images/icons/user.png" alt="prof-btn" width="35px"><?php } ?>
                </button>
                <button onclick="location.href='../../includes/handlers/logout.php'" class="log-btn"><img src="../../assets/images/icons/exit.svg" alt="log-out-btn" width="35px"></button>
            </div>
        </div>
    </div>
    
    <div class="wrapper">
        