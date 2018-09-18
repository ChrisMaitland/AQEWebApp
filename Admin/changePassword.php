<?php

session_start();
include("../Connection/conn.php");
    
$email = $_SESSION['7057_email'];
$role = $_SESSION['7057_role'];
$name = $_SESSION['7057_username'];

if (!isset($email)) {
    header('Location:logout.php');
}

if (isset($_GET['updated'])) {
        $pwupdated = $_GET['updated'];
    } else {
        $pwupdated = "";
    }

//check DB to for Email and Random
$resetcheckSQL = "SELECT * FROM 7057_users WHERE Email='$email'";
$resetcheck = mysqli_query($conn, $resetcheckSQL) or die(mysqli_error($conn));

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Profile</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="../Style/style.css" />
        <link rel="icon" href="../Images/rainbow.png">
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
                        
                        <?php
                                if ($role == 'Admin') {
                                    echo "<a href='users.php'><button type='button' class='btn btn-success' name='users'>Manage Users</button></a>";
                                    
                                    echo "<a href='questions.php'><button type='button' class='btn btn-success' name='questions'>Questions</button></a>";
                                } else if ($role == 'User') {
                                    echo "<a href='users.php'><button type='button' class='btn btn-success' name='users'>Manage Profiles</button></a>";
                                }
                            ?>

                        <a href='logout.php'><button type='button' class="btn btn-success" name='logout'>Log Out</button></a>
                        
                        <p><div id='pw'><a href="mailto:cmaitland02@qub.ac.uk"> Problem? Contact Admin! </a></div></p>

                    </form>

                </div>
            </div><!--/.navbar-collapse -->
        </div>
    </nav>
    <section class="s1">
    </section>
    <div class="container">
        <section class="s2">
            <div class="row">
                <div class="col-xs-12 col-sm-9 col-md-6">

<?php
    // if password update successful, message appears at top of screen
    if ($pwupdated) {
        echo "<p><div class='text-success'><i>Password Updated!</i></div></p>";
    }
        // if User is found, then create form
        if (mysqli_num_rows($resetcheck) > 0 ) { ?>

                <form action="" onsubmit="return validate()" method="POST">
                        <h2><p>Enter a new password</p></h2>
                        <br />
                        <div class="form-group">
                            <label id="reglabel">Choose a password</label>
                            <input type="password" name="pw1" id="pw1" placeholder="Password" class="form-control" onkeyup="checkPasswordsValid();"/>
                        </div>
                        <br />
                        <div class="form-group">
                            <label id="reglabel">Confirm password</label>
                            <input type="password" name="pw2" id="pw2" placeholder="Check password" class="form-control" onkeyup="checkPasswordsValid();"/>
                            <span id="message"></span>

                        </div>
                        <br />
                        <button type="submit" id="submit" class="btn btn-success" name="submit" value="Submit" disabled >Submit</button>
                    </form>
        <?php
        } else {
            //Otherwise display error and logout user - Should never get here.
            echo 'User not found.';
            header('Location:logout.php');
        }

    if(isset($_POST['submit']))
    {
        $pw = md5($_POST['pw1']);
        //if valid update user table to new password
        $updatequery = "UPDATE 7057_users SET Password = '$pw' WHERE Email = '$email'";
        $update = mysqli_query($conn, $updatequery) or die(mysqli_error($conn));
        mysqli_close($conn);
        //header('Location:changePassword.php?updated=true');
    }

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
