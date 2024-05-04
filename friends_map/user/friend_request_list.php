<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('includes/connect.php');
include_once('includes/logincheck.php');


$query = "  SELECT
                friend_requests.id AS request_id,
                friend_requests.sender_id,
                friend_requests.receiver_id,
                friend_requests.status,
                users.id AS user_id,
                users.name AS sender_name
            FROM 
                friend_requests,
                users
            WHERE 
                receiver_id = {$_SESSION['user_id']} 
            AND 
                friend_requests.status='pending'
            AND
                friend_requests.sender_id = users.id
        ";

$stmt = $conn->prepare($query);
$stmt->execute();
$friend_requests_count = $stmt->rowCount();
$friend_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Friend Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://getbootstrap.com/examples/jumbotron-narrow/jumbotron-narrow.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/user_profile.css">
    <link rel="stylesheet" href="assets/css/friend_list.css">
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
</head>

<body>
    <div class="container bootstrap snippets bootdey">
        <?php
        include_once('includes/navbar_profile.php');
        ?>
        <div class="header">
            <h3 class="text-muted prj-name">
                <span class="fa fa-users fa-2x principal-title"></span>
                Friend zone
            </h3>
        </div>
        <div class="jumbotron list-content">
            <ul class="list-group">
                <li href="#" class="list-group-item title">
                    Friend Requests List
                </li>
                <?php
                // Check if the user has friend requests
                if (empty($friend_requests)) {
                    // If no friend requests, display a message
                    echo '<li href="#" class="list-group-item text-center">You have no friend requests.</li>';
                } else {
                    // Loop through friend requests
                    foreach ($friend_requests as $request) : ?>
                        <li href="#" class="list-group-item d-flex align-items-center">
                            <img class="img-thumbnail me-2" src="https://bootdey.com/img/Content/User_for_snippets.png" alt="Friend avatar">
                            <label class="name flex-grow-1">
                                <label class="name">
                                    <?php echo $request['sender_name']; ?><br>
                                </label>
                            </label>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="acceptRequest btn btn-success mx-1" data-id="<?php echo $request['request_id']; ?>"><i class="fa fa-check fa-lg mx-3 text-center"></i></button>
                                <button type="button" class="rejectRequest btn btn-danger mx-1" data-id="<?php echo $request['request_id']; ?>"><i class="fa fa-trash fa-lg mx-3 text-center"></i></button>
                            </div>
                        </li>
                <?php endforeach;
                } ?>
                <!-- <li href="#" class="list-group-item text-center">
                    <a class="btn  btn-primary btn-block p-5 ">
                        <i class="fa fa-arrow-clockwise"></i>
                        Load more...
                    </a>
                </li> -->
            </ul>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".acceptRequest").click(function() {
                var request_id = $(this).data("id");
                if (confirm("Are you sure you want to confirm the friend request ?")) {
                    $.ajax({
                        method: 'POST',
                        url: 'ajax_api.php',
                        dataType: 'JSON',
                        data: {
                            action: 'acceptFriendRequest',
                            request_id: request_id
                        },
                        dataType: 'json',
                        success: function(data) {
                            if(data.success)
                            {
                                alert(data.response);
                                location.reload();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                }
            });
            $(".rejectRequest").click(function() {
                var request_id = $(this).data("id");
                if (confirm("Are you sure you want to decline the friend request ?")) {
                    $.ajax({
                        method: 'POST',
                        url: 'ajax_api.php',
                        dataType: 'JSON',
                        data: {
                            action: 'declineFriendRequest',
                            request_id: request_id
                        },
                        dataType: 'json',
                        success: function(data) {
                            if(data.success)
                            {
                                alert(data.response);
                                location.reload();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>