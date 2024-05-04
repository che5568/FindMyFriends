<?php
include_once('includes/connect.php'); 
include_once('includes/logincheck.php'); 

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signin'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['your_pass'];
    
    // Check if any input field is empty
    if (empty($email) || empty($password)) {
        $message[]= "<script>
        swal.fire({
            title: 'Warnig!',
            text: 'Please fill in all fields.',
            icon: 'error',
            confirmButtonColor: '#1085d6',
            confirmButtonText: 'OK'
        });
    </script>";
        
    } else {
        try {
            // Prepare SQL statement to fetch user data from the 'users' table
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Check if user exists and password is correct
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION["login"] = true;
                $_SESSION["user_name"] = $user["name"]; // Store user's name in session
                $_SESSION["user_id"] = $user["id"]; // Store user's id in session
                echo "<script>window.location.href='user/user_dashboard.php'</script>"; 
                           
            } else {
                $message[]= "<script>
                    swal.fire({
                        title: 'Error!',
                        text: 'Invalid email or password.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                </script>";
            }
        } catch (PDOException $e) {
            // Display error message if login fails
            
            echo "Login failed: " . $e->getMessage();
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

<title>Sign In Form</title>
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
        <!-- Sign in Form -->
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure><img src="images/signin-image.jpg" alt="sign up image"></figure>
                        <a href="register.php" class="signup-image-link">Create an account</a>
                    </div>

                    <div class="signin-form">
                        <h2 class="form-title">Sign In</h2>
                        <form method="POST" action="" class="register-form" id="login-form">
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-account material-icons-email"></i></label>
                                <input type="text" name="email" id="email" placeholder="Your Email"/>
                            </div>
                            <div class="form-group">
                                <label for="your_pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="your_pass" id="your_pass" placeholder="Password"/>
                            </div>
                            <div class="form-group">
                                <a href="forgot.php" class="signup-image-link text-start">Forget Password</a>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signin" id="signin" class="form-submit" value="Log in"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="assets/js/main.js"></script>
    
  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

  <script src="assets/js/isotope.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/wow.js"></script>
  <script src="assets/js/tabs.js"></script>
  <script src="assets/js/popup.js"></script>
  <script src="assets/js/custom.js"></script>
</body>
</html>
