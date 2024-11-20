<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE id = ?");
   $delete_admin->execute([$delete_id]);
   header('location:admin_accounts.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admins Accounts</title>

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
         border: 2px solid #ddd; /* Add border around the table */
      }

      th, td {
         padding: 12px;
         text-align: left;
         font-size: 18px;
         border: 1px solid #ddd; /* Add border to table cells */
      }

      th {
         background-color: #008CBA;
         color: white;
      }

      td {
         background-color: #f2f2f2;
      }

      .delete-btn {
         color: white ;
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

      .flex-btn {
         display: flex;
         justify-content: space-between;
         align-items: center;
      }

      .option-btn {
         padding: 8px 16px;
         font-size: 16px;
         text-decoration: none;
         color: white;
         background-color: #008CBA;
         border: none;
         border-radius: 5px;
      }

      .option-btn:hover {
         background-color: #005F6B;
      }

      .heading {
         text-decoration: underline;
      }

      .box {
   margin-bottom: 10px;
   padding: 10px;
   border: 1px solid #ccc;
   border-radius: 5px;
   background-color: #f9f9f9;
   text-align: center;
}

.box p {
   font-size: 18px;
   font-weight: bold;
   margin-bottom: 10px;
}

.option-btn1 {
   display: inline-block;
   padding: 10px 50px;
   font-size: 16px;
   text-decoration: none;
   background-color: #007bff;
   color: #fff;
   border-radius: 5px;
   background-color: green;
   
}

.option-btn:hover {
   background-color: #0056b3;
}

   </style>

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admins accounts section starts  -->
<section class="accounts">
   <h1 class="heading">Admins Accounts</h1>

   <div class="box">
   <p>Register New Admin</p>
   <a href="register_admin.php" class="option-btn1">Register</a>
</div>

   <div class="table-container">
      <table>
         <thead>
            <tr>
               <th>Admin ID</th>
               <th>Username</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $select_account = $conn->prepare("SELECT * FROM `admin`");
            $select_account->execute();
            if ($select_account->rowCount() > 0) {
               while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
            ?>
                  <tr>
                     <td><?= $fetch_accounts['id']; ?></td>
                     <td><?= $fetch_accounts['name']; ?></td>
                     <td>
                        <div class="flex-btn">
                           <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Delete this account?');">Delete</a>
                           <?php
                           if ($fetch_accounts['id'] == $admin_id) {
                              echo '<a href="update_profile.php" class="option-btn">Update</a>';
                           }
                           ?>
                        </div>
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
<!-- admins accounts section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>
