<?php
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/Sessions.php");
$SearchQueryParameter=$_GET["id"];
?>
<?php
if(isset($_POST["Submit"])){
    $Name=$_POST["CommenterName"];
    $Email=$_POST["CommenterEmail"];
    $Comment=$_POST["CommenterThoughts"];
    date_default_timezone_set("Asia/Kolkata");
    $CurrentTime=time();
    $DateTime=strftime("%Y-%m-%d %H:%m:%S",$CurrentTime);

    if(empty($Name)||empty($Email)||empty($Comment)){
        $_SESSION["ErrorMessage"]="All fields must be filled out";
        Redirect_to("FullPost.php?id={$SearchQueryParameter}");
    }
    elseif(strlen($Comment)>50){
        $_SESSION["ErrorMessage"]="Category title must be less than fifty characters";
        Redirect_to("FullPost.php?id={$SearchQueryParameter}");
    }
    else{
        $sql="INSERT INTO comments(datetime,name,email,comment,approvedby,status,post_id)
                VALUES(:dateTime,:name,:email,:comment,'Pending','OFF',:postIdFromURL)";
        $stmt=$ConnectingDB->prepare($sql);
        $stmt->bindvalue(':dateTime',$DateTime);
        $stmt->bindvalue(':name',$Name);
        $stmt->bindvalue(':email',$Email);
        $stmt->bindvalue(':comment',$Comment);
        $stmt->bindvalue(':postIdFromURL',$SearchQueryParameter);
        $Execute=$stmt->execute();
        var_dump($Execute);
        if($Execute){
            $_SESSION["SuccessMessage"]="Comment added successfully";
            Redirect_to("FullPost.php?id={$SearchQueryParameter}");
        }else{
            $_SESSION["ErrorMessage"]="Something went wrong!Try again!!";
            Redirect_to("FullPost.php?id={$SearchQueryParameter}");
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
    <link rel="stylesheet" href="./CSS/Styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <title>Blog Page</title>
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
                    <a href="Blog.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">About Us</a>
                </li>
                <li class="nav-item">
                    <a href="Blog.php" class="nav-link">Blog</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Contact Us</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Features</a>
                </li>
        
            </ul>
            <ul class="navbar-nav ml-auto">
                <form action="Blog.php" class="form-inline d-none d-sm-block">
                    <div class="form-group">
                        <input type="text" class="form-control mr-2" name="Search" placeholder="Search Here">
                        <button class="btn btn-primary" name="SearchButton">Go</button>
                    </div>
                </form>
            </ul>
        </div>
        </div>
    </nav>
    <div style="height: 10px; background:#27aae1"></div>
    <!--Nav End-->
    
    <!--Header-->
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
            <h1>CMS Blog</h1>
            <h1 class="lead">The Complete Blog by MithunKumar</h1>
            <?php
            echo ErrorMessage();
            echo SuccessMessage();
            ?>
            <?php
            global $ConnectingDB;
            if(isset($_GET["SearchButton"])){
                $Search=$_GET["Search"];
                $sql="SELECT * FROM posts WHERE datetime LIKE  :searcH OR title LIKE :searcH OR category LIKE :searcH OR post LIKE :searcH";
                $stmt=$ConnectingDB->prepare($sql);
                $stmt->bindvalue(':searcH','%'.$Search.'%');
                $stmt->execute();
            }else{
            $PostIdFromURL=$_GET["id"];
            if(!isset($PostIdFromURL)){
                $_SESSION["ErrorMessage"]="Bad Request";
                Redirect_to("Blog.php");
            }
            $sql="SELECT * FROM posts WHERE id='$PostIdFromURL' ";
            $stmt=$ConnectingDB->query($sql);
            $Result=$stmt->rowcount();
            if($Result!=1){
                $_SESSION["ErrorMessage"]="Bad Request!";
                Redirect_to("Blog.php?page=1");
            }
            }
            while($DataRows=$stmt->fetch()){
                $PostId=$DataRows["id"];
                $DateTime=$DataRows["datetime"];
                $PostTitle=$DataRows["title"];
                $Category=$DataRows["category"];
                $Admin=$DataRows["author"];
                $Image=$DataRows["image"];
                $PostDescription=$DataRows["post"];
        
            ?>
            <div class="card">
                <img src="Uploads/<?php  echo htmlentities($Image);?>" style="max-height:450px;" class="img-fluid card-img-top">
                <div class="card-body">
                    <h4 class="card-title"><?php echo $PostTitle; ?></h4>
                    <small class="text-muted"> Category:<span class="text-dark"> <a href="Blog.php?category=<?php echo htmlentities($Category); ?>"> <?php echo htmlentities($Category); ?> </a></span> Written By <span class="text-dark"><?php echo htmlentities($Admin); ?></span> On <span class="text-dark"><?php echo htmlentities( $DateTime);?></span></small>
                    <span style="float:right;" class="badge badge-dark text-light">Comments:<?php echo ApprCommentsRegPosts($PostId)  ?></span>
                    <hr/>
                    <p class="card-text">
                        <?php 
                        echo htmlentities($PostDescription);?>
                        </p>
                </div>
                <?php } ?>
            </div><br/>
                <!-- Comment part start -->
                <!--Fetching Exciting comments-->
                <span class="Fieldinfo">Comments</span><br/><br/>
                <?php
                global $ConnectingDB;
                $sql="SELECT * FROM comments WHERE post_id='$SearchQueryParameter' AND status='ON' ";
                $stmt=$ConnectingDB->query($sql);
                while($DataRows=$stmt->fetch()){
                    $CommentDate=$DataRows['datetime'];
                    $CommenterName=$DataRows['name'];
                    $CommentContent=$DataRows['comment'];
                
                ?>
                <div>
                    
                    <div class="media CommentBlock">
                    <img src="Images/comment.jpg" alt="Comment Image" class="d-block img-fluid align-self-start">
                        <div class="media-body ml-2">
                        <h6 class="lead"><?php echo $CommenterName; ?></h6>
                        <p class="small"><?php echo $CommentDate; ?></p>
                        <p><?php echo $CommentContent; ?></p>
                        </div>
                    </div>
                </div><hr/>
            <?php }?>

                <!--Fetching Exciting comments end-->
                <div>
                    <form action="FullPost.php?id=<?php echo $SearchQueryParameter; ?>" method="POST">
                    <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="Fieldinfo">Share Your Thoughts About Your Post</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                            <input type="text" class="form-control" name="CommenterName" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                            <input type="email" class="form-control" name="CommenterEmail" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea name="CommenterThoughts" class="form-control" cols="80" rows="6"></textarea>
                        </div>
                        <div>
                            <button type="submit" name="Submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    </div>
                    </form>
                </div>
                <!-- Comment part end -->
            </div>
            <!-- Side Area -->
            <div class="col-sm-4">
                
            </div>
            <!--Side Area end-->
        </div>
                    
    </div>
    
    <!--Header End-->

    
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