<?php

    session_start();
    include("../Connection/conn.php");
    
    $email = $_SESSION['7057_email'];
    $role = $_SESSION['7057_role'];
    $name = $_SESSION['7057_username'];
    
    if (!isset($email)) {
        header('Location:logout.php');
    }
    
    if ($role != 'Admin') {
        header('Location:logout.php');
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
    <link src="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" href="../Style/style.css" />
    <link rel="icon" href="../Images/rainbow.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css"></script>
    <script>
        $(document).ready(function() {
            $('#questionTable').DataTable();
        } );
    </script>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top foot ">

        <div class="container foot">

            <div class="navbar-header foot">

                <a href="userhub.php" class="navbar-brand navbar-left "><img id='imgbanner' src="../Images/rainbowLogo.png"  class="img-responsive title"></a>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                </button>
            </div>  

            <div id="myNavbar" class="navbar-collapse collapse">
                <div class="navbar-form navbar-right ">
                    <form action='admin/signin.php' method='POST'>

                        <a href='userhub.php'><button type='button' class="btn btn-success" name='userhub'>User Hub</button></a>
                        <?php if ($role == 'Admin') {
                            echo "<a href='users.php'><button type='button' class='btn btn-success' name='users'>Manage Users</button></a>";
                        } ?>
                        <a href='changePassword.php'><button type='button' class="btn btn-success" name='account'>Account</button></a>

                        <a href='logout.php'><button type='button' class="btn btn-success" name='logout'>Log Out</button></a>
                        
                        <p><div id='pw'><a href="mailto:cmaitland02@qub.ac.uk"> Problem? Contact Admin! </a></div></p>

                    </form>

                </div>
            </div><!--/.navbar-collapse -->
        </div>
    </nav>
    <section class="s1">
        <div class="container">
            <div class='row'>
                
            </div>
        </div>
    </section>
    <div class="container">
        <section class="s2">
            <div class="row">
                <div class='col-xs-offset-1 col-xs-12 col-sm-9 col-md-9'>
                    <div class="textoverflow">
                        <h3>Details of Questions Asked</h3><br />
                            <div class="table-responsive">
                                <table class='table table-bordered table-hover' id="questionTable">
                                    <thead>
                                        <tr class="success">
                                            <th>#ID</th>
                                            <th>Question</th>
                                            <th>Answer</th>
                                            <th>Question Type</th>
                                            <th>Difficulty</th>
                                            <th>Correct?</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                    $questionquerySQL = "SELECT qst.*, ans.Correct, COUNT(1) AS Count FROM 7057_questions_asked qst INNER JOIN 7057_answered ans ON qst.QuestionID = ans.QuestionID GROUP BY qst.Question, qst.Answer, qst.QuestionType, qst.Difficulty, ans.Correct ORDER BY qst.QuestionID ASC";
                                    $questionquery = mysqli_query($conn, $questionquerySQL) or die(mysqli_error($conn));

                                    if (mysqli_num_rows($questionquery) > 0) {
                                        while ($row = mysqli_fetch_assoc($questionquery)){
                                            echo "<tr>";
                                            echo "<td>".$row['QuestionID']."</td>";
                                            echo "<td>".$row['Question']."</td>";
                                            echo "<td>".$row['Answer']."</td>";
                                            echo "<td>".$row['QuestionType']."</td>";
                                            echo "<td>".$row['Difficulty']."</td>";
                                            if ($row['Correct'] == 1) {
                                                $row['Correct'] = 'Yes';
                                            } else {
                                                $row['Correct'] = 'No';
                                            }
                                            echo "<td>".$row['Correct']."</td>";
                                            echo "<td>".$row['Count']."</td></tr>";
                                        } 
                                    }
                                ?>
                                    </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
            </div>
        </section>
    </div>
            
</body>
<footer class="navbar-fixed-bottom foot">
    <div class="container">AQE Star Captain - by Chris Maitland - 2018 - <a href="mailto:cmaitland02@qub.ac.uk"> Email </a></div>
</footer>
</html>