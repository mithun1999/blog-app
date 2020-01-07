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
$AdminId=$_SESSION["UserId"];
global $ConnectingDB;
$sql="SELECT * FROM admins WHERE id='$AdminId' ";
$stmt=$ConnectingDB->query($sql);
while($DateRows=$stmt->fetch()){
    $ExistingName=$DateRows["aname"];
    $ExistingHeadline=$DateRows["aheadline"];
    $ExistingBio=$DateRows["abio"];
    $ExistingImage=$DateRows["aimage"];
}
if(isset($_POST["Submit"])){
    $AName=$_POST["Name"];
    $AHeadline=$_POST["Headline"];
    $ABio=$_POST["Bio"];
    $Image=$_FILES["Image"]["name"];
    $Target="Images/".basename($_FILES["Image"]["name"]);

   if(strlen($AHeadline)>30){
        $_SESSION["ErrorMessage"]="Headline must be less than 30 characters";
        Redirect_to("MyProfile.php");
    }
    elseif(strlen($ABio)>500){
        $_SESSION["ErrorMessage"]="Bio must be less than 500 characters";
        Redirect_to("MyProfile.php");
    }else{
        global $ConnectingDB;
        $SearchQueryParameter=$_GET["id"];
        if(!empty($_FILES["Image"]["name"])){
                $sql="UPDATE admins
                    SET aname='$AName',aheadline='$AHeadline',abio='$ABio',aimage='$Image'
                    WHERE id='$AdminId'  ";
        }
       else{
        $sql="UPDATE admins
        SET aname='$AName',aheadline='$AHeadline',abio='$ABio'
        WHERE id='$AdminId'  ";
        }
        $Execute=$ConnectingDB->query($sql);        
        move_uploaded_file($_FILES["Image"]["tmp_name"],$Target);
        if($Execute){
            $_SESSION["SuccessMessage"]="Details updated successfully";
            Redirect_to("MyProfile.php");
        }else{
            $_SESSION["ErrorMessage"]="Something went wrong!Try again!!";
            Redirect_to("MyProfile.php");
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

    <title>My Profile</title>
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
                    <h1><i class="fas fa-user"></i>My Profile</h1>
                </div>
            </div>
        </div>
    </header>
    <!--Header End-->

    <!--Main Area-->
    <section class="container">
        <div class="row">
        <div class="col-md-3">
        <div class="card">
        <div class="card-header bg-dark text-light">
        <h3><?php global $ExistingName; echo $ExistingName; ?></h3>
        </div>
        <div class="card-body">
        <img src="Images/<?php global $ExistingImage; echo $ExistingImage; ?>" height="250px" width="200px" alt="">
        <div>
        <?php global $ExistingBio; echo $ExistingBio; ?></div>
        </div>
        </div>
        </div>
        <div class="col-md-9" style="min-height:430px;">
        <?php
        echo ErrorMessage();
        echo SuccessMessage();
        ?>    
        <form action="MyProfile.php" method="POST" enctype="multipart/form-data">
            <div class="card bg-dark text-light">
            <div class="card-header">
            <h4>Edit Profile</h4>
            </div>
                    <div class="card-body bg-dark">
                        <div class="form-group">
                        <input class="form-control" type="text" name="Name" id="" placeholder="Type Your Name here">
                        </div>
                        <div class="form-group">
                        <input class="form-control" type="text" name="Headline" id="" placeholder="Type Your Skills here">
                        <small class="text-muted">Add a professional headline</small>
                        <span class="text-danger">Text not more than 30 characters</span>
                        </div>

                        <div class="form-group">
                            <textarea name="Bio" id="Post" cols="80" rows="8" class="form-control" placeholder="Bio"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="File" class="custom-file-input" name="Image" id="imageSelect" value="">
                                <label for="imageSelect" class="custom-file-label">Select Image</label> 
                            </div>                       
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
            </form>
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