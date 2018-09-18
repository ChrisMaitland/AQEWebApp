<?php
session_start();

include "../Connection/conn.php";

$email = $_POST["Email"];
$pw = md5($_POST["pw"]);


$checkuser="SELECT * FROM 7057_users WHERE Email='$email' AND Password='$pw'";
$logincheck=mysqli_query($conn,$checkuser) or die(mysqli_error($conn));

if (mysqli_num_rows($logincheck) > 0) {
    while ($row = mysqli_fetch_assoc($logincheck)) {
        $_SESSION['7057_email'] = $row['Email'];
        $_SESSION['7057_role'] = $row['Role'];
        $_SESSION['7057_username'] = $row['Username'];
        header("Location:userhub.php");
    }
} else {
    header("Location:../index.php?loginerror=true");
}
mysqli_close($conn);
