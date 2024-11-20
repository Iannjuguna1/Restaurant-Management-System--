<?php
include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: home1.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle reservation deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $reservation_id = $_GET['delete'];

    // Perform deletion
    $delete_reservation = $conn->prepare("DELETE FROM `reservation` WHERE Id = ?");
    $delete_reservation->execute([$reservation_id]);
    $message[] = 'Reservation Cancelled!';

    // // Redirect back to the same page after deletion
    // header('location: reservation_update.php');
    // exit;
}

// Pagination variables
$recordsPerPage = 12;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $recordsPerPage;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Update</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Additional CSS styles */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #ddd;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 18px;
        }

        th {
            background-color: #f2f2f2;
        }

        .table-container {
            overflow-x: auto;
        }

        /* Center the search form */
        .search-container {
            text-align: center;
            margin: 20px;
        }

        #date-search {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 200px; /* Adjust the width */
            margin-right: 10px;
        }

        #search-btn,
        #print-btn {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #search-btn {
            background-color: #4CAF50;
            color: #fff;
            margin-right: 10px;
        }

        #search-btn:hover {
            background-color: #45a049;
        }

        #print-btn {
            background-color: #008CBA;
            color: #fff;
        }

        #print-btn:hover {
            background-color: #005F6B;
        }

        label {
            font-size: 16px;
            font-style: italic;
            color: green;
        }

        /* Pagination styles */
        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            color: #008CBA;
            padding: 8px 16px;
            text-decoration: none;
            font-size: 18px;
            border: 1px solid #ddd;
            margin-right: 5px;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        .pagination .active {
            background-color: #008CBA;
            color: #fff;
        }
    </style>
</head>

<body>

    <!-- header section starts  -->
    <?php include 'components/user_header1.php'; ?>
    <!-- header section ends -->

    <div class="heading">
        <h3>Reservation Update</h3>
        <p><a href="reservation_summary.php">Reservation Summary</a> <span> / Reservation Update</span></p>
    </div>

    <!-- Add search form at the center middle part -->
    <div class="search-container">
        <form action="" method="POST">
            <label for="date-search">Search by Date:</label>
            <input type="date" name="date_search" id="date-search">
            <input type="submit" value="Search" id="search-btn">
            <!-- <button type="button" id="print-btn">Print</button> -->
        </form>
    </div>

    <section class="orders">

        <h1 class="title">My Reservations</h1>

        <?php
        if (empty($user_id)) {
            echo '<p class="empty">Please login to see your reservations</p>';
        } else {
            // Handle search query
            if (isset($_POST['date_search'])) {
                $date_search = $_POST['date_search'];
                if (!empty($date_search)) {
                    $select_reservations = $conn->prepare("SELECT * FROM `reservation` WHERE user_id = ? AND DATE(date) = ? LIMIT $offset, $recordsPerPage");
                    $select_reservations->execute([$user_id, $date_search]);
                } else {
                    // If no date is selected, fetch all reservations with pagination
                    $select_reservations = $conn->prepare("SELECT * FROM `reservation` WHERE user_id = ? LIMIT $offset, $recordsPerPage");
                    $select_reservations->execute([$user_id]);
                }
            } else {
                // If no search query, fetch all reservations with pagination
                $select_reservations = $conn->prepare("SELECT * FROM `reservation` WHERE user_id = ? LIMIT $offset, $recordsPerPage");
                $select_reservations->execute([$user_id]);
            }

            if ($select_reservations->rowCount() > 0) {
                ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>Time</th>
                                <!-- <th>Name</th> -->
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Party Size</th>
                                <th>Occasion</th>
                                <th>Update</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 1;
                            while ($fetch_reservation = $select_reservations->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td><?= $counter; ?></td>
                                    <td><?= $fetch_reservation['date']; ?></td>
                                    <td><?= $fetch_reservation['time']; ?></td>
                                    <!-- <td><?= $fetch_reservation['Name']; ?></td> -->
                                    <td><?= $fetch_reservation['email']; ?></td>
                                    <td><?= $fetch_reservation['Phone']; ?></td>
                                    <td><?= $fetch_reservation['Party_size']; ?></td>
                                    <td><?= $fetch_reservation['occasion']; ?></td>
                                    <!-- <td style="color:<?php echo ($fetch_reservation['status'] == 'pending') ? 'red' : 'green'; ?>"><?= $fetch_reservation['status']; ?></td> -->
                                    <td>
                                        <a href="reservation_change.php?id=<?= $fetch_reservation['Id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to update this reservation?');">Update</a>
                                    </td>
                                    <td>
                                        <a href="reservation_update.php?delete=<?= $fetch_reservation['Id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this reservation?');">Cancel</a>
                                    </td>
                                </tr>
                                <?php
                                $counter++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <?php

                // Pagination links
                $totalRecords = $conn->query("SELECT COUNT(*) FROM `reservation` WHERE user_id = $user_id")->fetchColumn();
                $totalPages = ceil($totalRecords / $recordsPerPage);

                echo "<div class='pagination'>";
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo "<a href='?page=$i' " . ($i == $page ? "class='active'" : "") . ">" . $i . "</a>";
                }
                echo "</div>";

            } else {
                echo '<p class="empty">No reservations found!</p>';
            }
        }
        ?>

    </section>

    <!-- footer section starts  -->
    <?php include 'components/footer1.php'; ?>
    <!-- footer section ends -->

    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>
