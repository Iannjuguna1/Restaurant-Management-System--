<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>M-Pesa Payment</title>
   <style>
      body {
         font-family: Arial, sans-serif;
         background-color: #f5f5f5;
         color: #333;
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100vh;
         margin: 0;
      }
      .container {
         width: 400px;
         padding: 20px;
         background-color: #fff;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
         border-radius: 8px;
      }
      h1 {
         text-align: center;
         margin-bottom: 20px;
      }
      form {
         display: flex;
         flex-direction: column;
      }
      label {
         font-weight: bold;
         margin-bottom: 8px;
      }
      input {
         padding: 10px;
         margin-bottom: 15px;
         border: 1px solid #ccc;
         border-radius: 5px;
         font-size: 16px;
         transition: border-color 0.3s;
      }
      input:focus {
         outline: none;
         border-color: #008CBA;
      }
      input[type="submit"] {
         background-color: #008CBA;
         color: #fff;
         border: none;
         border-radius: 5px;
         padding: 12px 20px;
         cursor: pointer;
         font-size: 16px;
         transition: background-color 0.3s;
      }
      input[type="submit"]:hover {
         background-color: #005F6B;
      }
   </style>
</head>
<body>

<div class="container">
   <h1>M-Pesa Payment</h1>

   <form action="process_payment.php" method="post">
      <label for="user_phone">Your Phone Number:</label>
      <input type="tel" id="user_phone" name="user_phone" required>

      <label for="amount">Amount to Pay (KES):</label>
      <input type="number" id="amount" name="amount" min="1" required>

      <label for="recipient_phone">Till Number:</label>
      <input type="tel" id="recipient_phone" name="recipient_phone" required>

      <input type="submit" value="Submit Payment">
   </form>
</div>

</body>
</html>

<script>
   // Optional JavaScript (for validation, etc.)
   // You can add JavaScript code here as needed
</script>
