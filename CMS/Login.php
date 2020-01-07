<?php
require_once("Includes/DB.php");
require_once("Includes/Functions.php");
require_once("Includes/Sessions.php");
?>
<?php
if(isset($_SESSION["UserId"])){
    Redirect_to(Dashboard.php);
}

if(isset($_POST["Submit"])){
    $Username=$_POST["Username"];
    $Password=$_POST["Password"];
    if(empty($Username)||empty($Password)){
        $_SESSION["ErrorMessage"]="All fields must be filled out";
        Redirect_to("Login.php");
    }else{
        $Found_Account=Login_Attempt($Username,$Password);
        if($Found_Account){
            $_SESSION["UserId"]=$Found_Account["id"];
            $_SESSION["Username"]=$Found_Account["username"];
            $_SESSION["AdminName"]=$Found_Account["aname"];
            $_SESSION["SuccessMessage"]="Welcome ".$_SESSION["AdminName"]."!";
            if(isset($_SESSION["TrackingURL"])){
                Redirect_to($_SESSION["TrackingURL"]);;
            }else{
            Redirect_to("Dashboard.php");
            }
        }else{
            $_SESSION["ErrorMessage"]="Incorrect Username/Password";
            Redirect_to("Login.php");
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

    <title>Login Page</title>
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
                    <h1></h1>
                </div>
            </div>
        </div>
    </header>
    
    <!--Header End-->
    <!-- Main Area -->
    <section class="container">
        <div class="row">
            <div class="col-sm-6 offset-sm-3" style="min-height:495px;"><br/><br/>
            <?php
                echo ErrorMessage();
                echo SuccessMessage();
            ?>    
                <div class="card bg-secondary text-light">
                    <div class="card-header">
                        <h4>Welcome Back!</h4>
                    </div>
                    <div class="card-body bg-dark">
                        <form action="Login.php" method="POST">
                            <div class="form-group">
                                <label for="username"><span class="Fieldinfo">Username:</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-white bg-info"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="Username" id="username">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password"><span class="Fieldinfo">Password:</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-white bg-info"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" name="Password" id="password">
                                </div>
                            </div>
                            <input type="submit" value="Login" class="btn btn-info btn-block" name="Submit">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Area End-->
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