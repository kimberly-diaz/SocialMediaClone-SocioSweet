<?php
    $cur_page_name = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); //Gets the current page

    // Determines the correct link for the pages
    if($cur_page_name == "index.php") {
        require 'config/config.php';
        require 'includes/form_handlers/register_handler.php';
        require 'includes/form_handlers/login_handler.php';
    }
    else {
        require '../../config/config.php';
        require '../../includes/form_handlers/register_handler.php';
        require '../../includes/form_handlers/login_handler.php';
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

    <!-- Link to the main SASS stylesheet & checks for index page -->
    <?php if($cur_page_name == "index.php") { ?><link rel="stylesheet" href="assets/sass/app.css"><?php } else { ?><link rel="stylesheet" href="../../assets/sass/app.css"><?php } ?>
</head>
<body>
    <!-- Landing-pages main navigation bar -->
    <div class="landing-nav-bar">
        <!-- Checks for index page & links it properly -->
        <?php if($cur_page_name == "index.php") { ?><h3 onClick="location.href='index.php'">SOCIOSWEET</h3><?php } else { ?><h3 onClick="location.href='../../index.php'">SOCIOSWEET</h3><?php } ?>
        <div> <!-- Register & Login buttons -->
            <?php if($cur_page_name == "index.php") { ?><button class="register-btn" onClick="location.href='html/landing-pages/register.php'">REGISTER</button><?php } else { ?><button class="register-btn" onClick="location.href='register.php'">REGISTER</button><?php } ?>
            <?php if($cur_page_name == "index.php") { ?><button class="login-btn" onClick="location.href='html/landing-pages/login.php'">LOGIN</button><?php } else { ?><button class="login-btn" onClick="location.href='login.php'">LOGIN</button><?php } ?>
        </div>
    </div>