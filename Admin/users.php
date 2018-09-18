<?php

    session_start();
    include("../Connection/conn.php");
    
    $email = $_SESSION['7057_email'];
    $role = $_SESSION['7057_role'];
    $name = $_SESSION['7057_username'];
    
    if (!isset($email)) {
        header('Location:logout.php');
    }
    
    if (isset($_POST['selected'])) {
        $selected = $_POST['selected'];
        $chosenone = $_POST['chosenone'];
    } else {
        $selected = "";
        $chosenone = "";
    }
    
    if (isset($_POST['deleted'])) {
        $deleted = $_POST['deleted'];
    } else {
        $deleted = "";
    }
    if (isset($_POST['success'])) {
        $success = $_POST['success'];
    } else {
        $success = "";
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
    <link rel="stylesheet" href="../Style/style.css" />
    <link rel="icon" href="../Images/rainbow.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="../JS/functions.js"></script>
    <script type="text/javascript">
        function confirmDelete(profile) {
            /* var ok = confirm("Are you sure you wish to delete the profile "+profile+" ?");
            if (ok == true) {
                return ok;
            } else {
                return "";
            } */
            return confirm("Are you sure you wish to delete the profile "+profile+" ?");
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
                        <?php if ($role == 'Admin') {
                            echo "<a href='questions.php'><button type='button' class='btn btn-success' name='questions'>Questions</button></a>";
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
                        <h3>Manage Profiles</h3><br />
                        <form action='updateuser.php' method='POST' enctype="multipart/form-data">
                            <div class="table-responsive">
                            <table class='table table-bordered table-hover'>
                            <tr class="success">
                                <?php if ($role == 'Admin') {
                                    echo "<th>#ID</th>
                                    <th>Username</th>";
                                } ?>
                                <th>Profile Name</th>
                                <th>Delete Profile</th>
                            </tr>
                                <?php
                                if ($role == 'User') {
                                    $profilequerySQL = "SELECT * FROM 7057_profiles WHERE Username='$name'";
                                } else if ($role == 'Admin') {
                                    $profilequerySQL = "SELECT * FROM 7057_profiles";
                                }
                                    $profilequery = mysqli_query($conn, $profilequerySQL) or die(mysqli_error($conn));

                                    if (mysqli_num_rows($profilequery) > 0) {
                                        while ($row = mysqli_fetch_assoc($profilequery)){
                                            echo "<tr>";
                                            if ($role == 'Admin') {
                                                echo "<td>".$row['ProfileID']."</td>";
                                                echo "<td>".$row['Username']."</td>";
                                            }
                                            echo "<td>".$row['ProfileName']."</td>";
                                            $deletename = $row['ProfileName'];
                                            
                                            echo "<td><button type='submit' name='deleteprofile' class='btn btn-warning btn-sm' onclick='return confirmDelete(\"$deletename\" );' value=".$row['ProfileID'].">Delete Profile</button></td>";
                                        } 
                                    }
                                ?>
                            </table>
                            </div>
                        </form>
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class='col-xs-offset-1 col-xs-12 col-sm-9 col-md-9'>
                    <div class="textoverflow">
                        <?php
                            if ($deleted) {
                                echo "<p><div class='text-success'><i>Profile has been deleted!</i></div></p>";
                            }
                            if ($success) {
                                echo "<p><div class='text-success'><i>Profile details have been updated!</i></div></p>";
                            } ?>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-inline">
                                        <div class='form-group'>
                                            <p><label for='userpick'>Edit Profiles</label></p>
                                            <p><h5>Select which Profile you wish to edit:</h5></p>
                                            <p><select class="form-control" name="chosenone" value="<?php echo $chosenone ?>">
                                        <?php
                                            if ($role == 'User') {
                                                $userspickSQL = "SELECT * FROM 7057_profiles WHERE Username='$name'";
                                            } else if ($role == 'Admin') {
                                                $userspickSQL = "SELECT * FROM 7057_profiles";
                                            }
                                            $userspick = mysqli_query($conn, $userspickSQL) or die(mysqli_error($conn));
                                            
                                            if (mysqli_num_rows($userspick) > 0) {
                                                if($chosenone == ""){
                                                    echo "<option selected hidden>Select profile to edit</option>";
                                                }
                                                while ($row = mysqli_fetch_assoc($userspick)){
                                                    $profileid = $row['ProfileID'];
                                                    $profileusername = $row['Username'];
                                                    $profilename = $row['ProfileName'];
                                                    if($chosenone == $profileid){
                                                        $selectedstring = "selected";
                                                    } else {
                                                        $selectedstring = "";
                                                    }
                                                    if ($role == 'User') {
                                                        $displayoption = $profilename;
                                                    } else if ($role == 'Admin') {
                                                        $displayoption = $profileusername." - ".$profilename; 
                                                    }
                                                    echo "<option value='$profileid' ".$selectedstring.">".$displayoption."</option>";
                                                }
                                            }
                                        ?>
                                        </select>
                                                <button type="submit" class="btn btn-success" name="selected" value="selected">Edit Profile</button>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </form>
                                <?php 
                                
                                if ($selected) {
                                
                                    if ($role == 'User') {
                                        $updateProfileSQL = "SELECT * FROM 7057_profiles WHERE ProfileID = '$chosenone' AND Username = '$name'";
                                    } else if ($role == 'Admin') {
                                        $updateProfileSQL = "SELECT * FROM 7057_profiles WHERE ProfileID = '$chosenone'";
                                    }
                                        $updateProfile = mysqli_query($conn, $updateProfileSQL) or die(mysqli_error($conn));
                                        
                                            $row2 = mysqli_fetch_assoc($updateProfile);
                                            $profileID = $row2['ProfileID'];
                                            $username = $row2['Username'];
                                            $pickname = $row2['ProfileName'];
                                            $pin = $row2['PIN'];
                                ?>
                                <hr>
                </div>
                </div>
            </div>
                                <div class="row">
                                    <div class='col-xs-offset-1 col-xs-12 col-sm-9 col-md-9'>
                                        <div class="textoverflow">
                                    <form action='updateuser.php' method='POST' enctype='multipart/form-data'>
                                        <input type='hidden' name='profileid' value="<?php echo $chosenone ?>">
                                        <div class='form-group'>
                                            <label for='updateuser'>Amend Below</label>
                                            <p>Name:<input type='text' class='form-control' name='updatename' value="<?php echo $pickname ?>"></p>
                                        </div>
                                        <div class='form-group'>
                                            <p>PIN:<input type='text' class='form-control' name='updatepin' autocomplete="off" placeholder="<?php echo '****' ?>"></p>
                                        </div>
                                        <div class='form-group'>
                                            <p><button type="submit" class="btn btn-success" name="update">Update</button></p>
                                        </div>
                                        
                                    </form>
                             <?php   
                                }
                            ?>
                        
                                        </div>
                                    </div>
                                </div>
        </section>
        </div>
            
        <div class='container'>
            <div class='row'>
                <div class='col-md-12'>
                    <br />
                    <br />
                </div>
            </div>
        </div>
</body>
<footer class="navbar-fixed-bottom foot">
    <div class="container">AQE Star Captain - by Chris Maitland - 2018 - <a href="mailto:cmaitland02@qub.ac.uk"> Email </a></div>
</footer>
</html>
<?php mysqli_close($conn) ?>