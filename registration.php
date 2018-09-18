<?php
    error_reporting(0);
    require "Connection/conn.php";
    
    if (isset($_GET['duplicate'])) {
        $duplicate = $_GET['duplicate'];
    } else {
        $duplicate = "";
    }
    
    if (isset($_GET['success'])) {
        $registered = $_GET['success'];
    } else {
        $registered = "";
    }
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AQE App Registration</title>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="Style/style.css" />
        <link rel="icon" href="Images/rainbow.png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script>
            function checkPasswordsValid() {
                if (document.getElementById('pw1').value.length < 8 || document.getElementById('pw2').value.length < 8 
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
            function validate() {
                //check email not null and length
                if(document.getElementById('Email').value.length == 0 || document.getElementById('Email').value.length > 100) {
                    return false;
                }
                //N.B. email format check is already handled by type restriction
                //check name not null and length
                if(document.getElementById('Name').value.length == 0 || document.getElementById('Name').value.length > 50) {
                    return false;
                }
                //check passwords are valid length
                if(checkPasswordsValid() == false) {
                    return false;
                }
                //N.B. passwords matching check is already handled when passwords are changed
                
                return true;
            }
            
        </script>
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top foot ">

            <div class="container foot">

                <div class="navbar-header">


                    <a href="index.php" class="navbar-brand navbar-left ">
                        <img id='imgbanner' src="Images/rainbow.png" style='width: 100px;' class="img-responsive title"></a>
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

                                <a href="register.php"><button type='button' class="btn btn-success" name='register'>Register</button></a>

                        </form>
                        <div id='pw'><a href="forgotten.php"> Forgotten password? </a></div>
                    </div>
                </div>

            </div>
        </nav><!--/.navbar-collapse -->
          <!--  <section class="s1"> -->
        <section class="s1">
        </section>
           <!-- </section>-->
        <div class="container">
            <section class="s2">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                    <br>
                    <form action="Admin/register.php" onsubmit="return validate()" method="POST">
                            <h2><p>Register</p></h2>
                            <h5>Registering here will also register your account on the AQE Star Captain Android App.</h5>
                            <br />
                            <div class="form-group">
                                <label id="reglabel">Enter your Email</label>
                                <input type="email" placeholder="Enter your Email" class="form-control" name="email" id="Email" required/>
                                
                            </div>
                            <br />
                            <div class="form-group">
                                <label id="reglabel">Your Unique Username</label>
                                <input type="text" placeholder="Enter your Username" class="form-control" name="name" id="Name" required />
                                <?php
                                    if ($duplicate) {
                                        echo "<p><div id='error'><i>Email has already been registered</i></div></p>";
                                    }
                                ?>
                            </div>
                            <br />
                            <div class="form-group">
                                <label id="reglabel">Choose a password</label>
                                <input type="password" name="password" id='pw1' placeholder="Password" class="form-control" onkeyup='checkPasswordsValid();'/>
                            </div>
                            <br />
                            <div class="form-group">
                                <label id="reglabel">Confirm password</label>

                                <input type="password" name="pw2" id='pw2' placeholder="Check password" class="form-control" onkeyup='checkPasswordsValid();'/>
                                <span id='message'></span>
                            <?php
                                if ($registered) {
                                    echo "<p><div class='text-success'><i>You have successfully registered!</i></div></p>";
                                }
                            ?>
                            </div>
                            <br />
                            <button type="submit" id='submit' class="btn btn-success" name='submit' disabled >Submit</button>

                            

                        </form>
                    </div>
                </div>
            </section>
        </div>
       <!-- </section>-->     
    </body>
    <footer class="navbar-fixed-bottom foot">
        <div class="container pad pad2">AQE Star Captain - by Chris Maitland - 2018 - <a href="mailto:chris.maitland@hotmail.co.uk"> Email </a></div>
    </footer>
</html>