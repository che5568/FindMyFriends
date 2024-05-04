<?php
require_once('includes/connect.php');
include_once('includes/logincheck.php'); 

if (!isset($_SESSION['token'])) {
    $message[]="<script>
        swal.fire({
            title: 'Warning!',
            text: 'Token is empty. Please check your email for the token.',
            icon: 'warning',
            confirmButtonColor: '#2085d6',
            confirmButtonText: 'OK',
            timer: 1500
        })
        </script>";
        echo "<script>window.location.href = 'login.php';</script>";

} else {
    $token = $_SESSION['token'];
    
    if (isset($_POST['reset_password'])) {
        $newPassword = $_POST['pass'];
        $confirmPassword = $_POST['confirm_pass'];

        if (!empty($newPassword) && !empty($confirmPassword)) {
            if ($newPassword == $confirmPassword) {
                try {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    
                    $stmt = $conn->prepare("UPDATE users SET password = :password, token = NULL WHERE token = :token");
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->bindParam(':token', $token);
                    $stmt->execute();
                    
                    // Unset the token session
                    unset($_SESSION['token']);

                    $message[]="<script>
                    swal.fire({
                        title: 'Success!',
                        text: 'Password updated successfully!',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    })
                </script>";
                    echo "<script>window.location.href = 'login.php';</script>";
                } catch (PDOException $e) {
                    echo "Error updating record: " . $e->getMessage();
                }
            } else {
                $message[]="<script>
                    swal.fire({
                        title: 'Info!',
                        text: 'Updated passwords do not match.',
                        icon: 'info',
                        confirmButtonColor: '#2085d6',
                        confirmButtonText: 'OK'
                    })
                </script>";
            }
        } else {
            $message[]="<script>
                swal.fire({
                    title: 'Warning!',
                    text: 'Please enter both new password and confirm password.',
                    icon: 'warning',
                    confirmButtonColor: '#2085d6',
                    confirmButtonText: 'OK'
                })
            </script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<?php
 include_once('includes/header.php');
 ?>
<title>Reset Password Form</title>
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
        <!-- Reset password form -->
        <section class="signup mx-auto">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Reset Password</h2>
                        <form method="POST" class="register-form" id="register-form">
                            <div class="form-group mx-auto">
                                <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="pass" id="pass" placeholder="Password"/>
                            </div>
                            <div class="form-group mx-auto">
                                <label for="confirm_pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="confirm_pass" id="confirm_pass" placeholder="Confirm Password"/>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="reset_password" id="reset_password" class="form-submit" value="Reset Password"/>
                            </div>
                        </form>
                    </div>
                    <div class="signin-image">
                        <figure><img src="images/signin-image.jpg" alt="sing up image"></figure>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/swal/sweetalert2.min.js"></script>
</body>
</html>
