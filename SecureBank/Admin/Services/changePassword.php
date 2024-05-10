<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Change Password</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">

  <style>
  main {
    max-width: 400px;
  }

  h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
  }

  ul.password-requirements {
    list-style-type: disc;
    margin-left: 20px;
    color: #555;
  }
  </style>
  <script>
    // Function to hide the success message after a delay
    function hideSuccessMessage() {
      var successMessage = document.getElementById('success-popup');
      successMessage.style.display = 'none';

      // Remove the success parameter from the URL
      var url = window.location.href;
      var baseUrl = url.split('?')[0];
      var newUrl = baseUrl;
      window.history.replaceState({}, '', newUrl);
    }

    window.onload = function() {
      // Check if the success message is present in the URL
      var urlParams = new URLSearchParams(window.location.search);
      var successParam = urlParams.get('success');
      
      if (successParam === '1') {
        // Display the success message
        var successMessage = document.getElementById('success-popup');
        successMessage.style.display = 'block';

        // Hide the success message after 5 seconds
        setTimeout(hideSuccessMessage, 5000);
      }
    };
  </script>
</head>

<body>
  <?php include('../Include/header.php');?>

  <div id="success-popup" class="success-popup" <?php if (isset($_GET['success']) && $_GET['success'] == 1) echo 'style="display: block;"'; ?>>
    <p id="success-popup-message">Password updated successfully!</p>
  </div>

  <main>
    <h1>Profile Settings</h1>
    <form method="POST" action="../../Configurations/updatePassword.php">
      <div>
        <label for="previous_password">Previous Password:</label>
        <input type="password" name="previous_password" required>
      </div>

      <div class="divider"></div>

      <div>
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required>
      </div>

      <div>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required>
      </div>
      
      <div class="divider"></div>

      <p style="font-weight: bold;">Note that the new password should comply with the following rules:</p>

      <ul class="password-requirements">
        <li>Must be at least 8 characters long.</li>
        <li>Must contain at least one lowercase letter.</li>
        <li>Must contain at least one uppercase letter.</li>
        <li>Must contain at least one number.</li>
        <li>Must contain at least one symbol.</li>
      </ul>

      <div class="divider"></div>

      <button type="submit" class="button">Save Changes</button>
    </form>

    <?php
        // Check if there are any transfer errors in the session
        if (isset($_SESSION['updatePass_errors'])) {
          $updatePassErrors = $_SESSION['updatePass_errors'];
          unset($_SESSION['updatePass_errors']);
          echo '<div style="color: red;">';
          foreach ($updatePassErrors as $error) {
            echo "<p>Error: $error</p>";
          }
          echo '</div>';
        }
      ?>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php'); ?>
</body>