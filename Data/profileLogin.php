<?php

error_reporting(0);
require "../Connection/conn.php";

$username = mysqli_real_escape_string($conn, $_POST["username"]);
$profilename = mysqli_real_escape_string($conn, $_POST["profileName"]);
if (isset($_POST["pin"])) {
    $pin = $_POST["pin"];


    $checkname = "SELECT * FROM 7057_profiles WHERE Username='$username' AND ProfileName = '$profilename' AND PIN='$pin'";
    $checkresult = mysqli_query($conn, $checkname) or die(mysqli_error($conn));
    
    if (mysqli_num_rows($checkresult) > 0) {
            echo '{"message":"PIN accepted!"}';
        } else {
            echo '{"message":"PIN incorrect!"}';
        }
    }
    mysqli_close($conn);
    ?>