<?php
function checkAuth() {
    if (!isset($_SESSION['token'])) {
        header("Location: ../views/login.php");
        exit();
    }
}
