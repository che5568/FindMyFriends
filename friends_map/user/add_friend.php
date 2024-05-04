<?php
include_once('includes/connect.php');
include_once('includes/logincheck.php');

// Get the receiver_id from the URL
if(isset($_GET['receiver_id'])) {
    $receiver_id = $_GET['receiver_id'];

    // Prepare and execute SQL to fetch the name of the receiver
    $query = "SELECT name FROM users WHERE id = :receiver_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':receiver_id', $receiver_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Get the sender_id from the session
        $sender_id = $_SESSION['user_id']; 

        // Prepare and execute SQL to insert friend request into the friend_requests table
        $query = "INSERT INTO friend_requests (sender_id, receiver_id, status, uploaded_at, created_at) VALUES (:sender_id, :receiver_id, :status, NOW(), NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':sender_id', $sender_id);
        $stmt->bindParam(':receiver_id', $receiver_id);
        $status = 'pending'; // Initial status for friend request
        $stmt->bindParam(':status', $status);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>window.location.href='find_friend.php'</script>"; 
        } else {
            // Error occurred
            echo "Error sending friend request.";
        }
    } else {
        echo "User not found."; // Handle case where user data is not found
    }
} else {
    echo "Receiver ID not provided."; // Handle case where receiver_id is not provided in the URL
}
?>
