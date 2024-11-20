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

// Fetch total number of orders for the user
$totalRecordsQuery = $conn->prepare("SELECT COUNT(*) FROM `orders` WHERE user_id = ?");
$totalRecordsQuery->execute([$user_id]);
$totalRecords = $totalRecordsQuery->fetchColumn();

// Calculate total pages based on total records and records per page
$totalPages = ceil($totalRecords / $recordsPerPage);

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
    </style>
</head>

<body>

    <!-- header section starts  -->
    <?php include 'components/user_header1.php'; ?>
    <!-- header section ends -->

    <div class="heading">
        <h3>Orders</h3>
        <p><a href="home1.php">Home</a> <span> / Orders</span></p>
    </div>

    <!-- Add search form at the center middle part -->
    <div class="search-container">
        <form action="" method="POST">
            <label for="date-search">Search by Date:</label>
            <input type="date" name="date_search" id="date-search">
            <input type="submit" value="Search" id="search-btn">
            <button type="button" id="print-btn">Print</button>
        </form>
    </div>

    <section class="orders">

        <h1 class="title">My Orders</h1>

        <?php
        if ($user_id == '') {
            echo '<p class="empty">Please login to see your orders</p>';
        } else {
            // Handle search query
            if (isset($_POST['date_search'])) {
                $date_search = $_POST['date_search'];
                if (!empty($date_search)) {
                    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? AND DATE(placed_on) = ? LIMIT $offset, $recordsPerPage");
                    $select_orders->execute([$user_id, $date_search]);
                } else {
                    // If no date is selected, fetch all orders with pagination
                    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? LIMIT $offset, $recordsPerPage");
                    $select_orders->execute([$user_id]);
                }
            } else {
                // If no search query, fetch all orders with pagination
                $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? LIMIT $offset, $recordsPerPage");
                $select_orders->execute([$user_id]);
            }

            if ($select_orders->rowCount() > 0) {
                ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Placed On</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Number</th>
                                <th>Address</th>
                                <th>Payment Method</th>
                                <th>Your Orders</th>
                                <th>Total Price</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = $offset + 1; // Start counter from the correct number
                            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td><?= $counter; ?></td>
                                    <td><?= $fetch_orders['placed_on']; ?></td>
                                    <td><?= $fetch_orders['name']; ?></td>
                                    <td><?= $fetch_orders['email']; ?></td>
                                    <td><?= $fetch_orders['number']; ?></td>
                                    <td><?= $fetch_orders['address']; ?></td>
                                    <td><?= $fetch_orders['method']; ?></td>
                                    <td><?= $fetch_orders['total_products']; ?></td>
                                    <td>ksh/-<?= $fetch_orders['total_price']; ?></td>
                                    <td style="color:<?php echo ($fetch_orders['payment_status'] == 'pending') ? 'red' : 'green'; ?>"><?= $fetch_orders['payment_status']; ?></td>
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
                echo "<div class='pagination'>";
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo "<a href='?page=$i' " . ($i == $page ? "class='active'" : "") . ">" . $i . "</a>";
                }
                echo "</div>";

            } else {
                echo '<p class="empty">No orders found!</p>';
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
