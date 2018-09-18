<?php

error_reporting(0);
require "../Connection/conn.php";

$username = $_POST["username"];
$profile = $_POST["profile"];

// find the Users ProfileID
    $findidSQL = "SELECT ProfileID FROM 7057_profiles WHERE Username='$username' AND ProfileName='$profile'";
    $findid = mysqli_query($conn, $findidSQL) or die(mysqli_error($conn));
    if (mysqli_num_rows($findid) > 0 ) {
        while($row = mysqli_fetch_assoc($findid)) {
            $profileid = $row['ProfileID'];
        }
    }

    // find the Users Achievements, if any
    $checkidSQL = "SELECT won.AchievementName, ach.AchievementDesc FROM 7057_achievements_won won INNER JOIN 7057_achievements ach ON won.AchievementName=ach.AchievementName WHERE ProfileID='$profileid'";
    
    $checkid = mysqli_query($conn, $checkidSQL) or die(mysqli_error($conn));
    
    $response = array();
    
    if (mysqli_num_rows($checkid) > 0 ) {
        
        while($row = mysqli_fetch_assoc($checkid)) {
            
            $response['7057_achievements_won'][] = $row;
            
        }
        
    } else {
        
        echo '{"message":"No achievements found!"}';
        
    }
    
    echo json_encode($response);
    
    mysqli_close($conn);
    
    ?>