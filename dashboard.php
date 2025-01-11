<?php
include "service/database.php";
session_start();
$blank_message = "";

// Proses logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

// Proses pengiriman pesan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['discuss'])) {
    $message = $_POST['message'];
    $username = $_SESSION['username'];
    $user_id_query = "SELECT id FROM users WHERE username = '$username'";

    if ($message == "") {
        $_SESSION['message_status'] = "Message cannot be blank!";
        header('Location: dashboard.php');
        exit();
    } else {
        $result = $db->query($user_id_query);
        $user_id = $result->fetch_assoc()['id'];
        $sql = "INSERT INTO posts (content, username, user_id) VALUES ('$message', '$username', '$user_id')";
        
        if ($db->query($sql)) {
            $_SESSION['message_status'] = "Message sent!";
        } else {
            $_SESSION['message_status'] = "Failed to send message!";
        }
        header('Location: dashboard.php');
        exit();
    }
}

// Mengambil semua pesan
$messages = [];
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $db->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbox</title>
    <link rel="stylesheet" href="layout/styles.css">
</head>
<body>
    <div class="notify">
        <span>
            <?php
            if (isset($_SESSION['message_status'])) {
                echo $_SESSION['message_status'];
                unset($_SESSION['message_status']); // Bersihkan pesan setelah ditampilkan
            }
            ?>
            </span>
    </div>
    <div class="chatbox">
        <div class="chatbox-header">
            <span>Chatbox</span>
            <span>Hi, <?= htmlspecialchars($_SESSION["username"]) ?></span>
            <form action="dashboard.php" method="POST">
                <button class="logout-button" type="submit" name="logout">Log Out</button>
            </form>
        </div>
        <div class="chatbox-body">
            <div class="chat-container">
                <?php foreach ($messages as $message): ?>
                    <div class="chat-message">
                        <span class="chat-username"><?= htmlspecialchars($message['username']) ?></span>
                        <span class="chat-content"><?= htmlspecialchars($message['content']) ?></span>
                        <span class="chat-time"><?= htmlspecialchars($message['created_at']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="chatbox-footer">
            <form method="POST" action="dashboard.php">
                <input type="text" placeholder="Masukkan pesan disini..." name="message" />
                <button class="send-button" type="submit" name="discuss">Kirim</button>
            </form>
        </div>
    </div>
</body>
</html>
