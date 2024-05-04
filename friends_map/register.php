<?php
include_once('includes/connect.php');
include_once('includes/logincheck.php'); 

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $re_password = $_POST['re_pass'];

    // Check if any input field is empty
    if (empty($name) || empty($email) || empty($password) || empty($re_password)) {
        $message[] = "                
            <script>
                swal.fire({
                    title: 'Info!',
                    text: 'Please fill in all fields.',
                    icon: 'Info',
                    confirmButtonColor: '#2085d6',
                    confirmButtonText: 'OK'
                })
            </script>";
  
    } else {
        // Check if password and confirm password match
        if ($password != $re_password) {
            $message[] = "                
            <script>
                swal.fire({
                    title: 'Info!',
                    text: 'Password and confirm password do not match.',
                    icon: 'Info',
                    confirmButtonColor: '#2085d6',
                    confirmButtonText: 'OK'
                })
            </script>";
    
        } else {
            try {
                // Check if the user is already registered
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $message[] = "                
                <script>
                    swal.fire({
                        title: 'Info!',
                        text: 'Email is already registered. Please use a different email.',
                        icon: 'Info',
                        confirmButtonColor: '#2085d6',
                        confirmButtonText: 'OK'
                    })
                </script>";
                    
                } else {
                    // Hash the password before storing it in the database
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Insert user data into the 'users' table
                    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->execute();

                    echo "<script>window.location.href='login.php'</script>";

                }

            } catch (PDOException $e) {
                // Display error message if registration fails
                echo "Registration failed: " . $e->getMessage();
            }
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
    <title>Sign Up Form </title>
    
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
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Sign up</h2>
                        <form method="POST" action="" class="register-form" id="register-form">
                            <div class="form-group">
                                <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="name" id="name" placeholder="Your Name"/>
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email" id="email" placeholder="Your Email"/>
                            </div>
                            <div class="form-group">
                                <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="pass" id="pass" placeholder="Password"/>
                            </div>
                            <div class="form-group">
                                <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input type="password" name="re_pass" id="re_pass" placeholder="Repeat your password"/>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signup" id="signup" class="form-submit" value="Register"/>
                            </div>
                        </form>
                    </div>
                    <div class="signup-image">
                        <figure><img src="images/signup-image.jpg" alt="sing up image"></figure>
                        <a href="login.php" class="signup-image-link">I am already member</a>
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