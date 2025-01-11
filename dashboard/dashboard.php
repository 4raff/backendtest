<?php
    session_start();
    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: ../index.php'); 
    }
?>

<?php
    include "dashboard.html";
?>