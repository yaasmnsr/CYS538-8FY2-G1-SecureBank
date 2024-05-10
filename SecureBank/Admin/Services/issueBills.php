<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Issue Bills</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">

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
  <?php include('../Include/header.php'); ?>

  <main class="issue-bill-main">
    <h1 style="margin-bottom: 10px;">Issue Bills</h1>

    <div id="success-popup" class="success-popup" <?php if (isset($_GET['success']) && $_GET['success'] == 1) echo 'style="display: block;"'; ?>>
    <p id="success-popup-message">Bill issued successfully!</p>
    </div>

    <div class="issue-bill"> 
    <form action="../../Configurations/issueBill.php" method="POST">
      <div class="form-group">
      <label for="user-id">Customer:</label>
      <select id="user-id" name="user_id" required>
        <?php
          require('../../Configurations/databaseConfig.php');

          // Retrieve the list of users from the database
          $query = "SELECT user_id FROM users";
          $result = mysqli_query($conn, $query);

          // Check if the query was successful
          if ($result) {
            // Loop through the retrieved rows
            while ($row = mysqli_fetch_assoc($result)) {
              $userId = $row['user_id'];

              // Display each user ID as an option in the select dropdown
              echo "<option value='$userId'>$userId</option>";
            }

            // Free the result set
            mysqli_free_result($result);
          }

          mysqli_close($conn);
        ?>
      </select>
      </div>

      <div class="form-group">
      <label for="bill-type">Bill Type:</label>
      <select id="bill-type" name="bill_type" required>
        <option value="Utility Bill">Utility Bill</option>
        <option value="Subscription Bill">Subscription Bill</option>
        <option value="Financial Bill">Financial Bill</option>
        <option value="Education Bill">Education Bill</option>
        <option value="Healthcare Bill">Healthcare Bill</option>
        <option value="Fine Bill">Fine Bill</option>
      </select>
      </div>
      
      <div class="form-group">
      <label for="amount">Amount:</label>
      <input type="text" id="amount" name="amount" required>
      </div>
      
      <div class="form-group">
      <label for="issued-date">Issued Date:</label>
      <input type="date" id="issued-date" name="issued_date"  value="<?php echo date('Y-m-d'); ?>" required readonly>
      </div>

      <input type="submit" class="button" value="Issue Bill">
      </div>
    </form>
    </div>

    <?php
        // Check if there are any transfer errors in the session
        if (isset($_SESSION['issueBill_errors'])) {
          $issueBillErrors = $_SESSION['issueBill_errors'];
          unset($_SESSION['issueBill_errors']);
          echo '<div style="color: red;">';
          foreach ($issueBillErrors as $error) {
            echo "<p>Error: $error</p>";
          }
          echo '</div>';
        }
      ?>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php');?>
</body>

</html>