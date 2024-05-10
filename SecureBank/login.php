<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - User Page</title>
  <link rel="stylesheet" href="CSS/styles.css">
</head>

<body>
  <?php include('Include/header.php'); ?>

  <div class="login">
    <h2>Welcome to SecureBank</h2>
    <form action="Configurations/processLogin.php" method="post">
      <input type="text" name="id" placeholder="ID" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="submit" class="button" value="Sign In">
    </form>
    <?php
      // Check if there are any transfer errors in the session
      if (isset($_SESSION['errorMsg'])) {
        $errorMsg = $_SESSION['errorMsg'];
        unset($_SESSION['errorMsg']);
        echo '<div style="color: red;">';
        foreach ($errorMsg as $error) {
          echo "<p>Error: $error</p>";
        }
        echo '</div>';
      }
    ?>
    <div class="create-account-link">
      Don't have an account? <a href="sign-up.php">Create a new account</a>
    </div>
  </div>

  <?php include('Include/footer.php'); ?>

</body>
</html> 