<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Profile Settings</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">

  <style>
    main {
      width: 700px;
    }
    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    form {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
    }

    form > div {
      flex-basis: calc(50% - 7.5px);
    }

    @media (max-width: 600px) {
      form > div {
        flex-basis: 100%;
      }
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
    <p id="success-popup-message">Profile updated successfully!</p>
  </div>

  <main>
    <h1>Profile Settings</h1>
    <form method="POST" action="../../Configurations/updateProfile.php">
      <div>
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" value="<?php echo $adminFirstName; ?>" required>
      </div>

      <div>
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" value="<?php echo $adminLastName; ?>" required>
      </div>

      <div>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
      </div>

      <p>Please enter your password to update your account.</p>
      
      <div class="divider"></div>

      <div class="button-container">
        <button type="submit" class='button'>Save Changes</button>
        <button type="button" class='button' onclick="window.location.href = 'changePassword.php';">Change Password</button>
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
            echo '</div></br>';
        }
        ?>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php'); ?>

</body>

</html>