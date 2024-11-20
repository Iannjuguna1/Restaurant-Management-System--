<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home1.php');
};

if(isset($_POST['submit'])){
   $flat = $_POST['flat'];
   $building = $_POST['building'];
   $area = $_POST['area'];
   $town = $_POST['town'];
   $city = $_POST['city'];
   $state = $_POST['state'];

   // Validate and sanitize the inputs
   $flat = filter_var($flat, FILTER_SANITIZE_STRING);
   $building = filter_var($building, FILTER_SANITIZE_STRING);
   $area = filter_var($area, FILTER_SANITIZE_STRING);
   $town = filter_var($town, FILTER_SANITIZE_STRING);
   $city = filter_var($city, FILTER_SANITIZE_STRING);
   $state = filter_var($state, FILTER_SANITIZE_NUMBER_INT); // Sanitize as a number

   // Validate input using regular expressions
   $regex_alphabets = "/^[a-zA-Z\s]+$/"; // Allows only alphabets and spaces
   $regex_numbers = "/^\d+$/"; // Allows only numbers

   if (!preg_match($regex_alphabets, $flat) || !preg_match($regex_alphabets, $area) || !preg_match($regex_alphabets, $town) || !preg_match($regex_alphabets, $city)) {
      $message[] = 'Invalid input for name of building, road, area, or town. Please use alphabets only.';
   } elseif (!preg_match($regex_numbers, $building) || !preg_match($regex_numbers, $state)) {
      $message[] = 'Invalid input for floor or postal code. Please use numbers only.';
   } else {
      $address = $flat .', '.$building.', '.$area.', '.$town .', '. $city .', '. $state;

      $update_address = $conn->prepare("UPDATE `users` set address = ? WHERE id = ?");
      $update_address->execute([$address, $user_id]);

      $message[] = 'Address saved!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update address</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <script>
      // Function to allow only alphabets in input fields
      function allowAlphabets(event) {
         var regex = /^[a-zA-Z\s]*$/;
         var key = event.key;
         if (!regex.test(key)) {
            event.preventDefault();
         }
      }

      // Function to allow only numbers in input fields
      function allowNumbers(event) {
         var regex = /^[0-9]*$/;
         var key = event.key;
         if (!regex.test(key)) {
            event.preventDefault();
         }
      }
   </script>

</head>
<body>
   
<?php include 'components/user_header1.php' ?>

<div class="heading">
        <h3>Update Address</h3>
        <p><a href="profile.php">Profile</a> <span> / Update Address</span></p>
    </div>


<section class="form-container">

   <form action="" method="post">
      <h3>My address</h3>
      <input type="text" class="box" placeholder="Name of Building" required maxlength="50" name="flat" onkeypress="allowAlphabets(event)">
      <input type="number" class="box" placeholder="Floor" required name="building" min="0" max="9999" maxlength="4" onkeypress="allowNumbers(event)">
      <input type="text" class="box" placeholder="Road" required maxlength="50" name="area" onkeypress="allowAlphabets(event)">
      <input type="text" class="box" placeholder="Area" required maxlength="50" name="town" onkeypress="allowAlphabets(event)">
      <input type="text" class="box" placeholder="Town" required maxlength="50" name="city" onkeypress="allowAlphabets(event)">
      <input type="number" class="box" placeholder="Postal code" required maxlength="5" name="state" onkeypress="allowNumbers(event)">
      <input type="submit" value="save address" name="submit" class="btn">
   </form>

</section>










<?php include 'components/footer1.php' ?>







<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>

