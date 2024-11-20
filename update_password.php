<?php
include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
   header('location:home1.php');
   exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['submit'])){

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
   $select_prev_pass->execute([$user_id]);
   $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'Old password not matched!';
      } elseif($new_pass != $confirm_pass){
         $message[] = 'Confirm password not matched!';
      } else {
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $user_id]);
            $message[] = 'Password updated successfully!';
         } else {
            $message[] = 'Please enter a new password!';
         }
      }
   }  

}

// Fetch user's profile information
$fetch_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$fetch_profile->execute([$user_id]);
$fetch_profile = $fetch_profile->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Password</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header1.php'; ?>
<!-- header section ends -->

<div class="heading">
    <h3>Update Password</h3>
    <p><a href="profile.php">Profile</a> <span> / Update Password</span></p>
</div>

<section class="form-container update-form">
   <form action="" method="post">
      <h3>Update Password</h3>
      <input type="password" name="old_pass" required placeholder="Enter your old password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" required placeholder="Enter your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" required placeholder="Confirm your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Update Password" name="submit" class="btn">
   </form>

   <?php
      // Display validation errors if any
      if(isset($message)) {
         foreach($message as $error) {
            echo "<p style='color: red;'>$error</p>";
         }
      }
   ?>

</section>

<?php include 'components/footer1.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>
   // JavaScript function to allow only alphabets in the name field
   function allowAlphabets(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if ((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && (charCode != 32))
         return false;
      return true;
   }
</script>

</body>
</html>
