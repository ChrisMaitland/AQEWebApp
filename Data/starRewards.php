<?php

error_reporting(0);
require "../Connection/conn.php";

$username = mysqli_real_escape_string($conn, $_POST['userName']);
$profile = mysqli_real_escape_string($conn, $_POST['profile']);

$checkname = "SELECT ProfileID FROM 7057_profiles WHERE Username='$username' AND ProfileName = '$profile'";
$checkresult = mysqli_query($conn, $checkname) or die(mysqli_error($conn));

if (mysqli_num_rows($checkresult) > 0 ) {
    
    while ($row = mysqli_fetch_assoc($checkresult)) {
        $profileID = $row['ProfileID'];
    }
    
    $getStarsSQL = "SELECT StarGained, COUNT(1) AS Total FROM 7057_round_tracker WHERE ProfileID='$profileID' AND StarGained IS NOT NULL AND StarGained != '' GROUP BY StarGained";
    $getStars = mysqli_query($conn, $getStarsSQL) or die(mysqli_error($conn));

    $response = array();
    
    if (mysqli_num_rows($getStars) > 0 ) {
        
        while($row = mysqli_fetch_assoc($getStars)) {
            
            $response['7057_round_tracker'][] = $row;
            
        }
        
    } else {
        
        echo '{"message":"No stars found!"}';
        
    }
    
    echo json_encode($response);
    
    mysqli_close($conn);
    
}