<?php
include 'components/connect.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location: home1.php');
    exit;
}

// Check if form is submitted with POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $reservation_id = $_POST['reservation_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $party_size = $_POST['party_size']; // Corrected form field name to match HTML
    $occasion = $_POST['occasion'];
    $status = $_POST['status'];

    // Update reservation in the database
    $update_reservation = $conn->prepare("UPDATE `reservation` SET date = ?, time = ?, Name = ?, email = ?, Phone = ?, party_size = ?, occasion = ?, status = ? WHERE Id = ?");
    $update_reservation->execute([$date, $time, $name, $email, $phone, $party_size, $occasion, $status, $reservation_id]);

    // Redirect to reservation_update.php after updating
    header('location: reservation_summary.php');
    exit;
} else {
    // If not submitted via POST method, redirect to reservation_update.php
    header('location: reservation_update.php');
    exit;
}
?>
