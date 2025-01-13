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

$messages = [];
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $created_at = new DateTime($row['created_at']);
        $now = new DateTime();
        $interval = $now->diff($created_at);

        if ($interval->d == 0) {
            // Pesan dikirim hari ini
            $formatted_time = "Today " . $created_at->format('H:i');
        } elseif ($interval->d == 1) {
            // Pesan dikirim kemarin
            $formatted_time = "Yesterday " . $created_at->format('H:i');
        } else {
            // Pesan lebih dari dua hari lalu
            $formatted_time = $created_at->format('d M H:i');
        }

        // Ambil gambar profil pengguna
        $username = $row['username'];
        $profile_pic_query = "SELECT profile_picture FROM users WHERE username = '$username'";
        $profile_pic_result = $db->query($profile_pic_query);
        $profile_pic = $profile_pic_result->fetch_assoc()['profile_picture'];

        // Tambahkan data gambar profil dan waktu yang diformat ke array message
        $row['formatted_time'] = $formatted_time;
        $row['profile_picture'] = $profile_pic; // Menyimpan URL gambar profil
        $messages[] = $row;
    }
}

// Array untuk menyimpan nama-nama pengguna yang terdaftar
$usernames = [];

// Query untuk mendapatkan semua username dari tabel users
$registered_users = "SELECT username FROM users";
$users_result = $db->query($registered_users);

// Mengecek jika query berhasil dan ada hasil
if ($users_result->num_rows > 0) {
    // Loop untuk mengambil setiap baris hasil query
    while ($row = $users_result->fetch_assoc()) {
        // Menyimpan setiap username ke dalam array
        $usernames[] = $row['username'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="layout/styles.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <header class="header">
    <div class="header-container">
        <div class="logo">
            <a href="dashboard.php">
                <img src="assets/weblogo.png" class="logo-image">
            </a>
        </div>
        <nav class="nav-menu">
            <a href="dashboard.php">Home</a>
            <a href="#">Nobar</a>
            <a href="#">Private Message</a>
            <a href="#">Sewa LC</a>
        </nav>
        <div class="user-menu">
            <img src="<?= htmlspecialchars($_SESSION['profile_picture']) ?>" alt="Avatar" class="chat-avatar">
            <span class="username"><?= htmlspecialchars($_SESSION['username']) ?></span>
        </div>
    </div>
</header>


        <!-- Main Content -->
        <main class="main-content">
            <div class ="main-container">
            <div class="left-section">
                 <div class="chatbox">
                <div class="chatbox-header">
                    <h3>CHATBOX</h3>
                    <form action="dashboard.php" method="POST">
                        <button class="logout-button" type="submit" name="logout">Log Out</button>
                    </form>
                </div>
                <div class="chatbox-body">
                <div class="chat-container">
    <?php foreach ($messages as $message): ?>
        <div class="chat-message">
            <img src="<?= htmlspecialchars($message['profile_picture']) ?>" alt="Avatar" class="chat-avatar">
            <div class="chat-content">
                <span class="chat-username"><?= htmlspecialchars($message['username']) ?></span>
                <p class="chat-text"><?= htmlspecialchars($message['content']) ?></p>
            </div>
            <span class="chat-time"><?= htmlspecialchars($message['formatted_time']) ?></span>
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
                <div class = "games">
                    <div class = "games-header">
                        <h3>GAMES</h3>
                    </div>
                    <div class="games-list">
                        <div class="game-item">
                            <img src="assets/valorant.png" alt="Valorant">
                            <a href="https://www.telkomuniversity.ac.id">
                                <i>Valorant</i>
                            </a>
                        </div>
                        <div class="game-item">
                            <img src="assets/steam.png" alt="Steam">
                            <a href="https://www.instagram.com">
                                <i>Steam</i>
                            </a>
                        </div>
                        <div class="game-item">
                            <img src="assets/balatro.png" alt="Balatro">
                            <a href="https://www.facebook.com">
                                <i>Balatro</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Section -->
            <aside class="right-section">
                <div class="announcement">
                    <h3>Announcement</h3>
                    <img src="assets/announcement.png" alt="Announcement Icon" class="announcement-icon">
                    <p>Adam berjalan menuju kamar mandi dengan langkah malas. Pikirannya penuh dengan keinginan untuk kembali ke kasur. Tapi, hari sudah siang dan bau badan mulai terasa. Ia meraih handuk yang tergantung di belakang pintu, lalu masuk ke kamar mandi. Air dingin dari shower menyentuh kulitnya, membuatnya tersadar sejenak. "Segar juga," pikir Adam sambil menggosok tubuh dengan sabun. Bau sabun yang harum memenuhi ruangan. Ia merasa sedikit lebih bersemangat. Setelah selesai, ia mengeringkan tubuh, lalu kembali ke kamarnya. "Mandi itu sebenarnya enak juga," gumam Adam sambil tersenyum kecil. Tapi ya, tetap saja malas di awal!</p>
                </div>
                <div class="users-registered">
                    <h3>Users Registered</h3>
                    <ul>
                    <?php foreach ($usernames as $username): ?>
                        <li><?= htmlspecialchars($username) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>
        </div>
        </main>
    </div>
    <script src="layout/tech.js"></script>
</body>
</html>