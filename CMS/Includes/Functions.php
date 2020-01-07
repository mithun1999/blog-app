<?php
require_once("Includes/DB.php");

function Redirect_to($New_Location){
    header("Location:".$New_Location);
    exit;
}

function CheckUsernameExists($Username){
    global $ConnectingDB;
    $sql="SELECT username FROM admins WHERE username=:userName";
    $stmt=$ConnectingDB->prepare($sql);
    $stmt->bindvalue(':userName',$Username);
    $stmt->execute();
    $Result=$stmt->rowcount();
    if($Result==1){
        return true;
    }
    else{
        return false;
    }
}

function Login_Attempt($Username,$Password){
    global $ConnectingDB;
        $sql="SELECT * FROM admins WHERE username=:userName AND password=:passWord LIMIT 1";
        $stmt=$ConnectingDB->prepare($sql);
        $stmt->bindvalue(':userName',$Username);
        $stmt->bindvalue(':passWord',$Password);
        $Execute=$stmt->execute();
        $Result=$stmt->rowcount();
        if($Result==1){
            return $Found_Account=$stmt->fetch();
        }else{
            return null;
        }
}

function Confirm_Login(){
    if(isset($_SESSION["UserId"])){
        return true;
    }else{
        $_SESSION["ErrorMessage"]="Login Required!";
        Redirect_to("Login.php");
    }
}

function TotalAdmins(){
    global $ConnectingDB;
    $sql="SELECT COUNT(*) FROM admins ";
    $stmt=$ConnectingDB->query($sql);
    $TotalRows=$stmt->fetch();
    $TotalAdmins=array_shift($TotalRows);
    echo $TotalAdmins;
}

function TotalPosts(){
    global $ConnectingDB;
    $sql="SELECT COUNT(*) FROM posts ";
    $stmt=$ConnectingDB->query($sql);
    $TotalRows=$stmt->fetch();
    $TotalPosts=array_shift($TotalRows);
    echo $TotalPosts;
}

function TotalCategories(){
    global $ConnectingDB;
    $sql="SELECT COUNT(*) FROM category ";
    $stmt=$ConnectingDB->query($sql);
    $TotalRows=$stmt->fetch();
    $TotalCategories=array_shift($TotalRows);
    echo $TotalCategories;
}

function TotalComments(){
    global $ConnectingDB;
    $sql="SELECT COUNT(*) FROM comments ";
    $stmt=$ConnectingDB->query($sql);
    $TotalRows=$stmt->fetch();
    $TotalComments=array_shift($TotalRows);
    echo $TotalComments;
}

function ApprCommentsRegPosts($PostId){
    global $ConnectingDB;
    $sqlApprove="SELECT COUNT(*) FROM comments WHERE post_id='$PostId' AND status='ON' ";
    $stmtApprove=$ConnectingDB->query($sqlApprove);
    $RowsTotal=$stmtApprove->fetch();
    $Total=array_shift($RowsTotal);
    return $Total;
}

function DisApprCommentsRegPosts($PostId){
    global $ConnectingDB;
    $sqlApprove="SELECT COUNT(*) FROM comments WHERE post_id='$PostId' AND status='OFF' ";
    $stmtApprove=$ConnectingDB->query($sqlApprove);
    $RowsTotal=$stmtApprove->fetch();
    $Total=array_shift($RowsTotal);
    return $Total;
}
?>