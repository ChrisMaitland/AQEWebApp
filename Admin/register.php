<?php

error_reporting(0);
require "../Connection/conn.php";

$name = mysqli_real_escape_string($conn, $_POST["name"]);
$password = md5($_POST["password"]);
$email = $_POST["email"];

$checkduplicate = "SELECT Email FROM 7057_users WHERE BINARY Username='$name'";
$checkresult = mysqli_query($conn, $checkduplicate) or die(mysqli_error($conn));

if (mysqli_num_rows($checkresult) > 0 ) {
        echo '{"message":"Username already exists"}';
    } else {
        $sql = "INSERT INTO 7057_users (Username, Password, Email, Role) VALUES ('".$name."', '".$password."', '".$email."', 'User');";
        $sqlResult = mysqli_query($conn, $sql) or die(mysqli_error($conn));
        
        if(!$sqlResult){
            echo '{"message":"Unable to save the data to the database."}';
        }
    }
    header('location:../registration.php');
    mysqli_close($conn);

?>