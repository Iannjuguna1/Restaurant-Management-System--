<?php
// Check if session is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // Check if last activity timestamp is set
    if (isset($_SESSION['last_activity'])) {
        // Get current timestamp
        $current_time = time();
        
        // Get last activity timestamp
        $last_activity = $_SESSION['last_activity'];
        
        // Calculate time difference in seconds
        $time_difference = $current_time - $last_activity;
        
        // Define session timeout duration (in seconds)
        $timeout_duration = 30 * 60; // 30 minutes
        
        // Check if session has expired
        if ($time_difference > $timeout_duration) {
            // Session expired, logout user
            session_unset(); // Unset all session variables
            session_destroy(); // Destroy the session
            // Redirect to login page
            header('Location: login.php');
            exit();
        }
    }
    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
}
?>





<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="home1.php" class="logo">Restaurant</a>

      <nav class="navbar">
         <a href="home1.php">Home</a>
         <a href="menu.php">Menu</a>
         <a href="orders1.php">Orders</a>
         <a href="reservation.php">Reservations</a>
         <a href="Mpesa_transaction.php">Pay</a>
         <a href="contact.php">Contact</a>
         <a href="about.php">About</a>
      </nav>

      <div class="icons">
         <?php
         $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $count_cart_items->execute([$user_id]);
         $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
         if ($user_id) {
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if ($select_profile->rowCount() > 0) {
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
               <p class="name"><?= $fetch_profile['name']; ?></p>
               <div class="flex">
                  <a href="profile.php" class="btn">profile</a>
                  <a href="components/user_logout.php" onclick="return confirm('Logout from this website?');" class="delete-btn">logout</a>
               </div>
         <?php
            }
         } else {
         ?>
            <p class="name">Please login first!</p>
            <a href="login.php" class="btn">login</a>
         <?php
         }
         ?>
         <p class="account">
            <?php
            if (!$user_id) {
               echo '<a href="login.php">Login</a> or <a href="register.php">Register</a>';
            }
            ?>
         </p>
      </div>

   </section>

</header>
