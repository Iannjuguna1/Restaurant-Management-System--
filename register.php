<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $pass = $_POST['pass'];
   $cpass = $_POST['cpass'];

   // Validate name: Only alphabets allowed
   if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
      $message[] = 'Name can only contain alphabets and spaces!';
   }

   // Validate password: At least one number, one uppercase and lowercase letter, one special character, and length > 5
   if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_+={}[]|:;\"'<>,.?\/])(?!.*\s).{6,}$/", $pass)) {
      $message[] = 'Password must contain at least one number, one uppercase and lowercase letter, one special character, and be at least 6 characters long!';
   }

   if($pass != $cpass){
      $message[] = 'Confirm password not matched!';
   }

   // If no validation errors, proceed with registration
   if (!isset($message)) {
      $pass = sha1($pass); // Encrypt password before saving
      $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
      $insert_user->execute([$name, $email, $number, $pass]);
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
      $select_user->execute([$email, $pass]);
      $row = $select_user->fetch(PDO::FETCH_ASSOC);
      if($select_user->rowCount() > 0){
         $_SESSION['user_id'] = $row['id'];
         header('location:home1.php');
         exit(); // Add exit after header redirection
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<style>



</style>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header1.php'; ?>
<!-- header section ends -->

<section class="form-container">

   <form action="" method="post">
      <h3>register now</h3>
      <input type="text" name="name" required placeholder="enter your name" class="box" maxlength="50" onkeypress="return allowAlphabets(event)">
      <input type="email" name="email" required placeholder="enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="enter your number" class="box" min="0" max="9999999999" maxlength="10">
      <input type="password" name="pass" required placeholder="enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="confirm your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" name="submit" class="btn">
      <p>already have an account? <a href="login.php">login now</a></p>
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
