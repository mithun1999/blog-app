<?php
$servername = "localhost";
$username = "root";
$password = "";
try {
   $ConnectingDB = new PDO("mysql:host=$servername;dbname=cms", $username, $password);
   // set the PDO error mode to exception
   $ConnectingDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   //echo "Connected successfully";
   }
catch(PDOException $e)
   {
   echo "Connection failed: " . $e->getMessage();
   }
?>