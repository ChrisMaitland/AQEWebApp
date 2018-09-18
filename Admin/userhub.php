<?php

    session_start();
    include("../Connection/conn.php");
    
    $email = $_SESSION['7057_email'];
    $role = $_SESSION['7057_role'];
    $name = $_SESSION['7057_username'];
    
    if (!isset($email)) {
        header('Location:logout.php');
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>AQE User Hub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="../Style/style.css" />
    <link rel="icon" href="../Images/rainbowLogo.png">
    <script src="../JS/jquery.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    
    <script type="text/javascript">
        
        window.onload = function() { 
            document.getElementById('details_section').style.visibility='hidden';
        };
        
        google.charts.load('current', {'packages':['corechart']});
        
        function displayLine(inputData, title) {
            
            document.getElementById('details_section').style.visibility='visible';
            
            var data = google.visualization.arrayToDataTable(inputData);
            
            var options =   { title:title,
                                width:750,
                                height:500,
                                hAxis: {
                                    title: 'Round (Timestamp)',
                                    format: 'short'
                                }
                        };
            var chart = new google.visualization.LineChart(document.getElementById('details'));           
            
            chart.draw(data, options);
        }
        
        function displayPie(questionsData, title) {
            
            document.getElementById('details_section').style.visibility='visible';
            
            var data = google.visualization.arrayToDataTable(questionsData);
            
            var options =   {
                                title:title,
                                width:750,
                                height:500,
                                is3D: true
                        };
            var chart = new google.visualization.PieChart(document.getElementById('details'));
            
            chart.draw(data, options);
            
        }
    
        function displayRounds(selectedProfileName, oldrating, newrating, pointsachieved, pointsavailable, roundTimeStamp, questionsData) {
            document.getElementById('details_section').style.visibility='visible';
            content = "<h5>Click each row to view the Questions & Answers given within each round</h5><br />" +
                        "<table class='table table-hover'>" +
                        "<tr>" +
                        "<th>Profile</th>" +
                        "<th>Old Rating</th>" +
                        "<th>New Rating</th>" +
                        "<th>Points Achieved</th>" +
                        "<th>Points Available</th>" +
                        "<th>Time Stamp</th>" +
                        "</tr>";
            number = Object.keys(roundTimeStamp).length;
            for (var i = number - 1; i >= 0; i--) {
                content = content + "<tr data-toggle='collapse' data-target='#accordion"+i+"' class='clickable'>" +
                                    "<td><div class='name' style='min-width: 120px; display: inline-block;'>" + selectedProfileName + "</div></td>" + 
                                    "<td><span class=''>" + oldrating[i] + "</span></td><td><span class=''>" + newrating[i] + "</span></td>" + 
                                    "<td><span class='text-muted'>" + pointsachieved[i] + "</span></td><td><span class='text-muted'>" + pointsavailable[i] + "</span></td>" + 
                                    "<td><span class='badge'>" + roundTimeStamp[i] + "</span></td></tr>";
                content = content + "<tr><td colspan='6'><div id='accordion"+i+"' class='collapse'>" + displayQuestions(questionsData[i]) + "</div></td></tr>";
            }
            content = content + "</table>";
            document.getElementById('details').innerHTML = content;
            
        }
        
        function displayQuestions(questionData) {
            questionContent = "<table class='table table-hover'>" +
                        "<tr>" +
                        "<th>Difficulty</th>" +
                        "<th>Question Type</th>" +
                        "<th>Question</th>" +
                        "<th>Answer</th>" +
                        "<th>Answer Given</th>" +
                        "<th>Correct</th>" +
                        "</tr>";
            for (var j = 0; j < Object.keys(questionData).length; j++) {
                var details = questionData[j];
                questionContent = questionContent + "<tr><td><div class='name' style='min-width: 120px; display: inline-block;'>" + details.Difficulty + "</div></td>" + 
                                    "<td><span class=''>" + details.QuestionType + "</span></td><td><span class=''>" + details.Question + "</span></td>" + 
                                    "<td><span class='text-muted'>" + details.Answer + "</span></td><td><span class='text-muted'>" + details.AnswerGiven + "</span></td>" + 
                                    "<td><span class='badge'>"; 
                                    if(details.Correct == "1") {
                                        questionContent=questionContent+"Yes";
                                    } else {
                                        questionContent=questionContent+"No";
                                    }
                                    questionContent=questionContent+"</span></td></tr>";
            }
            questionContent = questionContent + "</table>";
            return questionContent;
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top foot ">

            <div class="container foot">

                <div class="navbar-header">

                    <a href="userhub.php" class="navbar-brand navbar-left ">
			<img id='imgbanner' src="../Images/rainbowLogo.png" class="img-responsive"></a>
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>                        
                        </button>
                </div>

                <div id="myNavbar" class="navbar-collapse collapse">
                    <div class="navbar-form navbar-right ">
                        
                            <?php
                                if ($role == 'Admin') {
                                    echo "<a href='users.php'><button type='button' class='btn btn-success' name='users'>Manage Users</button></a>";
                                    
                                    echo "<a href='questions.php'><button type='button' class='btn btn-success' name='questions'>Questions</button></a>";
                                } else if ($role == 'User') {
                                    echo "<a href='users.php'><button type='button' class='btn btn-success' name='users'>Manage Profiles</button></a>";
                                }
                            ?>

                            <a href='changePassword.php'><button type='button' class="btn btn-success" name='account'>Account</button></a>

                            <a href='logout.php'><button type='button' class="btn btn-success" name='logout'>Log Out</button></a>

                            <p><div id='pw'><a href="mailto:cmaitland02@qub.ac.uk"> Problem? Contact Admin! </a></div></p>

                    </div>
                </div><!--/.navbar-collapse -->

            </div>
    </nav>
<section class="s1">
</section>
<div class="container">
<section class="s2">
    <div class="row">
        <div class='col-xs-offset-1 col-xs-12 col-sm-9 col-md-9'>
            <div class="textoverflow">
            
            <h3>My Profiles</h3>
            <h5>Welcome <?php echo $name ?></h5>
            <h5>Use the buttons in the table below to see details and charts on each profile.</h5>
            <h5>These charts should help you assess what areas need extra focus on.</h5>
            <hr>
            <?php
            if($role == 'User') {
                $profilequerySQL = "SELECT * FROM 7057_profiles WHERE Username = '$name'";
                $nonefoundstring = "You do not currently have any Profiles.";
            } else if ($role == 'Admin') {
                $profilequerySQL = "SELECT * FROM 7057_profiles";
                $nonefoundstring = "There are not currently any Profiles.";
            }
            $profilequery = mysqli_query($conn, $profilequerySQL) or die(mysqli_error($conn));

            if (mysqli_num_rows($profilequery) > 0) {
                ?>
            <div class="table-responsive">
                <table id="profileList" class='table table-hover'>
                    <tr class='success'>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Rating</th>
                        <th>Star Coins</th>
                        <th>Rounds Played</th>
                        <th>Level Progress</th>
                        <th>Points</th>
                        <th>Questions Summary</th>
                        <th>Incorrect by Type</th>
                        <th>Correct by Type</th>
                        <th>Questions by Difficulty</th>
                    </tr>
                    <?php
                while ($row = mysqli_fetch_assoc($profilequery)) {
                    $pid = $row['ProfileID'];
                    $pname = $row['ProfileName'];
                    $rating = $row['Rating'];
                    $starcoins = $row['StarCoins'];
                    
                    echo "<tr><td>$pid</td>";
                    echo "<td><b>$pname</b></td><td>$rating<br/></td><td>$starcoins</td>";
            
                    $selectProfileSQL = "SELECT round.* FROM 7057_round_tracker round WHERE round.ProfileID ='$pid' ORDER BY round.RoundID ASC";
                    $selectProfile = mysqli_query($conn, $selectProfileSQL) or die(mysqli_error($conn));
                            if (mysqli_num_rows($selectProfile) > 0) {
                                
                                $data=array();
                        
                                $levelPlayedData=array();
                                $pointsData=array();
                                
                                $levelPlayedData[0][0] = "Round ID";
                                //$levelPlayedData[0][1] = "Old Rating";
                                $levelPlayedData[0][1] = "Rating";
                                
                                $pointsData[0][0] = "Round ID";
                                $pointsData[0][1] = "Points Achieved";
                                $pointsData[0][2] = "Points Available";
                                
                                $i = 0;
                                
                                $roundTimeStamp=array();
                                $newrating=array();
                                $oldrating=array();
                                $pointsavailable=array();
                                $pointsachieved=array();
                                $stargained=array();
                                
                                $questions = array();
                        
                                while($row2 = mysqli_fetch_assoc($selectProfile)) {
                                    // Round summaries
                                    $selectedpID[$i] = $row2['ProfileID'];
                                    $roundTimeStamp[$i] = $row2['RoundID'];
                                    $newrating[$i] = $row2['NewRating'];
                                    $oldrating[$i] = $row2['OldRating'];
                                    $pointsavailable[$i] = $row2['PointsAvailable'];
                                    $pointsachieved[$i] = $row2['PointsAchieved'];
                                    $stargained[$i] = $row2['StarGained'];
                                    
                                    // Get all questions asked & answers given in that round
                                    $questionsAnsweredSQL = "SELECT * FROM 7057_answered ans JOIN 7057_questions_asked qus ON ans.QuestionID = qus.QuestionID WHERE ans.ProfileID='$pid' AND ans.RoundID='$roundTimeStamp[$i]'";
                                    $questionsAnswered = mysqli_query($conn, $questionsAnsweredSQL) or die(mysqli_error($conn));
                                    
                                    $j = 0;
                                    
                                    while($row3 = mysqli_fetch_assoc($questionsAnswered)) {                                        
                                        $questions[$i][$j] = $row3;
                                        $j = $j + 1;
                                    }
                                    
                                    $i = $i + 1;
                                    
                                    $levelPlayedData[$i][0] = $row2["RoundID"];
                                    //$levelPlayedData[$i][1] = intval($row2["OldRating"]);
                                    $levelPlayedData[$i][1] = intval($row2["NewRating"]);
                                    
                                    $pointsData[$i][0] = $row2["RoundID"];
                                    $pointsData[$i][1] = intval($row2["PointsAchieved"]);
                                    $pointsData[$i][2] = intval($row2["PointsAvailable"]);
                                    
                                    
                                    
                                }
                                
                                $roundTimeStampArray = json_encode($roundTimeStamp);
                                $newratingArray = json_encode($newrating);
                                $oldratingArray = json_encode($oldrating);
                                $pointsavailableArray = json_encode($pointsavailable);
                                $pointsachievedArray = json_encode($pointsachieved);
                                $stargainedArray = json_encode($stargained);
                                
                                $encodedLevelPlayedData = json_encode($levelPlayedData);
                                $encodedPointsData = json_encode($pointsData);
                                
                                $questionsData = json_encode($questions);
                                
                                // Pie Chart for Question Types
                                $countQuestionTypesSQL ="SELECT quest.QuestionType, ans.Correct, COUNT(1) AS Count  FROM 7057_questions_asked quest INNER JOIN 7057_answered ans ON quest.QuestionID = ans.QuestionID WHERE ans.ProfileID='$pid' GROUP BY quest.QuestionType, ans.Correct ";
                                $countQuestionTypes = mysqli_query($conn, $countQuestionTypesSQL) or die(mysqli_error($conn));
                                if (mysqli_num_rows($countQuestionTypes) > 0) {

                                    $incorrectQuestionData=array();
                                    $correctQuestionData=array();
                                    $correctIncorrectSummaryData=array();
                                    
                                    $incorrectQuestionData[0][0] = "Question Type";
                                    $incorrectQuestionData[0][1] = "Number Incorrect";
                                    
                                    $correctQuestionData[0][0] = "Question Type";
                                    $correctQuestionData[0][1] = "Number Correct";
                                    
                                    $correctIncorrectSummaryData[0][0] = "Correct or Incorrect";
                                    $correctIncorrectSummaryData[0][1] = "Total";
                                    
                                    $incorrectArrayIterator = 1;
                                    $correctArrayIterator = 1;
                                    
                                    $totalIncorrect = 0;
                                    $totalCorrect = 0;

                                    while($row4 = mysqli_fetch_assoc($countQuestionTypes)) {
                                        
                                        if($row4['Correct'] == '0') {
                                            $incorrectQuestionData[$incorrectArrayIterator][0] = $row4["QuestionType"];
                                            $incorrectQuestionData[$incorrectArrayIterator][1] = intval($row4["Count"]);
                                            $totalIncorrect = $totalIncorrect + intval($row4["Count"]);
                                            $incorrectArrayIterator = $incorrectArrayIterator + 1;
                                        } else if($row4['Correct'] == '1') {

                                            $correctQuestionData[$correctArrayIterator][0] = $row4["QuestionType"];
                                            $correctQuestionData[$correctArrayIterator][1] = intval($row4["Count"]);
                                            $totalCorrect = $totalCorrect + intval($row4["Count"]);
                                            $correctArrayIterator = $correctArrayIterator + 1;
                                        }
                                    }
                                    
                                    $correctIncorrectSummaryData[1][0] = "Correct";
                                    $correctIncorrectSummaryData[1][1] = $totalCorrect;
                                    $correctIncorrectSummaryData[2][0] = "Inorrect";
                                    $correctIncorrectSummaryData[2][1] = $totalIncorrect;
                                    
                                    $encodedIncorrectQuestionData = json_encode($incorrectQuestionData);
                                    $encodedCorrectQuestionData = json_encode($correctQuestionData);
                                    $encodedCorrectIncorrectSummaryData = json_encode($correctIncorrectSummaryData);
                                }
                                
                                $countQuestionDifficultiesSQL ="SELECT quest.Difficulty, ans.Correct, COUNT(1) AS Count  FROM 7057_questions_asked quest INNER JOIN 7057_answered ans ON quest.QuestionID = ans.QuestionID WHERE ans.ProfileID='$pid' GROUP BY quest.Difficulty, ans.Correct ";
                                $countQuestionDifficulties = mysqli_query($conn, $countQuestionDifficultiesSQL) or die(mysqli_error($conn));
                                if (mysqli_num_rows($countQuestionDifficulties) > 0) {

                                    $questionByDifficultyData=array();
                                    
                                    $questionByDifficultyData[0][0] = "Difficulty";
                                    $questionByDifficultyData[0][1] = "Number";
                                    
                                    $arrayIterator = 1;

                                    while($row5 = mysqli_fetch_assoc($countQuestionDifficulties)) {
                                        
                                        if($row5['Correct'] == '0') {
                                            $questionByDifficultyData[$arrayIterator][0] = $row5["Difficulty"] . " - Incorrect";
                                            $questionByDifficultyData[$arrayIterator][1] = intval($row5["Count"]);
                                            $arrayIterator = $arrayIterator + 1;
                                        } else if($row5['Correct'] == '1') {

                                            $questionByDifficultyData[$arrayIterator][0] = $row5["Difficulty"] . " - Correct";
                                            $questionByDifficultyData[$arrayIterator][1] = intval($row5["Count"]);
                                            $arrayIterator = $arrayIterator + 1;
                                        }
                                    }
                                    
                                    $encodedQuestionByDifficultyData = json_encode($questionByDifficultyData);
                                }
                                
                            }
                    echo "<td><button type='button' class='btn btn-warning btn-sm' name='submit' onclick='displayRounds(\"$pname\", $oldratingArray, $newratingArray, $pointsachievedArray, $pointsavailableArray, $roundTimeStampArray, $questionsData);'>Rounds</button></td>";
                    echo "<td><button type='button' class='btn btn-warning btn-sm' name='submit' onclick='displayLine($encodedLevelPlayedData, \"Rating Changes per Round\");'>Played</button></td>";
                    echo "<td><button type='button' class='btn btn-warning btn-sm' name='submit' onclick='displayLine($encodedPointsData, \"Point Changes per Round\");'>Points</button></td>";
                    echo "<td><button type='button' class='btn btn-warning btn-sm' name='submit' onclick='displayPie($encodedCorrectIncorrectSummaryData, \"Question Summary\");'>Summary</button></td>";
                    echo "<td><button type='button' class='btn btn-warning btn-sm' name='submit' onclick='displayPie($encodedIncorrectQuestionData, \"Incorrect Questions\");'>Incorrect</button></td>";
                    echo "<td><button type='button' class='btn btn-warning btn-sm' name='submit' onclick='displayPie($encodedCorrectQuestionData, \"Correct Questions\");'>Correct</button></td>";
                    echo "<td><button type='button' class='btn btn-warning btn-sm' name='submit' onclick='displayPie($encodedQuestionByDifficultyData, \"Incorrect Questions\");'>Difficulty</button></td></tr>";
                } ?>
                </table>
                
            </div>
            <?php } else {
                echo "$nonefoundstring";
            } ?>
        </div>
    </div>
    </div>
</section>
    
    <br />
    
    <section class="s2" id="details_section">
    <div class="row">
        <div class='col-xs-offset-1 col-xs-12 col-sm-9 col-md-9'>
            <div class="textoverflow">

                <div id="details"></div>
                
            </div>
        </div>
    </div>
</section>

</div>
</body>
<?php
    mysqli_close($conn);
?>
<footer class="navbar-fixed-bottom foot">
    <div class="container">AQE Star Captain - by Chris Maitland - 2018 - <a href="mailto:cmaitland02@qub.ac.uk"> Email </a></div>
</footer>
</html> 