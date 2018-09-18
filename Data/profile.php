<?php

error_reporting(0);
require "../Connection/conn.php";

$username = mysqli_real_escape_string($conn, $_POST["name"]);
$profilename = mysqli_real_escape_string($conn, $_POST["profileName"]);
if (isset($_POST["pin"])) {
    $pin = $_POST["pin"];


    $checkname = "SELECT ProfileName FROM 7057_profiles WHERE Username='$username' AND ProfileName = '$profilename'";
    
    $checkresult = mysqli_query($conn, $checkname) or die(mysqli_error($conn));
    
    $profileCount = "SELECT ProfileName FROM 7057_profiles WHERE Username='$username'";
    $profileCountResult = mysqli_query($conn, $profileCount) or die(mysqli_error($conn));
        
        if (mysqli_num_rows($checkresult) > 0 ) {
            echo '{"message":"Profile already exists!"}';
        } else if (mysqli_num_rows($profileCountResult) >= 4 ) {
            echo '{"message":"4 Profiles already created. Please delete one before creating another!"}';
        } else {

            $addProfile = "INSERT INTO 7057_profiles (Username,ProfileName,Rating,PIN,StarCoins) VALUES ('".$username."','".$profilename."','1','$pin','0');";
            $addProfileResult = mysqli_query($conn, $addProfile) or die(mysqli_error($conn));

            if(!$addProfileResult){
                echo '{"message":"Unable to add the profile."}';
            }
        }
    }
    
    mysqli_close($conn);
    
    ?>