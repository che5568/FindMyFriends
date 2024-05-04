<?php
// If user is logged in and session is set, and tries to access the login page or registration page, redirect to the home page
if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    echo "<script>window.location.replace('user/user_dashboard.php')</script>";
    exit;
}