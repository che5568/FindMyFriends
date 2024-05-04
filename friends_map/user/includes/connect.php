<?php 
session_start();

   $db_name = 'mysql:host=localhost;dbname=friends_map';
    $db_user_name = 'che5568'; // Your MySQL username
	$db_user_pass = 'M@stercheif1177'; // Your MySQL password

   try {
      $conn = new PDO($db_name, $db_user_name, $db_user_pass);
    //   echo "Connected to database successfully!";
  } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
        }
?>

