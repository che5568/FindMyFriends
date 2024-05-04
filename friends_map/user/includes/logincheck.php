<?php
// Check if the user is not logged in and tries to access any page other than the login page or registration page
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    echo "<script>window.location.replace('../index.php')</script>";
    exit;
}
else{
    $my_id = $_SESSION['user_id'];
}
