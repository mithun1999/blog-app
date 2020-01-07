<?php
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/Sessions.php");
?>
<?php
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
Confirm_Login();
?>
<?php
if(isset($_POST["Submit"])){
    $Username=$_POST["Username"];
    $Name=$_POST["Name"];
    $Password=$_POST["Password"];
    $ConfirmPassword=$_POST["ConfirmPassword"];
    $Admin=$_SESSION["AdminName"];
    date_default_timezone_set("Asia/Kolkata");
    $CurrentTime=time();
    $DateTime=strftime("%Y-%m-%d %H:%m:%S",$CurrentTime);

    if(empty($Username)||empty($Password)||empty($ConfirmPassword)){
        $_SESSION["ErrorMessage"]="All fields must be filled out";
        Redirect_to("Admins.php");
    }
    elseif(strlen($Password)<4){
        $_SESSION["ErrorMessage"]="Password must be greater than four characters";
        Redirect_to("Admins.php");
    }
    elseif($Password!==$ConfirmPassword){
        $_SESSION["ErrorMessage"]="Password and Confirm Password must match";
        Redirect_to("Admins.php");
    }
    elseif(CheckUsernameExists($Username)){
        $_SESSION["ErrorMessage"]="Username Exists! Try Another Username!!";
        Redirect_to("Admins.php");
    }
    else{
        $sql="INSERT INTO admins(datetime,username,password,aname,addedby)
                VALUES(:dateTime,:userName,:passWord,:aName,:addedBy)";
        $stmt=$ConnectingDB->prepare($sql);
        $stmt->bindvalue(':dateTime',$DateTime);
        $stmt->bindvalue(':userName',$Username);
        $stmt->bindvalue(':passWord',$Password);
        $stmt->bindvalue(':aName',$Name);
        $stmt->bindvalue(':addedBy',$Admin);
        $Execute=$stmt->execute();
        if($Execute){
            $_SESSION["SuccessMessage"]="New Admin added successfully";
            Redirect_to("Admins.php");
        }else{
            $_SESSION["ErrorMessage"]="Something went wrong!Try again!!";
            Redirect_to("Admins.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <title>Admin</title>
</head>
<body>
    <!--Navbar-->
    <div style="height: 10px; background:#27aae1"></div>
    <nav class="navbar navbar-expand-lg navbar-dark text-dark bg-dark">
        <div class="container">
            <a href="#" class="nav-bar brand top-heading">MITHUNKUMAR.COM</a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarcollapseCMS">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="MyProfile.php" class="nav-link"><i class="fas fa-user-tie"></i> My Profile</a>
                </li>
                <li class="nav-item">
                    <a href="Dashboard.php" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="Posts.php" class="nav-link">Posts</a>
                </li>
                <li class="nav-item">
                    <a href="Categories.php" class="nav-link">Categories</a>
                </li>
                <li class="nav-item">
                    <a href="Admins.php" class="nav-link">Manage Admins</a>
                </li>
                <li class="nav-item">
                    <a href="Comments.php" class="nav-link">Comments</a>
                </li>
                <li class="nav-item">
                    <a href="Blog.php?page=1" class="nav-link">Live Blog</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item ">
                    <a href="Logout.php" class="nav-link text-danger"><i class="fas fa-user-times"></i>Logout</a>
                </li>
            </ul>
        </div>
        </div>
    </nav>
    <div style="height: 10px; background:#27aae1"></div>
    <!--Nav End-->
    
    <!--Header-->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1><i class="fas fa-edit"></i>Manage Admin</h1>
                </div>
            </div>
        </div>
    </header>
    <!--Header End-->

    <!--Main Area-->
    <section class="container">
        <div class="row">
        <div class="offset-lg-1 col-lg-10" style="min-height:430px;">
        <?php
        echo ErrorMessage();
        echo SuccessMessage();
        ?>    
        <form action="Admins.php" method="POST">
            <div class="card">
                <div class="card-header bg-dark text-secondary ">
                    <h1>Add new Admin</h1>
                </div>
                    <div class="card-body bg-dark">
                        <div class="form-group">
                            <label for="username"><span class="Fieldinfo">Username:</span></label>
                            <input class="form-control" type="text" name="Username" id="username" placeholder="Type Your Username Here">
                        </div>
                        <div class="form-group">
                            <label for="name"><span class="Fieldinfo">Name:</span></label>
                            <input class="form-control" type="text" name="Name" id="name" placeholder="Type Your Name Here">
                            <small class="text-warning text-muted">Optional</small>
                        </div>
                        <div class="form-group">
                            <label for="password"><span class="Fieldinfo">Password:</span></label>
                            <input class="form-control" type="password" name="Password" id="password" placeholder="Type Your Password Here">
                        </div>
                        <div class="form-group">
                            <label for="confirmpassword"><span class="Fieldinfo">Confirm Password:</span></label>
                            <input class="form-control" type="password" name="ConfirmPassword" id="confirmpassword" placeholder="Confirm Password">
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <a href="Dashboard.php" class="btn btn-warning btn-block">Back To Dashboard</a>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <button type="Submit" name="Submit" class="btn btn-success btn-block">
                                    <i class="fas fa-check"></i>Publish
                                </button>
                            </div>
                        </div>
                    </div>            
            </div>
            </form><br/>
            <h2>Existing Admins</h2>
                <table class="table table-striped table-hover" >
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Date & Time</th>
                            <th>Username</th>
                            <th>Admin Name</th>
                            <th>Added By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                
                <?php
                global $ConnectingDB;
                $sql="SELECT * FROM admins ORDER BY id desc ";
                $Execute=$ConnectingDB->query($sql);
                $SrNo=0;
                while($DataRows=$Execute->fetch()){
                    $AdminId=$DataRows["id"];
                    $DateTime=$DataRows["datetime"];
                    $AdminUsername=$DataRows["username"];
                    $AdminName=$DataRows["aname"];
                    $AddedBy=$DataRows["addedby"];
                    $SrNo++;
                ?>
                <tbody>
                    <tr>
                        <td><?php echo htmlentities($SrNo); ?></td>
                        <td><?php echo htmlentities($DateTime); ?></td>
                        <td><?php echo htmlentities($AdminUsername); ?></td>
                        <td><?php echo htmlentities($AdminName); ?></td>
                        <td><?php echo htmlentities($AddedBy); ?></td>
                        <td><a href="DeleteAdmins.php?id=<?php echo $AdminId; ?>" class="btn btn-danger">Delete</a></td>
                        
                    </tr>
                </tbody>
                <?php } ?>
                </table>
        </div>
        </div>
    </section>

    <!--Main Area End-->
    
    <br/>
    <!--Footer-->
    <footer>
        <div class="bg-dark text-center pt-2">
            <div class="row">
                <div class="col">
                    <p class="lead text-center text-primary">Theme By |MithunKumar| <span id=year></span> &copy; All rights reserved</p>
                </div>
            </div>
        </div>
    </footer>
    <div style="height: 10px; background:#27aae1"></div>



    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>    

    <script>
        $('#year').text(new Date().getFullYear());    
    </script>
</body>
</html>