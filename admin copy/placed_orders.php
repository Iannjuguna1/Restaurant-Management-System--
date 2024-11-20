<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['update_payment'])) {
    $order_id = $_POST['order_id'];
    $payment_status = $_POST['payment_status'];
    $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
    $update_status->execute([$payment_status, $order_id]);
    $message[] = 'Payment status updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$delete_id]);
    header('location:placed_orders.php');
}

// Handle search query
if (isset($_POST['search'])) {
    $search_term = $_POST['search'];
    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE email LIKE ? OR number LIKE ?");
    $select_orders->execute(["%$search_term%", "%$search_term%"]);
} else {
    // If no search query, fetch all orders
    $select_orders = $conn->prepare("SELECT * FROM `orders`");
    $select_orders->execute();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placed Orders</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="../css/admin_style.css">

    <style>
        /* Center the search form */
        form {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Style for the search input and button */
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            font-style: italic;
        }

        #search {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
            width: 300px; /* Adjust the width as needed */
        }

        #search-btn {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #search-btn:hover {
            background-color: #45a049;
        }

        /* Styling for the order boxes */
        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .box {
            width: 300px;
            margin: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .box:hover {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        /* Styling for the delete button */
        .delete-btn {
            color: white;
            cursor: pointer;
        }

        .heading{
         text-decoration: underline;
        }

    </style>
</head>
<body>

<?php include '../components/admin_header1.php' ?>

<!-- Add search form -->
<form action="" method="POST">
    <label for="search">Search by Email or Phone Number:</label>
    <input type="text" name="search" id="search" placeholder="Enter Email or Phone number">
    <input type="submit" value="Search" id="search-btn">
</form>

<!-- placed orders section starts  -->
<section class="placed-orders">

    <h1 class="heading">Placed Orders</h1>

    <div class="box-container">
        <?php
        if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <div class="box">
                    <p> user id : <span><?= $fetch_orders['user_id']; ?></span> </p>
                    <p> placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
                    <p> name : <span><?= $fetch_orders['name']; ?></span> </p>
                    <p> email : <span><?= $fetch_orders['email']; ?></span> </p>
                    <p> number : <span><?= $fetch_orders['number']; ?></span> </p>
                    <p> address : <span><?= $fetch_orders['address']; ?></span> </p>
                    <p> total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
                    <p> total price : <span>$<?= $fetch_orders['total_price']; ?>/-</span> </p>
                    <p> payment method : <span><?= $fetch_orders['method']; ?></span> </p>
                    <form action="" method="POST">
                        <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                        <select name="payment_status" class="drop-down">
                            <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
                            <option value="pending">pending</option>
                            <option value="completed">completed</option>
                        </select>
                        <div class="flex-btn">
                            <input type="submit" value="update" class="btn" name="update_payment">
                            <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Delete this order?');">delete</a>
                        </div>
                    </form>
                </div>
        <?php
            }
        } else {
            echo '<p class="empty">No orders found!</p>';
        }
        ?>
    </div>

</section>
<!-- placed orders section ends -->

<!-- ... (same as before) ... -->

</body>
</html>
