<?php
    include "service/database.php";
    session_start();
    $login_message="";
    $register_message="";
    if(isset($_SESSION["is_Login"])){
        header("Location: dashboard.php");
    }
    if(isset($_POST["register"])){
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        if ($username == "" || $password == "" || $email == "") {
            $register_message = "Unregistered, Please fill all the fields!";
        } else {
            try {
                $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
                if($db->query($sql)){
                    $register_message = "Register Success!";
                }else{
                    $register_message = "Register Failed!";
                }
            } catch(mysqli_sql_exception){
                $register_message = "Account is Available!";
            }
            
            $db->close();
        }
    }
    if(isset($_POST["login"])){
        $email = $_POST["email"];
        $password = $_POST["password"];
        if ($email == "" || $password == "") {
            $login_message = "Please fill all the fields!";
        } else {
            $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
            $result = $db->query($sql);
            if($result->num_rows > 0){
                $data = $result->fetch_assoc();
                $_SESSION["username"] = $data["username"];
                $_SESSION["is_Login"] = true;
                header("Location: dashboard.php");
            }else{
                $login_message = "Credentials Doesnt Match!";
            }
            $db->close();
        }
    }
?>

<?php
    include "layout/index.html";
?>