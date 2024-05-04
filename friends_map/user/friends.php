<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('includes/connect.php');
include_once('includes/logincheck.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Profile_Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets\css\user_profile.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>
  <div class="container">
    <?php
    include_once('includes/navbar_profile.php');

    $query = "  SELECT 
                  * 
                FROM 
                  friends 
                WHERE 
                  user_1_id = {$my_id}
                OR
                  user_2_id = {$my_id}
                ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $friends_count = $stmt->rowCount();

    if ($friends_count > 0) {
      $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    ?>
    <div class="tab-pane fade show active" id="pills-friends" role="tabpanel" aria-labelledby="pills-friends-tab" tabindex="0">
      <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
        <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">Friends <span class="badge text-bg-secondary fs-2 rounded-4 py-1 px-2 ms-2"><?php echo $friends_count; ?></span></h3>
        <form class="position-relative">
          <input type="text" class="form-control search-chat py-2 ps-5" id="text-srh" placeholder="Search Friends">
          <i class="fa fa-search fa-lg position-absolute top-50 start-0 translate-middle-y text-dark ms-3"></i>
        </form>
      </div>
      <div class="row">
        <?php
        if (isset($friends)) {
          // Loop through each friend and display their information
          foreach ($friends as $friend) {
            $friend_id = $friend['user_2_id'];
            // Determine which user ID corresponds to the friend
            $query = "SELECT name FROM users WHERE id = :friend_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':friend_id', $friend_id);
            $stmt->execute();
            $friend_info = $stmt->fetch(PDO::FETCH_ASSOC);
            // Display friend's information
        ?>
            <div class="col-sm-6 col-lg-4">
              <div class="card hover-img">
                <div class="card-body p-4 text-center border-bottom">
                  <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt class="rounded-circle mb-3" width="80" height="80">
                  <h5 class="fw-semibold mb-0"><?php echo $friend_info['name']; ?></h5>
                </div>
              </div>
            </div>
        <?php
          }
        } else {
          // If the user has no friends, display a message
          echo '<div class="col-12">You have no friends.</div>';
        }
        ?>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript">

  </script>
</body>

</html>