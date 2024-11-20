<?php
include 'components/connect.php';

session_start();

// Redirect to home1.php if user is not logged in
if (!isset($_SESSION['user_id'])) {
   header('location:home1.php');
   exit(); // Add exit after header redirection
}

$user_id = $_SESSION['user_id'];

// Fetch user's profile information
$fetch_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$fetch_profile->execute([$user_id]);
$fetch_profile = $fetch_profile->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
   // Update the fields if they are not empty in the POST data
   $name = !empty($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_STRING) : $fetch_profile['name'];
   $email = !empty($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_STRING) : $fetch_profile['email'];
   $number = !empty($_POST['number']) ? filter_var($_POST['number'], FILTER_SANITIZE_STRING) : $fetch_profile['number'];

   // Update user data in the database
   $update_user = $conn->prepare("UPDATE `users` SET name = ?, email = ?, number = ? WHERE id = ?");
   $update_user->execute([$name, $email, $number, $user_id]);
   $message[] = 'Profile Updated!';

   // Redirect to profile.php after updating profile
   // header('location: profile.php');
   // exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>

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
    <h3>Update Profile</h3>
    <p><a href="profile.php">Profile</a> <span> / Update Profile</span></p>
</div>

<section class="form-container update-form">
   <form action="" method="post">
      <h3>Update Profile</h3>
      <input type="text" name="name" required placeholder="Enter your name" class="box" maxlength="50" value="<?= $fetch_profile['name']; ?>" onkeypress="return allowAlphabets(event)">
      <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" value="<?= $fetch_profile['email']; ?>" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="Enter your number" class="box" min="0" max="9999999999" maxlength="10" value="<?= $fetch_profile['number']; ?>">
      <input type="submit" value="Update Now" name="submit" class="btn">
   </form>
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
