<?php

error_reporting(0);
require "../Connection/conn.php";

$username = $_POST["username"];

    $checkname = "SELECT * FROM 7057_profiles WHERE Username='$username'";
    
    $checkresult = mysqli_query($conn, $checkname) or die(mysqli_error($conn));
    
    $response = array();
    
    if (mysqli_num_rows($checkresult) > 0 ) {
        
        while($row = mysqli_fetch_assoc($checkresult)) {
            
            $response['7057_profiles'][] = $row;
            
        }
        
    } else {
        
        echo '{"message":"No profiles found!"}';
        
    }
    
    echo json_encode($response);
    
    mysqli_close($conn);
    
    ?>