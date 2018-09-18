<?php

error_reporting(0);
require '../Connection/conn.php';

$username = mysqli_real_escape_string($conn, $_POST["name"]);
$password = $_POST["password"];

// Using BINARY to ensure case sensitivity
$sql = "SELECT * FROM 7057_users WHERE BINARY Username='$username' AND BINARY Password='$password'";

$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$response = array();

if (mysqli_num_rows($result) > 0) {

    while($row = mysqli_fetch_assoc($result)) {
        $response = array("Username"=>$row['Username'],"Email"=>$row['Email']);
        
    }
} else {
    $response = null;
}

echo json_encode(array("7057_users"=>$response));

mysqli_close($conn);

?>