<?php

error_reporting(0);
require "../Connection/conn.php";

$question = mysqli_real_escape_string($conn, $_POST['question']);
$qType = $_POST['questionType'];
$difficulty = $_POST['difficulty'];
$answer = mysqli_real_escape_string($conn, $_POST['answer']);
$answerGiven = mysqli_real_escape_string($conn, $_POST['answerGiven']);
$profile = mysqli_real_escape_string($conn, $_POST['profile']);
$username = mysqli_real_escape_string($conn, $_POST['userName']);
$roundID = $_POST['roundID'];
$correct = $_POST['correct'];
$questionID = -1;
$profileID = -1;

$dupliSQL = "SELECT Question FROM 7057_questions_asked WHERE Question='$question'";
$dupliCheck = mysqli_query($conn, $dupliSQL) or die(mysqli_error($conn));

$getProfileNameSQL = "SELECT ProfileID FROM 7057_profiles WHERE Username='$username' AND ProfileName='$profile'";
$getProfileName = mysqli_query($conn, $getProfileNameSQL) or die(mysqli_error($conn));
    
    while ($row = mysqli_fetch_assoc($getProfileName)) {
        $profileID = $row['ProfileID'];
    }

if (mysqli_num_rows($dupliCheck) > 0) {
    // Get the ID of the question already asked
    $getQIDSQL = "SELECT QuestionID FROM 7057_questions_asked WHERE Question='$question'";
    $getQID = mysqli_query($conn, $getQIDSQL) or die(mysqli_error($conn));
    
    while ($row = mysqli_fetch_assoc($getQID)) {
        $questionID = $row['QuestionID'];
    }
} else {
    // Add question to DB
    $addQSQL = "INSERT INTO 7057_questions_asked (Question,Answer,QuestionType,Difficulty) VALUES ('".$question."','".$answer."','".$qType."','".$difficulty."')";
    $addQ = mysqli_query($conn, $addQSQL) or die(mysqli_error($conn));
    
    // Find the QuestionID for the newly added question
    $getQIDSQL = "SELECT QuestionID FROM 7057_questions_asked WHERE Question='$question'";
    
    $getQID = mysqli_query($conn, $getQIDSQL) or die(mysqli_error($conn));
    
    while ($row = mysqli_fetch_assoc($getQID)) {
        $questionID = $row['QuestionID'];
    }

}

// Add question & result against users profile in DB
    $addQToProfileSQL = "INSERT INTO 7057_answered (ProfileID,QuestionID,AnswerGiven,Correct,RoundID) VALUES ('".$profileID."','".$questionID."','".$answerGiven."','".$correct."','".$roundID."')";
    
    $addQToProfile = mysqli_query($conn, $addQToProfileSQL) or die(mysqli_error($conn));
    

// does question already exist (SELECT)
// If not, add question (INSERT)
// (SELECT) to get for QuestionID
// to put the record into the other table
// INSERT into answer table with QuestionID & Whether right/wrong

?>