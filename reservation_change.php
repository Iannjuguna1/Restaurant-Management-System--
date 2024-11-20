<?php
include 'components/connect.php';

session_start();

// Assuming user_id is set during login or session creation
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

// Check if reservation ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $reservation_id = $_GET['id'];

    // Fetch reservation details based on ID
    $select_reservation = $conn->prepare("SELECT * FROM `reservation` WHERE Id = ?");
    $select_reservation->execute([$reservation_id]);

    if ($select_reservation->rowCount() > 0) {
        // Reservation found, retrieve details
        $reservation = $select_reservation->fetch(PDO::FETCH_ASSOC);
    } else {
        // No reservation found with the provided ID
        header('location: reservation_update.php');
        exit;
    }
} else {
    // Reservation ID not provided or invalid
    // header('location: reservation_update.php');
    // exit;
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
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
        <h3>Reservation</h3>
        <p><a href="reservation_update.php">Reservation </a> <span> / Reservation change</span></p>
    </div>

     <!-- Add search form at the center middle part -->
     <div class="search-container">
        <form action="" method="POST">
            <!-- <label for="date-search">Search reservation status by phone or reservation code:</label>
            <input type="text" name="date_search" id="date-search" placeholder="Enter code or number">
            <input type="submit" value="Search" class="button" id="search-btn" > -->
            <!-- <a href="reservation_summary.php" class="button">My Reservations</a> -->
            <!-- <input type="submit" value="Status" id="search-btn"> -->
        </form>
    </div>

<div class="container">
<h2>Update Reservation</h2>
  
  <form action="process_update.php" method="POST">
     <div class="form-group">
     <input type="hidden" name="reservation_id" value="<?= $reservation_id ?>">
      <label for="date">Date:</label>
      <input type="date" id="date"   name="date" min="<?php echo date('Y-m-d'); ?>" required value="<?= $reservation['date']; ?>">
    </div>
    <div class="form-group">
      <label for="time">Time:</label>
      <input type="time" id="time" name="time" required value="<?= $reservation['time']; ?>">>
    </div>
      
    <div class="form-group">
      <label for="name">Name:</label>
      <input type="text" id="name" required placeholder="Enter your name" name="name" required onkeypress="return allowAlphabets(event)" value="<?= $reservation['Name']; ?>">>
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required placeholder="Enter your Email" value="<?= $reservation['email']; ?>">
    </div>
    <div class="form-group">
      <label for="phone">Phone:</label>
      <input type="tel" id="phone" name="phone" required placeholder="Enter your Phone no" maxlength="10" value="<?= $reservation['Phone']; ?>">
    </div>
    <div class="form-group">
    <label for="partySize">Party Size:</label>
    <input type="number" id="party_size" name="party_size" required placeholder="Enter number of people" class="box" min="1" max="9999999999" maxlength="4" pattern="[0-9]+" title="Please enter a valid numeric value" required value="<?= $reservation['Party_size']; ?>">
    
    </div>

    <div class="form-group">
      <label for="occasion">Occasion:</label>
      <input type="text" id="occasion" name="occasion" required placeholder="Enter the occasion" value="<?= $reservation['occasion']; ?>">
    </div>
    <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="pending" selected>Pending</option>
        </select>
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