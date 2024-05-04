<?php
require_once('includes/connect.php');
include_once('includes/logincheck.php'); 

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['forget_password'])) {
  $email = $_POST['email'];

  if (!empty($email)) {
    try {
      // Prepare SQL statement to fetch user data from the 'users' table
      $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
      $stmt->bindParam(':email', $email);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        // Generate a random password reset token
        $tokenLength = 32;
        $randomToken = bin2hex(random_bytes($tokenLength / 2));
        $_SESSION['token']=$randomToken;

        // Update the token in the database for the specific user
        $stmt = $conn->prepare("UPDATE users SET token = :token WHERE email = :email");
        $stmt->bindParam(':token', $randomToken);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        // Set up SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'test@mail.com'; // SMTP username
        $mail->Password = 'password'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('test@mail.com');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Password Reset";
        $resetLink = 'http://localhost/friends_map/reset.php?token=' . $randomToken;
        $mail->Body = '<a href="' . $resetLink . '">Click here to reset your password</a>';

        // Send the email
        $mail->send();
        $message[] = "                
        <script>
            swal.fire({
                title: 'Success!',
                text: 'Email Sent to Your Account',
                icon: 'Success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            })
        </script>";
        echo "<script>window.location.href='login.php'</script>";
      } else {
        $message[] = "                
        <script>
            swal.fire({
                title: 'Info!',
                text: 'Account with this Email does not exist.',
                icon: 'Info',
                confirmButtonColor: '#2085d6',
                confirmButtonText: 'OK'
            })
        </script>";
      }
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  } else {
    $message[] = "             
        <script>
            swal.fire({
                title: 'Info!',
                text: 'Fill up fields First.',
                icon: 'Info',
                confirmButtonColor: '#2085d6',
                confirmButtonText: 'OK'
            })
        </script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <?php
 include_once('includes/header.php');
 ?>
  <title>Forget Password Form </title>
</head>

<body>
<?php

if(isset($message)){
   foreach($message as $message){
      echo $message;
   }
}
?>
  <div class="main">

    <!-- Sign up form -->
    <section class="signup mx-auto"> <!-- Add mx-auto class for horizontal centering -->
      <div class="container">
        <div class="signup-content">
          <div class="signup-form">

            <h2 class="form-title ">Forget Password</h2>
            <form method="POST" class="register-form" id="register-form">
              <div class="form-group mx-auto">
                <label for="email"><i class="zmdi zmdi-lock"></i></label>
                <input type="email" name="email" id="email" placeholder="Enter Your Email" />
              </div>
              <div class="form-group form-button">
                <input type="submit" name="forget_password" id="forget_password" class="form-submit" value="Forget Password" />
              </div>
              <div class="form-group">
                <a href="register.php" class="text-start text-decoration-none">Create an account</a>
              </div>
            </form>
          </div>
          <div class="signin-image">
            <figure><img src="images/signin-image.jpg" alt="sing up image"></figure>
          </div>
        </div>
      </div>
    </section>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/swal/sweetalert2.min.js"></script>
</body>

</html>