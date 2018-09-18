<?php

    session_start();
    include("../Connection/conn.php");
    
    $email = $_SESSION['7057_email'];
    $role = $_SESSION['7057_role'];
    $name = $_SESSION['7057_username'];
    
    if (!isset($email)) {
        header('Location:logout.php');
    }
    
    if (isset($_POST['deleteprofile'])) {
        $deleteprofile = $_POST['deleteprofile'];
        
        $deletecheckingSQL = "SELECT Username, ProfileName FROM 7057_profiles WHERE ProfileID = '$deleteprofile'";
        $deletechecking = mysqli_query($conn, $deletecheckingSQL) or die(mysqli_error($conn));
        if (mysqli_num_rows($deletechecking) > 0 ) {
            
            $deleteRoundsSQL = "DELETE FROM 7057_round_tracker WHERE ProfileID='$deleteprofile'";
            $deleteRounds = mysqli_query($conn, $deleteRoundsSQL) or die(mysqli_error($conn));

            $deleteAnswersSQL = "DELETE FROM 7057_answered WHERE ProfileID='$deleteprofile'";
            $deleteAnswers = mysqli_query($conn, $deleteAnswersSQL) or die(mysqli_error($conn));
            
            $deleteAchievementsSQL = "DELETE FROM 7057_achievements_won WHERE ProfileID='$deleteprofile'";
            $deleteAchievements = mysqli_query($conn, $deleteAchievementsSQL) or die(mysqli_error($conn));

            $deleteuserSQL = "DELETE FROM 7057_profiles WHERE ProfileID = '$deleteprofile'";
            $deleteuser = mysqli_query($conn, $deleteuserSQL) or die(mysqli_error($conn));
            mysqli_close($conn);
            header('Location:users.php?deleted=true');
        
        }
    } 
    
    if (isset($_POST['update'])) {
    $profileid = $_POST['profileid'];
    $updatename = $_POST['updatename'];
    $updatepin = md5($_POST['updatepin']);
    }
    
        $updatecheckuserSQL = "SELECT * FROM 7057_profiles WHERE ProfileID = '$profileid'";
    
    $userquery = mysqli_query($conn, $updatecheckuserSQL) or die(mysqli_error($conn));
    
    if (isset($_POST['update'])) {
        if (mysqli_num_rows($userquery) > 0 ) {
            if ($updatepin == "" || $updatepin == null || strlen($_POST['updatepin']) != 4) {
                $updateuserSQL = "UPDATE 7057_profiles SET ProfileName = '$updatename' WHERE ProfileID='$profileid'";
            } else {
                $updateuserSQL = "UPDATE 7057_profiles SET ProfileName = '$updatename', PIN='$updatepin' WHERE ProfileID='$profileid'";
            }
            $updateuser = mysqli_query($conn, $updateuserSQL) or die(mysqli_error($conn));
            mysqli_close($conn);
            header('Location:users.php?success=true');
        }
    }
    
    mysqli_close($conn);
    

