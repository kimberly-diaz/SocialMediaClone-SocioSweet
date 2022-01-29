<?php
    if(isset($_POST["reset_password_button"])) {
        $selector = $_POST["selector"]; //Runs the select statement
        $validator = $_POST["validator"]; //Validates the token
        $password = $_POST["new_password"];
        $password2 = $_POST["new_password2"];

        if(empty($password) || empty($password2)) { //If the input is empty
            header("Location: ../../html/landing-pages/create-new-password.php?selector=" . $selector . "&validator=" . $validator . "?newpwd=empty");
            exit();
        }
        else if ($password != $password2) { //If the passwords are not the same
            header("Location: ../../html/landing-pages/create-new-password.php?selector=" . $selector . "&validator=" . $validator . "?newpwd=pwdnotsame");
            exit(); 
        }

        $currentDate = date("U");

        require '../../config/config.php';

        $sql = "SELECT * FROM pwdreset WHERE pwdResetSelector=? AND pwdResetExpires >= ?"; //Checks the database
        $stmt = mysqli_stmt_init($con);

        if (!mysqli_stmt_prepare($stmt, $sql)) { 
            echo "There was an error!"; //Error message
            exit();
        }
        else {
            mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate); //Checking the token
            mysqli_stmt_execute($stmt); //Executes the statement

            $result = mysqli_stmt_get_result($stmt);
            if (!$row = mysqli_fetch_assoc($result)) { //There were no rows
                echo "You need to re-submit your reset request."; //Error message
                exit();
            }
            else { //If there were rows from the database
                $tokenBin = hex2bin($validator); //Changes the token from hex to bin
                $tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]); //Checks if the token is the same as in the database & gets the row by name

                if ($tokenCheck === false) {
                    echo "You need to re-submit your reset request."; //Error message
                    exit();
                }
                else if ($tokenCheck === true) {
                    $tokenEmail = $row['pwdResetEmail'];

                    $sql = "SELECT * FROM users WHERE email=?;";
                    $stmt = mysqli_stmt_init($con);

                    if (!mysqli_stmt_prepare($stmt, $sql)) { //Checking for an error
                        echo "There was an error!";
                        exit();
                    }
                    else {
                        mysqli_stmt_bind_param($stmt, "s", $tokenEmail); //Binds the email with the placeholder
                        mysqli_stmt_execute($stmt);

                        $result = mysqli_stmt_get_result($stmt);
                        if (!$row = mysqli_fetch_assoc($result)) { //There were no rows
                            echo "There was an error!";
                            exit();
                        }
                        else { //If there were rows from the database
                            //Updates the password in the users table
                            $sql = "UPDATE users SET password=? WHERE email=?";
                            
                            if (!mysqli_stmt_prepare($stmt, $sql)) { //Checking for an error
                                echo "There was an error!";
                                exit();
                            }
                            else {
                                $newPwdHash = md5($password);
                                mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail); //Updates the password in the database
                                mysqli_stmt_execute($stmt);

                                $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
                                $stmt = mysqli_stmt_init($con);

                                if (!mysqli_stmt_prepare($stmt, $sql)) { //Checking for an error
                                    echo "There was an error!";
                                    exit();
                                }
                                else {
                                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                    mysqli_stmt_execute($stmt);
                                    header("Location: ../../html/landing-pages/login.php?newpwd=passwordupdated");
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    else {
        header("location:../../html/landing-pages/login.php"); //Else send them back to the login page
    }
?>