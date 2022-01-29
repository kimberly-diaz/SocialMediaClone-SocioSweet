<?php 
    include("../../includes/main-header.php"); //Includes the main-pages header file
    include("../../includes/form_handlers/settings_handler.php"); //Includes the settings-handler file
?>
        <div class="settings-page">
            <?php //Fetches the data
                $user_data_query = mysqli_query($con, "SELECT first_name, last_name, email, quote1, quote2 FROM users WHERE username='$userLoggedIn'");
                $row = mysqli_fetch_array($user_data_query);

                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $email = $row['email'];
                $quote1 = $row['quote1'];
                $quote2 = $row['quote2'];
            ?>
            <div>
                <h3>Account</h3>
                <!-- Echoes the success or error message -->
                <?php 
                    if($message == "") { ?>
                        <h4 class="message" style="color:#3B3B58;">This information will be displayed publically so be careful what you share. Modify the values and click 'Update Details'</h4><?php 
                    } 
                    else if ($message == "Details updated!<br><br>") { ?>
                        <h4 class="message" style="color:#F58F8F;">Details updated!</h4><?php 
                    } 
                    else if ($message == "That email is already in use!<br><br>") { ?>
                        <h4 class="message">That email is already in use!</p><?php 
                    } 
                ?>
                <form action="settings.php" method="POST"><!-- Start of form -->
                    <div class="card">
                        <div>
                            <h3>First Name</h3>
                            <input type="text" name="first_name" value="<?php echo $first_name; ?>" id="settings_input" required><!-- Displays the first name -->
                        </div>
                        <div>
                            <h3>Last Name</h3>
                            <input type="text" name="last_name" value="<?php echo $last_name; ?>" id="settings_input" required><!-- Displays the last name -->
                        </div>
                    </div>
                    <div class="card">
                        <div>
                            <h3>Email</h3>
                            <input type="text" name="email" value="<?php echo $email; ?>" id="settings_input" required><!-- Displays the email -->
                        </div>
                        <div>
                            <h3>Phase</h3>
                            <div class="select-wrapper">
                                <select name="phases" id="phases" required>
                                    <option value>Please select</option> 
                                        <option value="hs">High School</option>
                                        <option value="uni">University</option>
                                        <option value="ya">Young Adult</option>
                                        <option value="a">Adult</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card quote">
                        <div>
                            <h3>First Quote</h3>
                            <input type="text" name="quote1" value="<?php echo $quote1; ?>" id="settings_input" required><!-- Displays the first quote -->
                        </div>
                        <div>
                            <h3>Second Quote</h3>
                            <input type="text" name="quote2" value="<?php echo $quote2; ?>" id="settings_input" required><!-- Displays the second quote -->
                        </div>
                    </div>
                    <div class="card picture">
                        <div>
                            <h3>Photo</h3>
                            <div>
                                <?php echo "<div class='settings-img'><img src='" . $user['profile_pic'] ."'></div>"; ?>
                                <a href="upload.php">Change</a><!-- Uploads a new profile picture by linking to upload-page -->
                            </div>
                        </div>
                        <input type="submit" name="update_details" id="save_details" value="Update Details" class="settings_submit"><br><!-- Submits the form -->
                    </div>
                </form><!-- End of form -->

                <h3>Change Password</h3>
                <!-- Echoes the success or error message -->
                <?php 
                    if($password_message == "") { ?>
                        <h4 class="message" style="color:#3B3B58;">Change your current password with a better one here.</h4><?php 
                    } 
                    else if ($password_message == "Password has been changed!<br><br>") { ?>
                        <h4 class="message">Password has been changed!</h4><?php 
                    } 
                    else if ($password_message == "Sorry, your password must be greater than 4 characters<br><br>") { ?>
                        <h4 class="message" style="color:#F58F8F;">Sorry, your password must be greater than 4 characters</h4><?php 
                    } 
                    else if ($password_message == "Your two new passwords need to match!<br><br>") { ?>
                        <h4 class="message" style="color:#F58F8F;">Your two new passwords need to match!</h4><?php 
                    }
                    else if ($password_message == "The old password is incorrect! <br><br>") { ?>
                        <h4 class="message" style="color:#F58F8F;">The old password is incorrect!</h4><?php 
                    } 
                ?>
                
                <form action="settings.php" method="POST"><!-- Start of other form -->
                    <div class="card">
                        <div>
                            <h3>Old Password</h3>
                            <input type="password" name="old_password" id="settings_input" required><!-- To enter old password -->
                        </div>
                        <div>
                            <h3>New Password</h3>
                            <input type="password" name="new_password_1" id="settings_input" required><!-- To enter new password -->
                        </div>
                    </div>
                    <div class="card">
                        <div>
                            <h3>New Password Again</h3>
                            <input type="password" name="new_password_2" id="settings_input" required><!-- To enter new password again -->
                        </div>
                        <div>
                            <br>
                            <input type="submit" name="update_password" id="save_details" value="Update Password" class="settings_submit"><!-- Submits the form -->
                        </div>
                    </div>
                    
                </form><!-- End of other form -->
            </div>
            <div>
                <h3>Close Account</h3>
                <form action="settings.php" method="POST"><!-- Start of last form -->
                    <input type="submit" name="close_account" id="close_account" value="Close Account" class="settings_submit"><!-- Submits the form -->
                </form><!-- End of last form -->
            </div>            
        </div>
        <footer class="main-footer"><p>Copyright ©️ 2021 Bath Spa University | All Rights Reserved</p></footer>
    </div>
    <script src="../../assets/js/imagePreview.js"></script><!-- For loading image preview -->
    <script src="../../assets/js/post.js"></script><!-- For Post button toggles -->
	<script src="../../assets/js/loader.js"></script><!-- For the loader wrapper to fadeOut -->
    <script src="../../assets/js/nav-bar.js"></script><!-- For the hamburger bar -->
</body>
</html>