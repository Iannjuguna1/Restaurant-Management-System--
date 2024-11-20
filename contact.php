<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['send'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $msg = $_POST['msg'];
    $msg = filter_var($msg, FILTER_SANITIZE_STRING);

    // Rating
    $rating = isset($_POST['rating']) ? $_POST['rating'] : 0;

    $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
    $select_message->execute([$name, $email, $number, $msg]);

    if ($select_message->rowCount() > 0) {
        $message[] = 'Already sent message!';
    } else {
        $insert_message = $conn->prepare("INSERT INTO `messages` (user_id, name, email, number, message, rating) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_message->execute([$user_id, $name, $email, $number, $msg, $rating]);

        $message[] = 'Message sent successfully!';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>

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
   <h3>Contact us</h3>
   <p><a href="home1.php">Home</a> <span> / contact</span></p>
</div>

<!-- contact section starts  -->

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="images/contact-img.svg" alt="">
      </div>

      <form action="" method="post">
         <h3>Tell us something!</h3>
         <input type="text" name="name" maxlength="50" class="box" placeholder="enter your name" required onkeypress="return allowAlphabets(event)">
         <input type="number" name="number" min="0" max="9999999999" class="box" placeholder="enter your number" required maxlength="10">
         <input type="email" name="email" maxlength="50" class="box" placeholder="enter your email" required>
         <textarea name="msg" class="box" required placeholder="enter your message" maxlength="500" cols="30" rows="10"></textarea>
         <p>Rate us</p>
         <!-- Star rating -->
            <div class="star-rating">
               <input type="radio" id="star5" name="rating" value="5" />
               <label for="star5" title="5 stars">&#9733; 5</label>
               <input type="radio" id="star4" name="rating" value="4" />
               <label for="star4" title="4 stars">&#9733; 4</label>
               <input type="radio" id="star3" name="rating" value="3" />
               <label for="star3" title="3 stars">&#9733; 3</label>
               <input type="radio" id="star2" name="rating" value="2" />
               <label for="star2" title="2 stars">&#9733; 2</label>
               <input type="radio" id="star1" name="rating" value="1" />
               <label for="star1" title="1 star">&#9733; 1</label>
            </div>
            <!-- End star rating -->

         <input type="submit" value="send message" name="send" class="btn">
      </form>

   </div>

</section>

<!-- contact section ends -->

<!-- footer section starts  -->
<?php include 'components/footer1.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>
    function allowAlphabets(event) {
        // Get the ASCII value of the pressed key
        var keyCode = event.keyCode;

        // Allow only alphabetic characters (A-Z, a-z), backspace, and delete keys
        if ((keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || keyCode == 8 || keyCode == 46) {
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    }
</script>

</body>
</html>
