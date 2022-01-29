<?php
    include("../../includes/main-header.php"); // Includes the main-pages header file

    if(isset($_POST['cancel'])) {
        header("Location: settings.php");
    }

    if(isset($_POST['close_account'])) {
        $close_query = mysqli_query($con, "UPDATE users SET user_closed='yes' WHERE username='$userLoggedIn'");
        session_destroy();
        header("Location: ../../index.php");
    }
?>

        <div class="main-pages close-account-page">
            <h3>Close Account</h3>

            <p style="margin-bottom: 5px">Are you sure you want to close your account?</p>
            <p style="margin-bottom: 5px">Closing your account will hide your profile and all your activity from other users.</p>
            <p style="margin-bottom: 5px">You can re-open your account at any time by simply logging in.</p>
            <br>

            <form action="close-account.php" method="POST">
                <input type="submit" name="cancel" id="update_details" value="No way!" class="info settings_submit">
                <input type="submit" name="close_account" id="close_account" value="Yes! Close it!" class="danger settings_submit">
            </form>
        </div>
    </div>
    <script src="../../assets/js/imagePreview.js"></script><!-- For loading image preview -->
    <script src="../../assets/js/post.js"></script><!-- For Post button toggles -->
	<script src="../../assets/js/loader.js"></script><!-- For the loader wrapper to fadeOut -->
    <script src="../../assets/js/nav-bar.js"></script><!-- For the hamburger bar -->
</body>
</html>