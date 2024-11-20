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
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
   exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reviews</title>

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

      .heading {
         text-decoration: underline;
      }
   </style>

</head>
<body>

   <?php include '../components/admin_header.php' ?>

   <!-- messages section starts  -->

   <section class="messages">

      <h1 class="heading">Reviews</h1>

      <div class="table-container">

         <table>
            <thead>
               <tr>
                  <th>Name</th>
                  <th>Number</th>
                  <th>Email</th>
                  <th>Message</th>
                  <th>Rating</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $select_messages = $conn->prepare("SELECT * FROM `messages`");
               $select_messages->execute();
               if ($select_messages->rowCount() > 0) {
                  while ($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)) {
               ?>
                     <tr>
                        <td><?= $fetch_messages['name']; ?></td>
                        <td><?= $fetch_messages['number']; ?></td>
                        <td><?= $fetch_messages['email']; ?></td>
                        <td><?= $fetch_messages['message']; ?></td>
                        <td><?= $fetch_messages['rating']; ?></td>
                        <td>
                           <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('Delete this message?');">Delete</a>
                        </td>
                     </tr>
               <?php
                  }
               } else {
                  echo '<tr><td colspan="6" class="empty">You have no messages</td></tr>';
               }
               ?>
            </tbody>
         </table>

      </div>

   </section>

   <!-- messages section ends -->

   <!-- custom js file link  -->
   <script src="../js/admin_script.js"></script>

</body>
</html>
