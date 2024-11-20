<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit;
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_users->execute([$delete_id]);
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_order->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   header('location:users_accounts.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Accounts</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      /* Additional CSS styles for the table */
      body {
         font-family: Arial, sans-serif;
         margin: 0;
         padding: 0;
         background-color: #f2f2f2;
      }

      .table-container {
         width: 100%;
         overflow-x: auto;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 20px;
      }

      th, td {
         padding: 12px;
         text-align: left;
         font-size: 18px;
         border: 1px solid #ddd;
      }

      th {
         background-color: #008CBA;
         color: white;
      }

      td {
         background-color: #f2f2f2;
      }

      .delete-btn {
         color: white;
         text-decoration: none;
         
      }

      .delete-btn:hover {
         text-decoration: underline;
      }

      .empty {
         text-align: center;
         font-size: 18px;
         font-style: italic;
         color: red;
      }

      .heading{
         text-decoration: underline;
      }
   </style>

</head>

<body>

   <?php include '../components/admin_header.php' ?>

   <!-- user accounts section starts  -->

   <section class="accounts">

      <h1 class="heading">User Accounts</h1>

      <div class="table-container">

         <table>
            <thead>
               <tr>
                  <th>User ID</th>
                  <th>Username</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $select_account = $conn->prepare("SELECT * FROM `users`");
               $select_account->execute();
               if ($select_account->rowCount() > 0) {
                  while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
               ?>
                     <tr>
                        <td><?= $fetch_accounts['id']; ?></td>
                        <td><?= $fetch_accounts['name']; ?></td>
                        <td>
                           <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Delete this account?');">Delete</a>
                        </td>
                     </tr>
               <?php
                  }
               } else {
                  echo '<tr><td colspan="3" class="empty">No accounts available</td></tr>';
               }
               ?>
            </tbody>
         </table>

      </div>

   </section>

   <!-- user accounts section ends -->

   <!-- custom js file link  -->
   <script src="../js/admin_script.js"></script>

</body>

</html>
