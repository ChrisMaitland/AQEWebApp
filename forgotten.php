<?php
    error_reporting(0);
    require "Connection/conn.php";
    
    if (isset($_GET['exists'])) {
        $exists = $_GET['exists'];
    } else {
        $exists = 'true';
    }
    
    
?>

<!DOCTYPE html>
<html lang="en">
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Forgot Password</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="Style/style.css" />
        <link rel="icon" href="Images/rainbow.png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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

                                <a href="registration.php"><button type='button' class="btn btn-success" name='register'>Register</button></a>

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
            <form action="sendResetEmail.php" method="POST">
                <p><h2>Forgotten Password?</h2></p>
                <br />
                <div class="form-group">
                    <label id="reglabel">Enter your Email address to generate a password reset email:</label><br />
                    <input type="email" placeholder="Enter your Email" class="form-control" name="Email" id="Email" required/>
                    <?php
                        if ($exists == 'false') {
                            echo "<p><div id='error'><i>Email address is not registered</i></div></p>";
                        }
                    ?>
                </div>
                
                <button type="submit" id='submit' class="btn btn-success" name='submit'>Submit</button>
             
            </form>
                </div>
            </div>
            </section>
        </div>
       

    </body>
    <footer class="navbar-fixed-bottom foot">
        <div class="container pad pad2">AQE Star Captain - by Chris Maitland - 2018 - <a href="mailto:chris.maitland@hotmail.co.uk"> Email </a></div>
    </footer>
</html>