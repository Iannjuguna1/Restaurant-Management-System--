<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

// Fetch completed orders with basic details (no product details)
$completed_orders_query = $conn->prepare("
    SELECT *
    FROM orders
    WHERE payment_status = 'completed'
");
$completed_orders_query->execute();
$completed_orders = $completed_orders_query->fetchAll(PDO::FETCH_ASSOC);

// Fetch pending orders
$pending_orders_query = $conn->prepare("
    SELECT * 
    FROM orders 
    WHERE payment_status = 'pending'
");
$pending_orders_query->execute();
$pending_orders = $pending_orders_query->fetchAll(PDO::FETCH_ASSOC);

// Function to compute grand total
function computeGrandTotal($orders) {
    $grandTotal = 0;
    foreach ($orders as $order) {
        $grandTotal += $order['total_price'];
    }
    return $grandTotal;
}

// Compute grand total for completed orders
$completedOrdersGrandTotal = computeGrandTotal($completed_orders);

// Compute grand total for pending orders
$pendingOrdersGrandTotal = computeGrandTotal($pending_orders);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Report</title>
    
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="../css/admin_style.css">
    
    <style>
        /* Additional CSS styles */
        .container {
            margin: 20px;
        }
        
        .box {
            padding: 20px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        
        h2 {
            margin-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        .print-btn {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #008CBA;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        
        .print-btn:hover {
            background-color: #005F6B;
        }
        
        /* Hide admin header during printing */
        @media print {
            .admin-header {
                display: none;
            }
        }
    </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<button class="print-btn" onclick="printReport()">Print Report</button>
<div class="print-content">
    <div class="container">
        <div class="box">
            <h2>Completed Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Placed On</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($completed_orders as $order) : ?>
                        <tr>
                            <td><?= $order['id']; ?></td>
                            <td><?= $order['placed_on']; ?></td>
                            <td><?= $order['name']; ?></td>
                            <td><?= $order['email']; ?></td>
                            <td>ksh/-<?= $order['total_price']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Grand Total</th>
                        <th>ksh/-<?= $completedOrdersGrandTotal; ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="box">
            <h2>Pending Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Placed On</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_orders as $order) : ?>
                        <tr>
                            <td><?= $order['id']; ?></td>
                            <td><?= $order['placed_on']; ?></td>
                            <td><?= $order['name']; ?></td>
                            <td><?= $order['email']; ?></td>
                            <td>ksh/-<?= $order['total_price']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Grand Total</th>
                        <th>ksh/-<?= $pendingOrdersGrandTotal; ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
    function printReport() {
        window.print();
    }
</script>



</body>
</html>
