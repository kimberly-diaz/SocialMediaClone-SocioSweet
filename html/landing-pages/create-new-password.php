<?php require '../../includes/landing-header.php'; ?><!-- Includes landing-pages header file -->

    <div class="landing-pages create-new-password-page"><!-- Contains two classes to separate landing & main pages -->
        <div class="left-text"><!-- Left text for content -->
            <!-- Text Header -->
            <p class="hashtags">#NEW #SOCIALMEDIA #WEBAPP</p>
            <h1>Never Get Locked<br>Out Of Your Account</h1>
            <div class="text2">Enter your new password!</div>
            <div class="right-illustration"><img src="../../assets/images/illustration-4.svg" alt="illustration-4"></div><!-- Right illustration for visuals -->

            <!-- Checks if the user completed the new password form successfully, if so, hides form -->
            <div class="hide-form" <?php if (isset($_GET["newpwd"]) && $_GET["newpwd"] == "passwordupdated"){ echo 'style="display: none; "'; } ?> >
                <?php 
                    $selector = $_GET["selector"];
                    $validator = $_GET["validator"];
                    
                    //Checks if the tokens are in the URL
                    if(empty($selector) || empty($validator)) { //If not, outputs an error
                        echo "Could not validate your request!";
                    }
                    else {
                        if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) { //Checks if the selector and validator is the right one
                            ?>
                                <form action="../../includes/form_handlers/reset_password.php" method="POST"><!-- Start of new password form -->
                                    <input type="hidden" name="selector" value="<?php echo $selector ?>">
                                    <input type="hidden" name="validator" value="<?php echo $validator ?>">

                                    <div class="input">
                                        <p data-end="*">Password</p>
                                        <input type="password" name="new_password" placeholder="Enter a new password..." required>
                                    </div>
                                    <div class="input">
                                        <p data-end="*">Confirm Password</p>
                                        <input type="password" name="new_password2" placeholder="Repeat new password..." required>
                                    </div>
                                    <input type="submit" name="reset_password_button" class="reset_button" value="RESET PASSWORD">
                                </form><!-- End of form -->
                            <?php
                        }
                    }
                ?>
            </div>
            <!-- If creating a new password is successful, shows this block -->
            <div id="show" <?php if (isset($_GET["newpwd"]) && $_GET["newpwd"] == "passwordupdated"){ echo 'style="display: block; "'; } ?> >
                <!-- Contains a success message and link to Login page -->
                <div class="complete-msg">Your password has been reset! Check it out by logging in!</div>
                <div class="login-btn" onClick="location.href='login.php'">LOGIN</div>
            </div>
        </div>
    </div>
    <!-- Footer tag -->
    <footer class="landing-footer" style="margin-top: 260px;"><p>Copyright ©️ 2021 Bath Spa University | All Rights Reserved</p></footer>
</body>
</html>