<?php
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/Sessions.php");
?>
<?php
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
Confirm_Login();
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

    <title>Comments</title>
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
                    <h1><i class="fas fa-comment"></i>Manage Comments</h1>
                </div>
            </div>
        </div>
    </header>
    
    <!--Header End-->

    <section class="container py-2 mb-4">
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <h2>Un-Approved Comments</h2>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Date & Time</th>
                            <th>Name</th>
                            <th>Comment</th>
                            <th>Approve</th>
                            <th>Delete</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                
                <?php
                global $ConnectingDB;
                $sql="SELECT * FROM comments WHERE status='OFF' ORDER BY id desc ";
                $Execute=$ConnectingDB->query($sql);
                $SrNo=0;
                while($DataRows=$Execute->fetch()){
                    $CommentId=$DataRows["id"];
                    $DateTimeOfComment=$DataRows["datetime"];
                    $CommenterName=$DataRows["name"];
                    $CommentContent=$DataRows["comment"];
                    $CommentPostId=$DataRows["post_id"];
                    $SrNo++;
                ?>
                <tbody>
                    <tr>
                        <td><?php echo htmlentities($SrNo); ?></td>
                        <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                        <td><?php echo htmlentities($CommenterName); ?></td>
                        <td><?php echo htmlentities($CommentContent); ?></td>
                        <td><a href="ApproveComments.php?id=<?php echo $CommentId; ?>" class="btn btn-success">Approve</a></td>
                        <td><a href="DeleteComments.php?id=<?php echo $CommentId; ?>" class="btn btn-danger">Delete</a></td>
                        <td style="min-width:140px;"><a href="FullPost.php?id=<?php echo $CommentPostId; ?>" target="_blank" class="btn btn-primary">Live Preview</a></td>
                    </tr>
                </tbody>
                <?php } ?>
                </table>

                <h2>Approved Comments</h2>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Date & Time</th>
                            <th>Name</th>
                            <th>Comment</th>
                            <th>Revert</th>
                            <th>Delete</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                
                <?php
                global $ConnectingDB;
                $sql="SELECT * FROM comments WHERE status='ON' ORDER BY id desc ";
                $Execute=$ConnectingDB->query($sql);
                $SrNo=0;
                while($DataRows=$Execute->fetch()){
                    $CommentId=$DataRows["id"];
                    $DateTimeOfComment=$DataRows["datetime"];
                    $CommenterName=$DataRows["name"];
                    $CommentContent=$DataRows["comment"];
                    $CommentPostId=$DataRows["post_id"];
                    $SrNo++;
                ?>
                <tbody>
                    <tr>
                        <td><?php echo htmlentities($SrNo); ?></td>
                        <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                        <td><?php echo htmlentities($CommenterName); ?></td>
                        <td><?php echo htmlentities($CommentContent); ?></td>
                        <td style="min-width:140px;"><a href="DisApproveComments.php?id=<?php echo $CommentId; ?>" class="btn btn-warning">Dis-Approve</a></td>
                        <td><a href="DeleteComments.php?id=<?php echo $CommentId; ?>" class="btn btn-danger">Delete</a></td>
                        <td style="min-width:140px;"><a href="FullPost.php?id=<?php echo $CommentPostId; ?>" target="_blank" class="btn btn-primary">Live Preview</a></td>
                    </tr>
                </tbody>
                <?php } ?>
                </table>
            </div>
        </div>
    </section>
    
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