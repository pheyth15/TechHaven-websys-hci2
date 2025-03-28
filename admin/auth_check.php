<?php
session_start();

// Function to check if the user is logged in and is a super admin
function checkAuth() {
    if (!isset($_SESSION['loggedin'])) {
        header('Location: login.php');
        exit();
    }

    // Check if user is not a super admin
    if ($_SESSION['role'] !== 'admin') {
        // Log out the user and redirect to login page
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    }
}
?>