<?php

error_reporting(0);
require "../Connection/conn.php";

$username = mysqli_real_escape_string($conn, $_POST['userName']);
$profile = mysqli_real_escape_string($conn, $_POST['profileName']);
$oldrating = $_POST['oldRating'];
$newrating = $_POST['newRating'];
$pointsavailable = $_POST['pointsAvailable'];
$pointsachieved = $_POST['pointsAchieved'];
$roundid = $_POST['roundID'];
$star = $_POST['star'];

if (isset($_POST['roundID'])) {

    $getProfileNameSQL = "SELECT ProfileID, StarCoins FROM 7057_profiles WHERE Username='$username' AND ProfileName='$profile'";
    $getProfileName = mysqli_query($conn, $getProfileNameSQL) or die(mysqli_error($conn));
    
    while ($row = mysqli_fetch_assoc($getProfileName)) {
        $profileID = $row['ProfileID'];
        $oldCoins = $row['StarCoins'];
    }
    
    $coins = $oldCoins + $pointsachieved;

    // Add round to DB
    $addQSQL = "INSERT INTO 7057_round_tracker (RoundID,Username,ProfileID,OldRating,NewRating,PointsAvailable,PointsAchieved,StarGained) VALUES ('".$roundid."','".$username."','".$profileID."','".$oldrating."','".$newrating."','".$pointsavailable."','".$pointsachieved."','".$star."')";
    
    $addQ = mysqli_query($conn, $addQSQL) or die(mysqli_error($conn));
    

    // Update rating and coins in DB
    $updateRatingSQL = "UPDATE 7057_profiles SET Rating='$newrating', StarCoins='$coins' WHERE ProfileID='$profileID'";
    $updateRating = mysqli_query($conn, $updateRatingSQL) or die(mysqli_error($conn));

    echo '{"message":"Round Saved"}';
}
?>