<?php
include_once('includes/connect.php');
include_once('includes/logincheck.php');

// Check if the friend ID is provided in the URL
if(isset($_GET['id'])) {
    // Get the friend ID from the URL
    $friend_id = $_GET['id'];

    // Get the sender's ID from the session
    $sender_id = $_SESSION['user_id'];

    // Prepare and execute the SQL statement to remove the friend request
    $query = "DELETE FROM friend_requests WHERE sender_id = :sender_id AND receiver_id = :receiver_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':sender_id', $sender_id);
    $stmt->bindParam(':receiver_id', $friend_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the friend request list page
        echo "<script>window.location.href='friend_request_list.php'</script>";
    } else {
        // Error occurred
        echo "Error declining friend request.";
    }
} else {
    // Friend ID is not provided
    echo "Friend ID is missing.";
}
?>
