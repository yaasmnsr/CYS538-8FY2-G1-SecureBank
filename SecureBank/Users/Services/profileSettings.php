<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Profile Settings</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">

  <style>

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
    <p id="success-popup-message">Profile updated successfully!</p>
  </div>

  <main class="profile-settings">
    <h1>Profile Settings</h1>
    <form method="POST" action="../../Configurations/updateProfile.php">
      <div>
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" value="<?php echo $userFirstName; ?>" required>
      </div>

      <div>
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" value="<?php echo $userLastName; ?>" required>
      </div>

      <div>
        <label for="email">Email:</label>
        <input type="email" name="email"value="<?php echo $email; ?>" required>
      </div>

      <div>
        <label for="phone">Phone:</label>
        <input type="text" name="phone" value="<?php echo $phone; ?>" required>
      </div>

      <div>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
      </div>

      <p>Please enter your password to update your account.</p>
      
      <div class="divider"></div>

      <div>
        <label for="city">City:</label>
        <input type="text" name="city" value="<?php echo $city; ?>" required>
      </div>

      <div>
        <label for="district">District:</label>
        <input type="text" name="district" value="<?php echo $district; ?>" required>
      </div>

      <div>
        <label for="street">Street:</label>
        <input type="text" name="street" value="<?php echo $street; ?>" required>
      </div>

      <div class="divider"></div>

      <div class="button-container">
        <button type="submit">Save Changes</button>
        <button type="button" onclick="window.location.href = 'changePassword.php';">Change Password</button>
      </div>
    </form>

    <?php
        // Check if there are any transfer errors in the session
        if (isset($_SESSION['update_errors'])) {
            $updateErrors = $_SESSION['update_errors'];
            unset($_SESSION['update_errors']);
            echo '<div style="color: red;">';
            foreach ($updateErrors as $error) {
                echo "<p>Error: $error</p>";
            }
            echo '</div>';
        }
        ?>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php'); ?>

</body>
</html>