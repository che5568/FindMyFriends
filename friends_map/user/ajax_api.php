<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'includes/connect.php';
// Check if the action is to send user location
if (isset($_POST['action']) && $_POST['action'] == 'sendFriendsLocations') {
    // Get user ID from session or wherever you store it
    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];

        // Get latitude and longitude sent from the client-side
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        // Prepare and execute SQL query to update user's location
        $query = "UPDATE users SET latitude = :latitude, longitude = :longitude WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);
        $stmt->bindParam(':user_id', $user_id);

        // Execute the query
        if ($stmt->execute()) {
            $response = ["success" => true, "response" => "Location updated successfully."];
        } else {
            $response = ["success" => false, "response" => "Failed to update location."];
        }
    } else {
        $response = ["success" => false, "response" => "User ID not found in Database."];
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'getFriendsLocations') {
    // Get user ID from session or wherever you store it
    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];


        // Initialize locations array
        $locations = array();

        // Prepare and execute SQL query to get friend locations
        // $query = "SELECT name, latitude, longitude FROM users WHERE id != :user_id";
        $query = "  SELECT
                        u.id,
                        u.name,
                        u.longitude,
                        u.latitude
                    FROM
                        users u,
                        friends f
                    WHERE
                        (
                            u.id = f.user_1_id AND f.user_2_id = :user_id
                        ) OR(
                            u.id = f.user_2_id AND f.user_1_id = :user_id
                        )
                ";


        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Fetch all rows as associative array
        $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Iterate through each friend and store their location in the locations array
        foreach ($friends as $friend) {
            $friend_location = array(
                'id'        => $friend['id'],
                'name'      => $friend['name'],
                'latitude'  => $friend['latitude'],
                'longitude' => $friend['longitude']
            );
            $locations[] = $friend_location;
        }

        $response = $locations;
    } else {
        $response = ["success" => false, "response" => "User ID not found in Database."];
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'declineFriendRequest') {
    
    $request_id = $_POST['request_id'];

    $query = "DELETE FROM friend_requests WHERE id = :request_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':request_id', $request_id);

    if ($stmt->execute()) {
        $response = ["success" => true, "response" => "Request Declined Successfully"];
    } else {
        $response = ["success" => false, "response" => "Error declining friend request."];
    }

} elseif (isset($_POST['action']) && $_POST['action'] == 'acceptFriendRequest') {
    // Get the request ID from the URL
    $request_id = $_POST['request_id'];

    // Get the sender's ID from the session
    $receiver_id = $_SESSION['user_id'];



    $query = "  SELECT
                    *
                FROM 
                    friend_requests
                WHERE 
                    id = {$request_id} 
                AND 
                    `status`='pending'
                AND
                    receiver_id = {$receiver_id}
            ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $friend_requests_count = $stmt->rowCount();

    if ($friend_requests_count > 0) {
        $friend_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sender_id = $friend_requests[0]['sender_id'];
        $response = ["success" => true, "response" => $friend_requests];


        $query = "INSERT INTO friends (user_1_id, user_2_id, uploaded_at, created_at) VALUES (:user_1_id, :user_2_id, NOW(), NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_1_id', $sender_id);
        $stmt->bindParam(':user_2_id', $receiver_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Remove the friend request from the friend_requests table
            $remove_query = "DELETE FROM friend_requests WHERE id = :request_id";
            $remove_stmt = $conn->prepare($remove_query);
            $remove_stmt->bindParam(':request_id', $request_id);
            $remove_stmt->execute();

            $response = ["success" => true, "response" => "Friend added successfully"];
        } else {
            // Error occurred
            $response = ["success" => false, "response" => "Error accepting friend request."];
        }
    } else {
        $response = ["success" => false, "response" => "Error in finding request."];
    }
} else {
    $response = ["success" => false, "response" => "Invalid action."];
}


header('Content-Type: application/json');
echo json_encode($response);
// Closing the database connection