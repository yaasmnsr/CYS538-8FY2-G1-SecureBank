<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Create Account</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">
</head>

<body>
  <?php
    include('../Include/header.php');

    // Function to generate a unique account number
    function generateUniqueAccountNumber($length) {
      $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $accountNumber = '';

      // Check if the generated account number already exists in the database
      // and generate a new one if it does
      do {
        $accountNumber = '';
        for ($i = 0; $i < $length; $i++) {
          $accountNumber .= $characters[rand(0, strlen($characters) - 1)];
        }
      } while (accountNumberExistsInDatabase($accountNumber));

      return $accountNumber;
    }

    // Function to check if an account number exists in the database
    function accountNumberExistsInDatabase($accountNumber) {
      require($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Configurations/databaseConfig.php');
      
      $query = "SELECT COUNT(*) FROM accounts WHERE account_number = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("s", $accountNumber);
      
      $stmt->execute();
      
      $stmt->bind_result($count);
      
      $stmt->fetch();
      
      $stmt->close();
      
      // Return true if the account number exists, or false otherwise
      return $count > 0;
    }

    // Generate a unique account number
    $uniqueAccountNumber = generateUniqueAccountNumber(15);
  ?>


  <main class="small-main">
    <h1>Create an Account</h1>
    <form action="/SecureBank/Configurations/createAccount.php" method="post">
    <div class="form-group">
        <label for="UserId">User ID:</label>
        <input type="text" name="userId" value="<?php echo $userId;?>" readonly>
    </div>
    <div class="form-group">
        <label for="UserName">Full Name:</label>
        <input type="text" name="userName" value="<?php echo $userFirstName . " ". $userLastName;?>" readonly>
    </div>
    <div class="form-group">
        <label for="accountNumber">Account Number:</label>
        <input type="text" name="accountNumber" value="<?php echo $uniqueAccountNumber;?>" readonly>
    </div>
    <div class="form-group">
        <label for="accountType">Account Type:</label>
        <select name="accountType" required>
            <option value="Savings">Savings</option>
            <option value="Checking">Checking</option>
            <option value="Business">Business</option>
            <option value="Joint">Joint</option>
            <option value="Individual Retirement">Invididual Retirement</option>
        </select>
    </div>
    <div class="form-group">
        <label for="dateOpened">Date Opened:</label>
        <input type="date" name="dateOpened" value="<?php echo date('Y-m-d'); ?>" readonly>
    </div>
    <div class="form-group">
        <input type="submit" value="Create Account">
    </div>
    </form>
    <?php
      // Check if there are any transfer errors in the session
      if (isset($_SESSION['accountError'])) {
        $accountError = $_SESSION['accountError'];
        unset($_SESSION['accountError']);
        echo '<div style="color: red;">';
        foreach ($accountError as $error) {
          echo "<p>Error: $error</p>";
        }
        echo '</div>';
      }
    ?>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php'); ?>
</body>

</html>