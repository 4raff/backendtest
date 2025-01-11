<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database_name = "myweb";

    $db = mysqli_connect($hostname, $username, $password, $database_name);

    if($db->connect_error){
        echo "Database Connection Failed!";
        die("error!");
    }
?>