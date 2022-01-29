<?php require '../../includes/landing-header.php'; ?><!-- Includes landing-pages header file -->

    <div class="landing-pages login-page"><!-- Contains two classes to separate landing & main pages -->
        <div class="left-text"><!-- Left text for content -->
            <!-- Text Header -->
            <p class="hashtags">#NEW #SOCIALMEDIA #WEBAPP</p>
            <h1>Browsing The<br>Old Fashion Way</h1>
            <div class="text2">Giving to you what you need, when you need it!</div>
            <div class="right-illustration"><img src="../../assets/images/illustration-2.svg" alt="illustration-2"></div><!-- Right illustration for visuals -->
            
            <form action="login.php" method="POST"><!-- Start of Login Form -->
                <!-- Checks for Email -->
                <div class="input">
                    <p data-end="*">Email</p>
                    <input type="email" name="log_email" placeholder="mail@website.com" id="email" value="<?php if(isset($_SESSION['log_email'])) { echo $_SESSION['log_email']; } ?>" required>
                </div>
                <!-- Checks for Password -->
                <div class="input">
                    <p data-end="*">Password</p>
                    <input type="password" name="log_password" placeholder="Min. 8 characters" id="password" required>
                    <br>
                    <!-- Error message for invalid login -->
                    <?php if(in_array("Email or password was incorrect<br>", $error_array)) echo  "<p style='color:#F37C7C; font-size: 12px; margin-top: 10px; '>Email or password was incorrect<br></p>"; ?>
                </div>
                <!-- Other Functionalities -->
                <div class="other-func">
                    <!-- Remember Me feature -->
                    <div class="remember">
                        <input type="checkbox" name="remember-me" id="remember-me" onclick="setCookie()" autocomplete="off">
                        <label for="remember-me">Remember Me</label>
                    </div>
                    <!-- Forgot Password feature -->
                    <div class="forgot-pass">
                        <a href="reset-password.php">Forgot Password?</a>
                    </div>
                </div>
                <!-- For submitting the form -->
                <input type="submit" name="login_button" value="LOGIN">
                <!-- Convinient link to Register page -->
                <p class="create">Not registered yet? <a onClick="location.href='register.php'">Create an Account</a></p>
            </form><!-- End of form -->
        </div>
    </div>
    <!-- Footer tag -->
    <footer class="landing-footer"><p>Copyright ©️ 2021 Bath Spa University | All Rights Reserved</p></footer>
</body>
</html>