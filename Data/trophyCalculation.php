<?php

include "../Connection/conn.php";

$retryRound = $_POST["retryRound"];
$username = $_POST["userName"];
$profile = $_POST["profileName"];

// find the Users ProfileID
$findidSQL = "SELECT ProfileID FROM 7057_profiles WHERE Username='$username' AND ProfileName='$profile'";
$findid = mysqli_query($conn, $findidSQL) or die(mysqli_error($conn));
    if (mysqli_num_rows($findid) > 0 ) {
        while($row = mysqli_fetch_assoc($findid)) {
            $profileID = $row['ProfileID'];
        }
    }

//Achievement Table will have:
//Achievement
//Description
//Type: TotalCorrect/TotalDays/CorrectStreak/DaysStreak/TotalRounds/Retry
//QuestionType: English/ArithmeticAddicion/.../ALL/""
//GreaterThanCriteria: 5/10/15/20

//Get any achievements not yet received
$achievementsOutstandingSQL = "SELECT * FROM 7057_achievements
WHERE AchievementName NOT IN (
SELECT AchievementName FROM 7057_achievements_won
WHERE ProfileID = '$profileID')";
$achievementsOutstanding = mysqli_query($conn, $achievementsOutstandingSQL) or die(mysqli_error($conn));

//if > 0
if (mysqli_num_rows($achievementsOutstanding) > 0 ) {
	
	$totalDays = -1;
	$totalRounds = -1;
	
	//for each achievement not yet received i.e. above query
	while ($row = mysqli_fetch_assoc($achievementsOutstanding))
	{
		$achievement = $row["AchievementName"];
		$description = $row["AchievementDesc"];
		$type = $row["Type"];
		$questionType = $row["QuestionType"];
		$greaterThanCriteria = $row["GreaterThanCriteria"];
                
		if ($type == "TotalDays") {
			
			//Total Days
			//if $totalDays has not yet been calculated
			if ($totalDays == -1) {
				$totaldDaysCheckSQL = "SELECT COUNT(DISTINCT SUBSTR(RoundID, 1, 8)) AS Count FROM 7057_round_tracker
				WHERE ProfileID = '$profileID'";
				$totaldDaysCheck = mysqli_query($conn, $totaldDaysCheckSQL) or die(mysqli_error($conn));
                                $totalDaysResult = mysqli_fetch_assoc($totaldDaysCheck);
				$totalDays = $totalDaysResult['Count'];
                        }
                        if ($totalDays >= $greaterThanCriteria) {
                            //Insert into Won_Table
                            $insertWonQuerySQL = "INSERT INTO 7057_achievements_won (ProfileID, AchievementName) VALUES ('$profileID', '$achievement')";
                            $insertWonQuery = mysqli_query($conn, $insertWonQuerySQL) or die(mysqli_error($conn));
                            //TODO: Add to output JSON to inform user what new trophies have been achieved
                        }
		} else if ($type == "DaysStreak") {

			//Days Streak 
			$targetStreak = $greaterThanCriteria;
                        $dayDifference = $targetStreak - 1;
                        
                        $date = date('Ymd', strtotime('-$dayDifference days'));//today - ($targetStreak - 1) days as YYYYMMDD;
			$daysStreakQuerySQL = "SELECT COUNT(DISTINCT SUBSTR(RoundID, 1, 8)) AS Count FROM 7057_round_tracker
			WHERE ProfileID = '$profileID'
			AND SUBSTR(RoundID, 1, 8) >= '$date'";
			$daysStreakQuery = mysqli_query($conn, $daysStreakQuerySQL) or die(mysqli_error($conn));
			$dayStreakResult = mysqli_fetch_assoc($daysStreakQuery);
			$count = $dayStreakResult['Count'];
                        if ($count == $targetStreak) {
                            //Insert into Won_Table
                            $insertStreakWonQuerySQL = "INSERT INTO 7057_achievements_won (ProfileID, AchievementName) VALUES ('$profileID', '$achievement')";
                            $insertStreakWonQuery = mysqli_query($conn, $insertStreakWonQuerySQL) or die(mysqli_error($conn));
                            //TODO: Add to output JSON to inform user what new trophies have been achieved
                        }
                        
		} else if ($type == "TotalRounds") {
			
			//Total Rounds
			//if $totalRounds has not yet been calculated
			if ($totalRounds == -1) {
				$totalRoundsQuerySQL = "SELECT COUNT(1) AS Count FROM 7057_round_tracker WHERE ProfileID = '$profileID'";
				$totalRoundsQuery = mysqli_query($conn, $totalRoundsQuerySQL) or die(mysqli_error($conn));
				$totalRoundsResult = mysqli_fetch_assoc($totalRoundsQuery);
                                $totalRounds = $totalRoundsResult['Count'];
                                if ($totalRounds >= $greaterThanCriteria) {
                                    //Insert into Won_Table
                                    $insertRoundsWonQuerySQL = "INSERT INTO 7057_achievements_won (ProfileID, AchievementName) VALUES ('$profileID', '$achievement')";
                                    $insertRoundsWonQuery = mysqli_query($conn, $insertRoundsWonQuerySQL) or die(mysqli_error($conn));
                                    //TODO: Add to output JSON to inform user what new trophies have been achieved
                                }
                                
                            }
			
			
		} else if ($type == "Retry") {
			
			//Retry
			//if round just completed was a retry then give achievement
			if($retryRound == 'true') {
                            //Insert into Won_Table
                            $retryRoundsWonQuerySQL = "INSERT INTO 7057_achievements_won (ProfileID, AchievementName) VALUES ('$profileID', '$achievement')";
                            $retryRoundsWonQuery = mysqli_query($conn, $retryRoundsWonQuerySQL) or die(mysqli_error($conn));
                        
                            //TODO: Add to output JSON to inform user what new trophies have been achieved
			}
			
		} else if ($type == "TotalCorrect") {
			
			//TotalCorrect
				$totalCorrectQuerySQL = "SELECT COUNT(1) AS Count FROM 7057_answered ans
				INNER JOIN 7057_questions_asked qus
				ON ans.QuestionID = qus.QuestionID
				WHERE ans.ProfileID = '$profileID'
                                AND ans.Correct = '1'";
				if ($questionType != "ALL") {
					$totalCorrectQuerySQL = $totalCorrectQuerySQL . "AND qus.QuestionType = '$questionType'";
				}
                                $totalCorrectQuery = mysqli_query($conn, $totalCorrectQuerySQL) or die(mysqli_error($conn));
                                $totalCorrectResult = mysqli_fetch_assoc($totalCorrectQuery);
                                $totalCorrect = $totalCorrectResult['Count'];
			if ($totalCorrect >= $greaterThanCriteria) {
                            //Insert into Won_Table
                            $insertTotalWonQuerySQL = "INSERT INTO 7057_achievements_won (ProfileID, AchievementName) VALUES ('$profileID', '$achievement')";
                            $insertTotalWonQuery = mysqli_query($conn, $insertTotalWonQuerySQL) or die(mysqli_error($conn));

                            //TODO: Add to output JSON to inform user what new trophies have been achieved
			}
			
		} else if ($type == "CorrectStreak") {
			
			//CorrectStreak
                        $maxStreak = 0;
                        
                        //get current correct answer streak - select all count of correct answers since last incorrect answer
			$correctStreakQuerySQL = "SELECT COUNT(1) AS Count FROM 7057_answered ans
			INNER JOIN 7057_questions_asked qus
			ON ans.QuestionID = qus.QuestionID
			WHERE ans.ProfileID = '$profileID'
                        AND ans.Correct = '1'";
			if ($questionType != "ALL") {
				$correctStreakQuerySQL = $correctStreakQuerySQL . "AND qus.QuestionType = '$questionType'";
			}
			$correctStreakQuerySQL = $correctStreakQuerySQL . "AND ans.AnswerID > (
			SELECT IFNULL(MAX(AnswerID), 0) FROM 7057_answered ans
			INNER JOIN 7057_questions_asked qus
			ON ans.QuestionID = qus.QuestionID
			WHERE ans.ProfileID = '$profileID'";
			if ($questionType != "ALL") {
				$correctStreakQuerySQL = $correctStreakQuerySQL . "AND qus.QuestionType = '$questionType'";
			}
			$correctStreakQuerySQL = $correctStreakQuerySQL . "AND ans.Correct = '0')";
			$correctStreakQuery = mysqli_query($conn, $correctStreakQuerySQL) or die(mysqli_error($conn));
                        $correctStreakResult = mysqli_fetch_assoc($correctStreakQuery);
                        $streak = $correctStreakResult['Count'];
                        
                        if ($streak > $maxStreak) {
                            $maxStreak = $streak;
                        }
                        
                        //in case where there were incorrect answers in the round, 
                        //check for any earlier streaks either within this round or that started in a previous round and finished in this round
                        $incorrectAnswerIDsQuerySQL = "SELECT AnswerID FROM 7057_answered
                            WHERE ProfileID = '$profileID'
                            AND RoundID = (
                            SELECT MAX(RoundID) FROM 7057_answered
                            WHERE ProfileID = '$profileID')
                            AND Correct = '0'";
                        
                        $incorrectAnswerIDsQuery = mysqli_query($conn, $incorrectAnswerIDsQuerySQL) or die(mysqli_error($conn));
                        if (mysqli_num_rows($incorrectAnswerIDsQuery) > 0 ) {
                            //for each incorrect answer in the round, check for a streak before it
                            while($row2 = mysqli_fetch_assoc($incorrectAnswerIDsQuery)) {
                                $incorrectAnswerID = $row2['AnswerID'];
                                
                                //same query as for current streak but with additional restriction based on the selected incorrect answer
                                $correctStreakQuerySQL = "SELECT COUNT(1) AS Count FROM 7057_answered ans
                                INNER JOIN 7057_questions_asked qus
                                ON ans.QuestionID = qus.QuestionID
                                WHERE ans.ProfileID = '$profileID'
                                AND ans.Correct = '1'";
                                if ($questionType != "ALL") {
                                        $correctStreakQuerySQL = $correctStreakQuerySQL . "AND qus.QuestionType = '$questionType'";
                                }
                                //below line added to use the given incorrect answer ID as a boundary
                                $correctStreakQuerySQL = $correctStreakQuerySQL . "AND ans.AnswerID < '$incorrectAnswerID'";
                                $correctStreakQuerySQL = $correctStreakQuerySQL . "AND ans.AnswerID > (
                                SELECT IFNULL(MAX(AnswerID), 0) FROM 7057_answered ans
                                INNER JOIN 7057_questions_asked qus
                                ON ans.QuestionID = qus.QuestionID
                                WHERE ans.ProfileID = '$profileID'";
                                if ($questionType != "ALL") {
                                        $correctStreakQuerySQL = $correctStreakQuerySQL . "AND qus.QuestionType = '$questionType'";
                                }
                                //below line added to use the given incorrect answer ID as a boundary
                                $correctStreakQuerySQL = $correctStreakQuerySQL . "AND ans.AnswerID < '$incorrectAnswerID'";
                                $correctStreakQuerySQL = $correctStreakQuerySQL . "AND ans.Correct = '0')";
                                $correctStreakQuery = mysqli_query($conn, $correctStreakQuerySQL) or die(mysqli_error($conn));
                                $correctStreakResult = mysqli_fetch_assoc($correctStreakQuery);
                                $streak = $correctStreakResult['Count'];
                                
                                if ($streak > $maxStreak) {
                                    $maxStreak = $streak;
                                }
                                
                            }
                        }
                        
			if ($maxStreak >= $greaterThanCriteria) {
                            //Insert into Won_Table
                            $insertCorrectStreakQuerySQL = "INSERT INTO 7057_achievements_won (ProfileID, AchievementName) VALUES ('$profileID', '$achievement')";
                            $insertCorrectStreakQuery = mysqli_query($conn, $insertCorrectStreakQuerySQL) or die(mysqli_error($conn));
				//TODO: Add to output JSON to inform user what new trophies have been achieved
			}
			
		}

	}
	
	//TODO: echo $json_response;
	
} else {

    echo "{'message':'All trophies have already been achieved'}";

}
?>