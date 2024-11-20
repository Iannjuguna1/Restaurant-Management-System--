<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    // Redirect the user to the login page if not authenticated
    header('Location: login.php');
    exit(); // Stop further execution
}

if(isset($_POST['submit'])){

    $date = $_POST['date'];
    $time = $_POST['time'];
    $partySize = $_POST['number'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $occasion = $_POST['occasion'];

    // Insert data into the database with the user's ID
    $stmt = $conn->prepare("INSERT INTO `reservation` (user_id, date, time, party_size, name, email, phone, occasion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $date, $time, $partySize, $name, $email, $phone, $occasion]);

    // Check if insertion was successful
    if($stmt->rowCount() > 0){
        // Redirect to success page to prevent resubmission on page refresh
        header("Location: reservation_summary.php");
        exit(); // Stop further execution
    }else{
        $error = 'Error submitting reservation. Please try again.';
    }
}

?>

<!-- Rest of your HTML code -->



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reservation</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
    <!-- flatpicker -->
     
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


   <style >
/* styles.css */
.container {
  max-width: 600px;
  margin: 50px auto;
  padding: 20px;
  background-color: #333; /* Deep grey background */
  border-radius: 10px;
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 5px;
  color: white;
}

input[type="date"],
input[type="time"],
input[type="number"],
input[type="text"],
input[type="email"],
input[type="tel"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
    background-color: #fff; /* White input background */
}

button {
  display: block;
  width: 100%;
  padding: 10px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

/* Center the search form */
.search-container {
            text-align: center;
            margin: 20px;
        }
 #date-search {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 200px; /* Adjust the width */
            margin-right: 10px;
        }
 #search-btn,
        #print-btn {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #search-btn {
            background-color: #4CAF50;
            color: #fff;
            margin-right: 10px;
        }

        #search-btn:hover {
            background-color: #45a049;
        }
        .container h2{
  color: white;
  text-align: center;
}
.search-container label{
  color: black;
  font-size: 13px;
  font-style: italic;
}
.button {
    display: inline-block;
    padding: 15px 40px;
    background-color: #4CAF50;
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 14px;
}

.button:hover {
    background-color: #45a049;
}





   </style>
</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header1.php'; ?>
<!-- header section ends -->

<div class="heading">
        <h3>Reservation</h3>
        <p><a href="checkout.php">Checkout</a> <span> / Reservation</span></p>
    </div>

     <!-- Add search form at the center middle part -->
     <div class="search-container">
        <form action="" method="POST">
            <!-- <label for="date-search">Search reservation status by phone or reservation code:</label>
            <input type="text" name="date_search" id="date-search" placeholder="Enter code or number">
            <input type="submit" value="Search" class="button" id="search-btn" > -->
            <a href="reservation_summary.php" class="button">My Reservations</a>
            <!-- <input type="submit" value="Status" id="search-btn"> -->
        </form>
    </div>

<div class="container">
  <h2>Make a Reservation</h2>
  
  <form id="reservationForm" action="" method="post">
     <div class="form-group">
      <label for="date">Date:</label>
      <input type="date" id="date"   name="date" min="<?php echo date('Y-m-d'); ?>" required>
    </div>
    <div class="form-group">
      <label for="time">Time:</label>
      <input type="time" id="time" name="time" required>
    </div>
        <div class="form-group">
    <label for="partySize">Party Size:</label>
    <input type="number" name="number" required placeholder="Enter number of people" class="box" min="1" max="9999999999" maxlength="4" pattern="[0-9]+" title="Please enter a valid numeric value" required>
    </div>


    <div class="form-group">
      <label for="name">Name:</label>
      <input type="text" id="name" required placeholder="Enter your name" name="name" required onkeypress="return allowAlphabets(event)">
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required placeholder="Enter your Email">
    </div>
    <div class="form-group">
      <label for="phone">Phone:</label>
      <input type="tel" id="phone" name="phone" required placeholder="Enter your Phone no" maxlength="10">
    </div>
    <div class="form-group">
      <label for="occasion">Occasion:</label>
      <input type="text" id="occasion" name="occasion" required placeholder="Enter the occasion">
    </div>
    <button type="submit" name="submit">Submit

      </form>
   


  
</div>












<?php include 'components/footer1.php'; ?>






<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  var currentTime = new Date();
  var currentHour = currentTime.getHours();
  var currentMinutes = currentTime.getMinutes();
  var timeInput = document.getElementById("time");
  
  // Adjust the current time if minutes have passed
  if (currentMinutes > 0) {
    currentHour += 1; // Increment the hour
    currentMinutes = 0; // Reset minutes to 0a
  }
  
  // Ensure the hour is formatted as two digits
  var formattedHour = ('0' + currentHour).slice(-2);
  
  // Set the minimum time for the time input
  var minTime = formattedHour + ':' + ('0' + currentMinutes).slice(-2);
  timeInput.min = minTime;
});
</script>
<script>
      // JavaScript function to allow only alphabets in the name field
      function allowAlphabets(evt) {
         var charCode = (evt.which) ? evt.which : event.keyCode;
         if ((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && (charCode != 32))
            return false;
         return true;
      }
</script>

<script>
    document.getElementById('status-btn').addEventListener('click', function() {
        window.location.href = 'reservation1.php';
    });
</script>


</body>
</html>