<?php

error_reporting(0);
require "../Connection/conn.php";

$username = mysqli_real_escape_string($conn, $_POST["userName"]);
$profilename = mysqli_real_escape_string($conn, $_POST["profileName"]);
if (isset($_POST["password"])) {
    $password = $_POST["password"];

    $getProfileSQL = "SELECT ProfileID FROM 7057_profiles WHERE Username='$username' AND ProfileName='$profilename'";
    $getProfile = mysqli_query($conn, $getProfileSQL) or die(mysqli_error($conn));

    if (mysqli_num_rows($getProfile) > 0) {
        while ($row = mysqli_fetch_assoc($getProfile)) {
            $profileID = $row['ProfileID'];
        }
        
        $checkPWSQL = "SELECT * FROM 7057_users WHERE Username='$username' AND Password='$password'";
        $checkPW = mysqli_query($conn, $checkPWSQL) or die(mysqli_error($conn));
        
        if (mysqli_num_rows($checkPW) > 0 ) {
        
            $deleteRoundsSQL = "DELETE FROM 7057_round_tracker WHERE ProfileID='$profileID'";
            $deleteRounds = mysqli_query($conn, $deleteRoundsSQL) or die(mysqli_error($conn));

            $deleteAnswersSQL = "DELETE FROM 7057_answered WHERE ProfileID='$profileID'";
            $deleteAnswers = mysqli_query($conn, $deleteAnswersSQL) or die(mysqli_error($conn));
            
            $deleteAchievementsSQL = "DELETE FROM 7057_achievements_won WHERE ProfileID='$profileID'";
            $deleteAchievements = mysqli_query($conn, $deleteAchievementsSQL) or die(mysqli_error($conn));

            $deleteSQL = "DELETE FROM 7057_profiles WHERE ProfileID='$profileID'";
            $delete = mysqli_query($conn, $deleteSQL) or die(mysqli_error($conn));
        
            echo '{"message":"Profile deleted!"}';
        } else {
            echo '{"message":"Password incorrect, try again!"}';
        }
        
        
    } else {
        echo '{"message":"Profile not found!"}';
    }
}

?>