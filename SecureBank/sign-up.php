<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Home Page</title>
  <link rel="stylesheet" href="CSS/styles.css">

  <style>
    main {
      max-width: 600px;
      margin: 50px auto;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
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

    .divider {
      flex-basis: 100%;
      border-bottom: 1px solid #ccc;
      margin: 15px 0;
    }

    ul.password-requirements {
      list-style-type: disc;
      margin-left: 20px;
      color: #555;
    }

    label {
      font-weight: bold;
      color: #555;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .button-container {
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    button[type="submit"],
    button[type="button"] {
      padding: 10px 20px;
      background-color: #1d3557;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      width: 150px;
    }

    button[type="submit"]:hover,
    button[type="button"]:hover {
      background-color: #0f2642;
    }

    .success-popup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      z-index: 9999;
      display: none;
    }

    .success-popup p {
      margin: 0;
      color: #28a745;
      font-weight: bold;
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
  <?php include('Include/header.php'); ?>

  <div id="success-popup" class="success-popup"
  <?php if (isset($_GET['success']) && $_GET['success'] == 1) echo 'style="display: block;"';
     ?>>
    <p id="success-popup-message">Profile created successfully!<br>
      Your ID is: 
      <?php
      echo $_SESSION['user_id'];
      session_destroy();
      ?>
    </p>
  </div>

  <main>
    <h1>Sign Up</h1>
    <form method="POST" action="Configurations/createProfile.php">
      <div>
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required>
      </div>

      <div>
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required>
      </div>

      <div>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
      </div>

      <div>
        <label for="phone">Phone:</label>
        <input type="text" name="phone" required>
      </div>

      <div class="divider"></div>

      <div>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
      </div>

      <p style="font-weight: bold;">Note that the new password should comply with the following rules:</p>

      <ul class="password-requirements">
        <li>Must be at least 8 characters long.</li>
        <li>Must contain at least one lowercase letter.</li>
        <li>Must contain at least one uppercase letter.</li>
        <li>Must contain at least one number.</li>
        <li>Must contain at least one symbol.</li>
      </ul>
      
      <div class="divider"></div>

      <div>
        <label for="city">City:</label>
        <input type="text" name="city" required>
      </div>

      <div>
        <label for="district">District:</label>
        <input type="text" name="district" required>
      </div>

      <div>
        <label for="street">Street:</label>
        <input type="text" name="street" required>
      </div>

      <div class="divider"></div>

      <div class="button-container">
        <button type="submit">Save Changes</button>
      </div>
    </form>
    <?php
        // Check if there are any transfer errors in the session
        if (isset($_SESSION['create_errors'])) {
            $updateErrors = $_SESSION['create_errors'];
            unset($_SESSION['create_errors']);
            echo '<div style="color: red;">';
            foreach ($updateErrors as $error) {
                echo "<p>Error: $error</p>";
            }
            echo '</div>';
        }
        ?>
  </main>

  <?php include('Include/footer.php');?>

</body>
</html>