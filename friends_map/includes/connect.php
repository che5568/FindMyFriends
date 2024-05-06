<?php 
session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
   $db_name = 'mysql:host=localhost;dbname=friends_map';
   $db_user_name = ''; // Your MySQL username
$db_user_pass = ''; // Your MySQL password


   try {
      $conn = new PDO($db_name, $db_user_name, $db_user_pass);
    //   echo "Connected to database successfully!";
  } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
       
    }
?>

