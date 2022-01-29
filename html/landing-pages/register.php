<?php require '../../includes/landing-header.php'; ?><!-- Includes landing-pages header file -->

    <div class="landing-pages register-page"><!-- Contains two classes to separate landing & main pages -->
        <div class="left-text"><!-- Left text for content -->
            <!-- Text Header -->
            <p class="hashtags">#NEW #SOCIALMEDIA #WEBAPP</p>
            <h1>Start Your New<br>Journey </h1>

            <!-- Checks if the user completed the register form successfully, if so, hides form -->
            <div class="hide-form"<?php if (in_array("Register complete", $error_array)){ echo 'style="display: none; "'; } ?>>
                <div class="text2">SocioSweet’s got you covered!</div>
                <div class="right-illustration"><img src="../../assets/images/illustration-3.svg" alt="illustration-3"></div><!-- Right illustration for visuals -->

                <form action="register.php" method="POST"><!-- Start of Register Form -->
                    <div class="register-input">
                        <div class="name">
                            <!-- Checks for First Name -->
                            <div class="input">
                                <p data-end="*">First Name</p>
                                <input type="text" name="reg_fname" placeholder="John" id="fname" value="<?php if(isset($_SESSION['reg_fname'])) { echo $_SESSION['reg_fname']; } ?>" required>
                                <br>
                                <!-- Error message -->
                                <?php if(in_array("Your first name must be between 2 and 25 characters<br>", $error_array)) echo "<p style='color:#F37C7C; font-size: 12px; margin-top: 5px; '>Your first name must be between 2 and 25 characters</p>"; ?>
                            </div>
                            <!-- Checks for Last Name -->
                            <div class="input">
                                <p data-end="*">Last Name</p>
                                <input type="text" name="reg_lname" placeholder="Doe" id="lname" value="<?php 
                                if(isset($_SESSION['reg_lname'])) { echo $_SESSION['reg_lname']; } ?>" required>
                                <br>
                                <!-- Error message -->
                                <?php if(in_array("Your last name must be between 2 and 25 characters<br>", $error_array)) echo "<p style='color:#F37C7C; font-size: 12px; margin-top: 5px; '>Your last name must be between 2 and 25 characters</p>"; ?>
                            </div>
                        </div>
                        <!-- Checks for Email Address -->
                        <div class="input">
                            <p data-end="*">Email Address</p>
                            <input type="email" name="reg_email" placeholder="mail@website.com" id="email" value="<?php if(isset($_SESSION['reg_email'])) { echo $_SESSION['reg_email']; } ?>" required>
                        </div>
                        <!-- Confirms Email Address -->
                        <div class="input">
                            <p data-end="*">Confirm Email Address</p>
                            <input type="email" name="reg_email2" placeholder="mail@website.com" id="email2" value="<?php if(isset($_SESSION['reg_email2'])) { echo $_SESSION['reg_email2']; } ?>" required>
                        </div>
                        <!-- Error messages -->
                        <?php if(in_array("Email already in use<br>", $error_array)) echo "<p style='color:#F37C7C; font-size: 12px; margin-top: 5px; '>Email already in use </p>"; 
                        else if(in_array("Invalid email format<br>", $error_array)) echo "<p style='color:#F37C7C; font-size: 12px; margin-top: 5px; '>Invalid email format</p>";
                        else if(in_array("Emails don't match<br>", $error_array)) echo "<p style='color:#F37C7C; font-size: 12px; margin-top: 5px; '>Emails don't match</p>"; ?>
                        
                        <!-- Checks for Password -->
                        <div class="input">
                            <p data-end="*">Password</p>
                            <input type="password" name="reg_password" placeholder="Min. 8 characters" required>
                        </div>
                        <!-- Confirms Password -->
                        <div class="input">
                            <p data-end="*">Confirm Password</p>
                            <input type="password" name="reg_password2" placeholder="Min. 8 characters" required>
                        </div>
                        <!-- Error messages -->
                        <?php if(in_array("Your passwords do not match<br>", $error_array)) echo "<p style='color:#F37C7C; font-size: 12px; margin-top: 5px; '>Your passwords do not match</p>"; 
                        else if(in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo "<p style='color:#F37C7C; font-size: 12px; margin-top: 5px; '>Your password can only contain english characters or numbers</p>";
                        else if(in_array("Your password must be betwen 8 and 30 characters<br>", $error_array)) echo "<p style='color:#F37C7C; font-size: 12px; margin-top: 5px; '>Your password must be betwen 8 and 30 characters</p>"; ?>
                        <!-- Checks for phase in life -->
                        <div class="input">
                            <p data-end="*">Phase</p>
                            <div class="select-wrapper"><select name="reg_phases" id="phases" required>
                                <option value>Please select</option> 
                                <option value="hs">High School</option>
                                <option value="uni">University</option>
                                <option value="ya">Young Adult</option>
                                <option value="a">Adult</option>
                            </select></div>
                        </div>
                    </div>
                    <!-- For submitting the form -->
                    <input type="submit" name="register_button" value="REGISTER">
                </form><!-- End of form -->
                <!-- Convinient link to Login page -->
                <p class="create">Already have an account? <a onClick="location.href='login.php'">Login</a></p>
            </div>
            
            <!-- If registration is successful, shows this block -->
            <div id="show" <?php if (in_array("Register complete", $error_array)){ echo 'style="display: block; "'; } ?> >
                <!-- Contains a success message and link to Login page -->
                <div class="complete-msg">You’re all set! Let’s get you started! Click on the login button!</div>
                <div class="login-btn" onClick="location.href='login.php'">LOGIN</div>
            </div>
        </div>
    </div>
    <!-- Footer tag -->
    <footer class="landing-footer"><p>Copyright ©️ 2021 Bath Spa University | All Rights Reserved</p></footer>
</body>
</html>