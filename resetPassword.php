<?php

require "Connection/conn.php";

$email = $_GET['email'];
$reset = $_GET['reset'];

//check DB to for Email and Random
$resetcheckSQL = "SELECT * FROM 7057_resetpw WHERE Email='$email' AND ResetKey='$reset' AND Used IS NULL";
$resetcheck = mysqli_query($conn, $resetcheckSQL) or die(mysqli_error($conn));


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Reset Password</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="Style/style.css" />
        <link rel="icon" href="Images/rainbow.png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script>
            function checkPasswordsValid() {
                if (document.getElementById('pw1').value.length < 7 || document.getElementById('pw2').value.length < 7 
                        || document.getElementById('pw1').value.length > 15 || document.getElementById('pw2').value.length > 15 ) {
                    document.getElementById('message').style.color = 'red';
                    document.getElementById('message').innerHTML = 'Password must be between 8 and 15 characters';
                    document.getElementById('submit').disabled = true;
                    return false;
                } else if (document.getElementById('pw1').value == document.getElementById('pw2').value) {
                    document.getElementById('message').style.color = 'green';
                    document.getElementById('message').innerHTML = 'Passwords match';
                    document.getElementById('submit').disabled = false;
                    return true;
                } else {
                    document.getElementById('message').style.color = 'red';
                    document.getElementById('message').innerHTML = 'Passwords do not match';
                    document.getElementById('submit').disabled = true;
                    return false;
                }
            }
        </script>
    </head>
    <body>
    <nav class="navbar navbar-inverse navbar-fixed-top foot ">
            <div class="container foot">
                <div class="navbar-header">
                    <a href="index.php" class="navbar-brand navbar-left ">
                        <img id='imgbanner' src="Images/rainbow.png" class="img-responsive title"></a>
                                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>                        
                                    </button>
                </div>
                <div id="myNavbar" class="navbar-collapse collapse">
                    <div class="navbar-form navbar-right ">
                        <form action='Admin/signin.php' method='POST'>


                                <span class="form-group ">
                                    <input type="email" placeholder="Email" class="form-control" name="Email" required/>
                                </span>

                                <span class="form-group">
                                    <input type="password" name="pw" placeholder="Password" class="form-control"/>
                                </span>

                                <button type="submit" class="btn btn-success" name='sign'>Sign in</button>

                                <a href="registration.php"><button type='button' class="btn btn-success" name='register'>Register</button></a>

                        </form>
                        <div id='pw'><a href="forgotten.php"> Forgotten password? </a></div>
                    </div>
                </div>
            </div>
        </nav><!--/.navbar-collapse -->
    <section class="s1">
    </section>
    <div class="container">
        <section class="s2">
            <div class="row">
                <div class="col-xs-12 col-sm-9 col-md-6">

<?php
    if(!isset($_POST['submit'])) {
        //if found
        if (mysqli_num_rows($resetcheck) > 0 ) {

            $row = mysqli_fetch_assoc($resetcheck);
            //Check timestamp stored is within 1 day
            $timestamp = strtotime($row['TimeStamp']);
            $timenow = time();
            $diff = $timenow - $timestamp; //gives difference in seconds
            //echo $timestamp, ' ', $timenow, ' ', $diff;
            $secondsPerDay = 86400;
            if ($diff <= $secondsPerDay) {
                //If so display fields
                echo '<form action="" onsubmit="return validate()" method="POST">';
                        echo '<p><h2>Enter a new password</h2></p>';
                        echo '<br />';
                        echo '<div class="form-group">';
                            echo '<label id="reglabel">Choose a password</label>';
                            echo '<input type="password" name="pw1" id="pw1" placeholder="Password" class="form-control" onkeyup="checkPasswordsValid();"/>';
                        echo '</div>';
                        echo '<br />';
                        echo '<div class="form-group">';
                            echo '<label id="reglabel">Confirm password</label>';
                            echo '<input type="password" name="pw2" id="pw2" placeholder="Check password" class="form-control" onkeyup="checkPasswordsValid();"/>';
                            echo '<span id="message"></span>';

                        echo '</div>';
                        echo '<br />';
                        echo '<button type="submit" id="submit" class="btn btn-success" name="submit" value="Submit" disabled >Submit</button>  ';
                    echo '</form>';
            } else {
                //Otherwise display error with a link back to forgotten.php to allow them to regenerate
                echo 'Password reset link has expired.  Please visit the <a href="forgotten.php">Forgotten password page</a> to generate a new link.';
            }

        } else {
            //Otherwise display error with a link back to forgotten.php
            echo 'Password reset link is invalid.  Please visit the <a href="forgotten.php">Forgotten password page</a> to generate a new link.';
        }
    } else {
        echo "No Reset link found. Please visit the <a href='forgotten.php'>Forgotten password page</a> to generate a new link.";
    }
    if(isset($_POST['submit']))
    {
        $pw = md5($_POST['pw1']);
        //if valid update user table to new password
        $updatequery = "UPDATE 7057_users SET Password = '$pw' WHERE Email = '$email'";
        $update = mysqli_query($conn, $updatequery) or die(mysqli_error($conn));
        //and update so that they can't be used again but kept for audit
        $deletequery = "UPDATE 7057_resetpw SET Used='Yes' WHERE Email='$email' AND Used IS NULL";
        $delete = mysqli_query($conn, $deletequery) or die(mysqli_error($conn));
        //header('Location:index.php');
        echo '<a href:index.php>Home</a>';
    }
mysqli_close($conn);
?>
            
                </div>
            </div>
        </section>
    </div>
       
    </body>
    <footer class="navbar-fixed-bottom foot">
        <div class="container">AQE Star Captain - by Chris Maitland - 2018 - <a href="mailto:cmaitland02@qub.ac.uk"> Email </a></div>
    </footer>
</html>
