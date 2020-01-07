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
    $PostTitle=$_POST["PostTitle"];
    $Category=$_POST["Category"];
    $Image=$_FILES["Image"]["name"];
    $Target="Uploads/".basename($_FILES["Image"]["name"]);
    $PostText=$_POST["PostDescription"];
    $Admin=$_SESSION["AdminName"];
    date_default_timezone_set("Asia/Kolkata");
    $CurrentTime=time();
    $DateTime=strftime("%Y-%m-%d %H:%m:%S",$CurrentTime);

    if(empty($PostTitle)){
        $_SESSION["ErrorMessage"]="Title can't be empty";
        Redirect_to("AddNewPost.php");
    }
    elseif(strlen($PostTitle)<4){
        $_SESSION["ErrorMessage"]="Post title must be greater than four characters";
        Redirect_to("AddNewPost.php");
    }
    elseif(strlen($PostText)>999){
        $_SESSION["ErrorMessage"]="Post Description must be less than thousand characters";
        Redirect_to("AddNewPost.php");
    }else{
        $sql="INSERT INTO posts(datetime,title,category,author,image,post)
                VALUES(:dateTime,:postTitle,:categoryName,:adminName,:imageName,:postDescription)";
        $stmt=$ConnectingDB->prepare($sql);
        $stmt->bindvalue(':dateTime',$DateTime);
        $stmt->bindvalue(':postTitle',$PostTitle);
        $stmt->bindvalue(':categoryName',$Category);
        $stmt->bindvalue(':adminName',$Admin);
        $stmt->bindvalue(':imageName',$Image);
        $stmt->bindvalue(':postDescription',$PostText);
        $Execute=$stmt->execute();
        move_uploaded_file($_FILES["Image"]["tmp_name"],$Target);
        if($Execute){
            $_SESSION["SuccessMessage"]="Post with id: ".$ConnectingDB->lastInsertId(). " added successfully";
            Redirect_to("AddNewPost.php");
        }else{
            $_SESSION["ErrorMessage"]="Something went wrong!Try again!!";
            Redirect_to("AddNewPost.php");
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

    <title>Post</title>
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
                    <h1><i class="fas fa-edit"></i>Add New Post</h1>
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
        <form action="AddNewPost.php" method="POST" enctype="multipart/form-data">
            <div class="card">
                    <div class="card-body bg-dark">
                        <div class="form-group">
                            <label for="title"><span class="Fieldinfo">Post Title:</span></label>
                            <input class="form-control" type="text" name="PostTitle" id="title" placeholder="Type title here">
                        </div>
                        <div class="form-group">
                            <label for="CategoryTitle"><span class="Fieldinfo">Choose Category:</span></label>
                            <select name="Category" id="CategoryTitle" class="form-control">
                                <option value="">Choose Category</option>
                            <?php
                                global $ConnectingDB;
                                $sql="SELECT id,title FROM category";
                                $stmt=$ConnectingDB->query($sql);
                                while($DateRows=$stmt->fetch()){
                                    $Id=$DateRows["id"];
                                    $CategoryName=$DateRows["title"];
                                
                                ?>
                                <option><?php echo $CategoryName; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="File" class="custom-file-input" name="Image" id="imageSelect" value="">
                                <label for="imageSelect" class="custom-file-label">Select Image</label> 
                            </div>                       
                        </div>
                        <div class="form-group">
                            <label for="Post"><span class="Fieldinfo">Post:</span></label>
                            <textarea name="PostDescription" id="Post" cols="80" rows="8" class="form-control"></textarea>
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