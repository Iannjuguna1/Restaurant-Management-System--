<?php
include '../components/connect.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if reservation_id and status are set in POST data
    if (isset($_POST['Id'], $_POST['status'])) {
        // Sanitize and store posted data
        $reservation_id = $_POST['Id']; // Change to 'Id'
        $status = $_POST['status'];

        // Prepare and execute SQL update query
        $update_query = $conn->prepare("UPDATE `reservation` SET `status` = ? WHERE `Id` = ?");
        $update_query->execute([$status, $reservation_id]);

        // Check if update was successful
        if ($update_query->rowCount() > 0) {
            // Redirect back to manage_reservations.php with success message
            header('Location: manage_reservations.php?message=Status updated successfully');
            exit;
        } else {
            // Redirect back to manage_reservations.php with error message
            header('Location: manage_reservations.php?error=Failed to update status');
            exit;
        }
    } else {
        // Redirect back to manage_reservations.php with error message if required data is missing
        header('Location: manage_reservations.php?error=Missing data');
        exit;
    }
} else {
    // Redirect back to manage_reservations.php if form is not submitted directly
    header('Location: manage_reservations.php');
    exit;
}
?>
