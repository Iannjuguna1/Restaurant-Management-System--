<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit; // Add exit after header redirection
}

// Fetch current admin's name from database
$select_name = $conn->prepare("SELECT name FROM `admin` WHERE id = ?");
$select_name->execute([$admin_id]);
$fetch_profile = $select_name->fetch(PDO::FETCH_ASSOC);

if (!$fetch_profile) {
   // Handle error if admin not found
   header('location:admin_login.php');
   exit; // Add exit after header redirection
}

if (isset($_POST['submit'])) {
   // Sanitize and validate input data
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $old_pass = sha1(filter_var($_POST['old_pass'], FILTER_SANITIZE_STRING));
   $new_pass = sha1(filter_var($_POST['new_pass'], FILTER_SANITIZE_STRING));
   $confirm_pass = sha1(filter_var($_POST['confirm_pass'], FILTER_SANITIZE_STRING));

   // Check if old password matches the stored password
   if ($old_pass != $fetch_profile['password']) {
      $message[] = 'Old password does not match!';
   } elseif ($new_pass != $confirm_pass) {
      $message[] = 'New password and confirm password do not match!';
   } else {
      // Update admin's name and password in the database
      $update_profile = $conn->prepare("UPDATE `admin` SET name = ?, password = ? WHERE id = ?");
      $update_profile->execute([$name, $confirm_pass, $admin_id]);
      $message[] = 'Profile updated successfully!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile Update</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admin profile update section starts  -->
<section class="form-container">
   <form action="" method="POST">
      <h3>Update Profile</h3>
      <input type="text" name="name" maxlength="20" required class="box" value="<?= $fetch_profile['name']; ?>">
      <input type="password" name="old_pass" required maxlength="20" placeholder="Enter your old password" class="box">
      <input type="password" name="new_pass" required maxlength="20" placeholder="Enter your new password" class="box">
      <input type="password" name="confirm_pass" required maxlength="20" placeholder="Confirm your new password" class="box">
      <input type="submit" value="Update Now" name="submit" class="btn">
   </form>
</section>
<!-- admin profile update section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>
