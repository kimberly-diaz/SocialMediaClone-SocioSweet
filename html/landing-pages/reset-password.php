<?php require '../../includes/landing-header.php'; ?><!-- Includes landing-pages header file -->

    <div class="landing-pages reset-password-page"><!-- Contains two classes to separate landing & main pages -->
        <div class="left-text"><!-- Left text for content -->
            <!-- Text Header -->
            <p class="hashtags">#NEW #SOCIALMEDIA #WEBAPP</p>
            <h1>Never Get Locked<br>Out Of Your Account</h1>
            <div class="text2">Forgot your password? Easy! Just add in your email and we will get you sorted!</div>
            <div class="right-illustration"><img src="../../assets/images/illustration-4.svg" alt="illustration-4"></div><!-- Right illustration for visuals -->
            
            <form action="../../includes/form_handlers/reset_request.php" method="POST"><!-- Start of Reset Form -->
                    <!-- Checks for Email -->
                <div class="input">
                    <p data-end="*">Email</p>
                    <input type="email" name="reset_email" placeholder="mail@website.com" required>
                </div>
                <input type="submit" name="reset_request_button" class="reset_button" value="GET NEW PASSWORD">
            </form><!-- End of form -->
            <!-- Success message -->
            <?php if (isset($_GET["reset"])) { if ($_GET["reset"] == "success") { echo '<p class="signup-sucess">Email sent! Check your email!</p>'; }}?>
        </div>
    </div>
    <!-- Footer tag -->
    <footer class="landing-footer" style="margin-top: 260px;"><p>Copyright ©️ 2021 Bath Spa University | All Rights Reserved</p></footer>
</body>
</html>