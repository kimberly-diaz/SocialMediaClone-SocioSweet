<?php
    // Requires & uses PHPMailer files to send reset email
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';
    require '../PHPMailer/src/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


    if(isset($_POST["reset_request_button"])) { //If the reset request button was clicked

        //Two tokens for safety from timing-attacks by hackers
        $selector = bin2hex(random_bytes(8)); //Checks the token in the database
        $token = random_bytes(32); //Authenticates the user

        $url = "http://localhost/SocioSweet/html/landing-pages/create-new-password.php?selector=" . $selector . "&validator=" . bin2hex($token); //Creates the custom link

        $expires = date("U") + 1800; //Expiry date is 1 hour from now

        require '../../config/config.php'; //Connection file

        $userEmail = $_POST["reset_email"]; //Get the email entered

        $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?;"; //Deletes any existing tokens
        $stmt = mysqli_stmt_init($con);

        if (!mysqli_stmt_prepare($stmt, $sql)) { //Checking for an error first
            echo "There was an error!";
            exit();
        }
        else {
            mysqli_stmt_bind_param($stmt, "s", $userEmail); //Replaces the ? by binding the user's email
            mysqli_stmt_execute($stmt); //Executes the statement
        }

        $sql = "INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?);"; //Inserts the data into the database
        $stmt = mysqli_stmt_init($con);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "There was an error!"; //Error message
            exit();
        }
        else {
            $hashedToken = password_hash($token, PASSWORD_DEFAULT); //Hashes the token for security
            mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires); //Creates a new token in the database
            mysqli_stmt_execute($stmt);
        }

        //Closes the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($con);

        //Creates the email
        $to = $userEmail;
        $subject = "Reset your password for SocioSweet.com";
        $message = '<p>We received a password reset request. The link to reset your password is below. If you did not make this request, you can ignore this email. Here is your password reset link: </br><a href="' . $url . '">' . $url . '</a></p>';

        // Sends it via PHPMailer
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = '465';
        $mail->Username = 'sociosweet.site@gmail.com'; //Gmail of site
        $mail->Password = 'sociosweet123@'; //Password of gmail
        $mail->Subject = $subject;
        $mail->setFrom('sociosweet.site@gmail.com');
        $mail->isHTML(true);
        $mail->Body = $message;
        $mail->addAddress($to);
        $mail->Send();

        $mail->smtpClose();

        header("Location: ../../html/landing-pages/reset-password.php?reset=success"); //Returns the user back to the page
    }
    else {
        header("location:../../html/landing-pages/login.php"); //Else send them back to the login page
    }
?>