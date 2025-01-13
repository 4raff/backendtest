<?php
include "service/database.php";
session_start();
$login_message = "";
$register_message = "";

// Cek apakah pengguna sudah login, jika ya, arahkan ke dashboard
if (isset($_SESSION["is_Login"])) {
    header("Location: dashboard.php");
    exit();
}

// Proses pendaftaran
if (isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    if ($username == "" || $password == "" || $email == "") {
        $_SESSION['register_message'] = "Unregistered, Please fill all the fields!";
    } else {
        // Generate a unique avatar using RoboHash
        $avatar_url = 'https://robohash.org/' . urlencode($username);

        try {
            $sql = "INSERT INTO users (username, password, email, profile_picture) VALUES ('$username', '$password', '$email', '$avatar_url')";
            if ($db->query($sql)) {
                $_SESSION['register_message'] = "Register Success!";
            } else {
                $_SESSION['register_message'] = "Register Failed!";
            }
        } catch (mysqli_sql_exception) {
            $_SESSION['register_message'] = "Account is Available!";
        }
    }
    header('Location: index.php');
    exit();
}

// Proses login
if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($email == "" || $password == "") {
        $_SESSION['login_message'] = "Please fill all the fields!";
    } else {
        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $_SESSION["username"] = $data["username"];
            $_SESSION["profile_picture"] = $data["profile_picture"];
            $_SESSION["is_Login"] = true;
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['login_message'] = "Credentials Doesn't Match!";
        }
    }
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="layout/styleindex.css">
    <title>Website</title>
</head>

<body>
    
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="index.php" method="post">
                <h1>Create Account</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                
                <!-- Display register message from session -->
                <i>
                    <?php
                    if (isset($_SESSION['register_message'])) {
                        echo $_SESSION['register_message'];
                        unset($_SESSION['register_message']); // Hapus pesan setelah ditampilkan
                    }
                    ?>
                </i>
                
                <input type="text" name="username" placeholder="Name">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <button type="submit" name="register">Sign Up</button>
            </form>
        </div>
        
        <div class="form-container sign-in">
            <form action="index.php" method="post">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email password</span>
                <i>
                    <?php
                    if (isset($_SESSION['login_message'])) {
                        echo $_SESSION['login_message'];
                        unset($_SESSION['login_message']); // Hapus pesan setelah ditampilkan
                    }
                    ?>
                </i>
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <a href="#">Forget Your Password?</a>
                <button type="submit" name="login">Sign In</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="layout/script.js"></script>
</body>

</html>
