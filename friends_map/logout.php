<?php
session_start();

// Destroy the session data
session_destroy();
session_unset();
// Redirect to the login page or any other page after logout
echo "<script>window.location.replace('index.php')</script>";
exit;
