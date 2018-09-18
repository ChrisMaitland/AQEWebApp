<?php

    require "Connection/conn.php";
    
    $email = $_POST['Email'];
    
    $checkemailSQL = "SELECT Email FROM 7057_users WHERE Email='$email'";
    $checkemail = mysqli_query($conn, $checkemailSQL) or die(mysqli_error($conn));
    
    if (mysqli_num_rows($checkemail) == 0 ) {
        header('Location:forgotten.php?exists=false');
    } else {
       //send reset email
        //generate this randomly
        $resetKey = md5(uniqid(rand(), true));
        echo "$resetKey";
        $link = "http://cmaitland02.web.eeecs.qub.ac.uk/AQEStarCaptain/resetPassword.php?email=$email&reset=$resetKey";
        //save to DB table with Email, random and timestamp
        echo "\n$link";
        $querypostSQL = "INSERT INTO 7057_resetpw (Email, ResetKey) VALUES ('$email','$resetKey')";
        $querypost = mysqli_query($conn, $querypostSQL) or die(mysqli_error($conn));
        
        // the message
        $headers = 'From: <DoNotReply@AQEStarCaptain.com>' ."\r\n";
        $headers .= "Content-type-type: text/html";
        $subject = "Password Reset Request - AQE Star Captain";
        
        $msg = "Dear User,\n\nPlease click the following link to reset your password:\n\n" .$link."\n\nThanks from AQE Star Captain.\n ";
        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg,70);

        // send email
        mail($email,$subject,$msg,$headers);
        header("Location:index.php");
        
    }
    mysqli_close($conn);
?>