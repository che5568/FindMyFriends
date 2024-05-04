<?php
include_once('includes/connect.php');
include_once('includes/logincheck.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Friend List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://netdna.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/user_profile.css">
  <link rel="stylesheet" href="assets/css/find_friend.css">
</head>

<body>
  <div class="container">
    <?php include_once('includes/navbar_profile.php'); ?>
    <div class="row text-center justify-content-center">
      <div class="col-md-8">
        <div class="people-nearby">
          <?php
          $user_id = $_SESSION["user_id"];

          // Fetch user data who are not friends or in friend requests
          $query = "  SELECT 
                        u.id, u.name, u.email
                      FROM 
                        users u
                      LEFT JOIN 
                        friend_requests fr1 ON u.id = fr1.sender_id AND fr1.receiver_id = :user_id
                      LEFT JOIN 
                        friend_requests fr2 ON u.id = fr2.receiver_id AND fr2.sender_id = :user_id
                      LEFT JOIN 
                        friends f1 ON u.id = f1.user_1_id AND f1.user_2_id = :user_id
                      LEFT JOIN 
                        friends f2 ON u.id = f2.user_2_id AND f2.user_1_id = :user_id
                      WHERE 
                        fr1.receiver_id IS NULL 
                      AND 
                        fr2.sender_id IS NULL 
                      AND 
                        f1.user_1_id IS NULL 
                      AND 
                        f2.user_2_id IS NULL
                      AND 
                        u.id != :user_id
                  ";

          $stmt = $conn->prepare($query);
          $stmt->bindParam(':user_id', $user_id);
          $stmt->execute();
          $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

          // Check if user data is fetched successfully
          if ($users) {
            foreach ($users as $user) {
              $user_id = $user['id']; // Get user id
              $user_name = $user['name']; // Get user name
              // Output HTML for each user
          ?>
              <div class="nearby-user">
                <div class="row">
                  <div class="col-md-2 col-sm-2">
                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="user" class="profile-photo-lg">
                  </div>
                  <div class="col-md-7 col-sm-7">
                    <h5><a href="#" class="profile-link"><?php echo htmlspecialchars($user_name); ?></a></h5>
                    <p class="text-muted">500m away</p>
                  </div>
                  <div class="col-md-3 col-sm-3">
                    <!-- Add Friend button with user id sent in URL -->
                    <a href="add_friend.php?receiver_id=<?php echo htmlspecialchars($user_id); ?>" class="btn btn-primary pull-right">Add Friend</a>
                  </div>
                </div>
              </div>
          <?php
            }
          } else {
            // If no users found, display a message
            echo "No users found.";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="https://netdna.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
  <script type="text/javascript">

  </script>
</body>

</html>
