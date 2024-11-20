<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home1.php');
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
    <title>Orders</title>

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
 /* Add/update these CSS rules */
 .update-link {
        color: orange;
    }

    .update-link:hover {
        text-decoration: underline; /* Add underline on hover if needed */
        color: green;
    }
    
    .warning {
            font-style: italic; /* Set font style to italic */
            color: red; 
        }

    </style>
</head>

<body>

    <!-- header section starts  -->
    <?php include 'components/user_header1.php'; ?>
    <!-- header section ends -->

    <div class="heading">
        <h3>Reservations</h3>
        <p><a href="reservation.php">Reservation</a> <span> / My Reservations</span></p>
    </div>

    <!-- Add search form at the center middle part -->
    <div class="search-container">
        <form action="" method="POST">
            <label for="date-search">Search by Date:</label>
            <input type="date" name="date_search" id="date-search">
            <input type="submit" value="Search" id="search-btn" >
            <button type="button" id="print-btn">Print</button>
        </form>
    </div>

    <section class="orders">

        <h1 class="title">My Reservations</h1>
        <h2 class="warning">NB:The reservation cannot be altered after 24hrs</h2>

        <?php
        if ($user_id == '') {
            echo '<p class="empty">Please login to see your orders</p>';
        } else {
            // Handle search query
            if (isset($_POST['date_search'])) {
                $date_search = $_POST['date_search'];
                if (!empty($date_search)) {
                    $select_orders = $conn->prepare("SELECT * FROM `reservation` WHERE user_id = ? AND DATE(date) = ? LIMIT $offset, $recordsPerPage");
                    $select_orders->execute([$user_id, $date_search]);
                } else {
                    // If no date is selected, fetch all orders with pagination
                    $select_orders = $conn->prepare("SELECT * FROM `reservation` WHERE user_id = ? LIMIT $offset, $recordsPerPage");
                    $select_orders->execute([$user_id]);
                }
            } else {
                // If no search query, fetch all orders with pagination
                $select_orders = $conn->prepare("SELECT * FROM `reservation` WHERE user_id = ? LIMIT $offset, $recordsPerPage");
                $select_orders->execute([$user_id]);
            }

            if ($select_orders->rowCount() > 0) {
                ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Party Size</th>
                                <th>Occasion</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 1;
                            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td><?= $counter; ?></td>
                                    <td><?= $fetch_orders['date']; ?></td>
                                    <td><?= $fetch_orders['time']; ?></td>
                                    <td><?= $fetch_orders['Name']; ?></td>
                                    <td><?= $fetch_orders['email']; ?></td>
                                    <td><?= $fetch_orders['Phone']; ?></td>
                                    <td><?= $fetch_orders['Party_size']; ?></td>
                                    <td><?= $fetch_orders['occasion']; ?></td>
                                    <td style="color:<?php
                                        if ($fetch_orders['status'] == 'Pending') {
                                            echo 'orange'; // Set color to orange for Pending
                                        } elseif ($fetch_orders['status'] == 'Successful') {
                                            echo 'green'; // Set color to green for Approved
                                        } elseif ($fetch_orders['status'] == 'Failed') {
                                            echo 'red'; // Set color to red for Cancelled
                                        } else {
                                            echo 'black'; // Default color (add any other color if needed)
                                        }
                                    ?>"><?= $fetch_orders['status']; ?></td>

                                    <td>
                                    <?php if ($fetch_orders['action'] == 'Update'): ?>
                                        <a class="update-link" href="reservation_update.php"><?= $fetch_orders['action']; ?></a>
                                    <?php else: ?>
                                        <?= $fetch_orders['action']; ?>
                                    <?php endif; ?>
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

                echo "<div style='text-align: center; margin-top: 20px;'>";
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo "<a href='?page=$i' style='margin-right: 5px;'>" . $i . "</a>";
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

    <!-- Add script for printing -->
    <script>
        // Get the print button
        const printButton = document.getElementById('print-btn');

        // Add click event listener to the print button
        printButton.addEventListener('click', function () {
            // Create a new window with the table content
            const printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Print</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; border: 2px solid #ddd; }');
            printWindow.document.write('th, td { border: 1px solid #ddd; padding: 12px; text-align: left; font-size: 18px; }');
            printWindow.document.write('th { background-color: #f2f2f2; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(document.querySelector('.table-container').innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        });
    </script>

</body>

</html>
