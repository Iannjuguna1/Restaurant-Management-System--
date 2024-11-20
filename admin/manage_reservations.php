<?php
include '../components/connect.php';

session_start();

// Check if the user is logged in and is an admin (you need to have admin authentication logic)
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
} else {
    header('location:admin_login.php'); // Redirect to admin login page if not logged in
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
    <title>Manage Reservations</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="../css/admin_style.css">

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
        .submit{
            background-color: green;
        }

        /* CSS to style the input button with name="update" */
        input[name="update"] {
            background-color: green; /* Set the background color to green */
            color: white; /* Set the text color to white */
            padding: 10px 20px; /* Add padding for better appearance */
            border: none; /* Remove default border */
            border-radius: 5px; /* Apply border radius for rounded corners */
            cursor: pointer; /* Change cursor to pointer on hover */
        }

        /* Hover effect */
        input[name="update"]:hover {
            background-color: darkgreen; /* Change background color on hover */
        }


    </style>
</head>

<body>

    <!-- Admin header section -->
    <?php include '../components/admin_header.php' ?>

    <div class="heading">
        <h3>Manage Reservations</h3>
    </div>

    <!-- Search form -->
    <div class="search-container">
        <form action="" method="POST">
            <label for="date-search">Search by Date:</label>
            <input type="date" name="date_search" id="date-search">
            <input type="submit" value="Search" id="search-btn">
            <button type="button" id="print-btn">Print</button>
        </form>
    </div>

    <section class="orders">
        <h1 class="title">Reservations List</h1>

        <?php
        // Handle search query
        if (isset($_POST['date_search'])) {
            $date_search = $_POST['date_search'];
            if (!empty($date_search)) {
                $select_orders = $conn->prepare("SELECT * FROM `reservation` WHERE DATE(date) = ? LIMIT $offset, $recordsPerPage");
                $select_orders->execute([$date_search]);
            } else {
                // If no date is selected, fetch all reservations with pagination
                $select_orders = $conn->prepare("SELECT * FROM `reservation` LIMIT $offset, $recordsPerPage");
                $select_orders->execute();
            }
        } else {
            // If no search query, fetch all reservations with pagination
            $select_orders = $conn->prepare("SELECT * FROM `reservation` LIMIT $offset, $recordsPerPage");
            $select_orders->execute();
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
                            <!-- <th>Email</th> -->
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
                                <!-- <td><?= $fetch_orders['email']; ?></td> -->
                                <td><?= $fetch_orders['Phone']; ?></td>
                                <td><?= $fetch_orders['Party_size']; ?></td>
                                <td><?= $fetch_orders['occasion']; ?></td>
                                <td style="color:<?php echo ($fetch_orders['status'] == 'Pending') ? 'red' : 'green'; ?>"><?= $fetch_orders['status']; ?></td>
                                <td>
                                    <!-- Form to change status -->
                                    <form action="update_reservation.php" method="POST">
                                        <!-- Hidden input for reservation_id -->
                                        <input type="hidden" name="Id" value="<?= $fetch_orders['Id']; ?>">


                                        <!-- Select input for updating status -->
                                        
                                        <select name="status" id="status">
                                            <option value="Pending">Pending</option>
                                            <option value="Successful">Successful</option>
                                            <option value="Failed">Failed</option>
                                        </select>

                                        <!-- Submit button to update status -->
                                        <input type="submit" name="update" value="Update Status">
                                    </form>


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
            $totalRecords = $conn->query("SELECT COUNT(*) FROM `reservation`")->fetchColumn();
            $totalPages = ceil($totalRecords / $recordsPerPage);

            echo "<div class='pagination'>";
            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<a href='?page=$i'>" . $i . "</a>";
            }
            echo "</div>";

        } else {
            echo '<p class="empty">No reservations found!</p>';
        }
        ?>

    </section>

    <!-- Admin footer section
    <?php include '../components/admin_footer.php'; ?> -->

    <!-- Custom JS file link -->
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
