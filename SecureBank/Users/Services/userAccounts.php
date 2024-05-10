<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - User Accounts</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">

  <style>

  </style>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const urlParams = new URLSearchParams(window.location.search);
      const message = urlParams.get('message'); // message containing account has been successfuly created

      if (message !== null && message !== '') {
        const popup = document.getElementById('account-popup');
        const popupMessage = document.getElementById('popup-message');
        popupMessage.innerText = message;
        popup.style.display = 'block';
        setTimeout(() => {
          popup.style.display = 'none';
        }, 3000);
      }
    });

    function showPopup(accountType, accountNumber, dateOpened, accountStatus, balance) {
      var titleElement = document.getElementById("popupAccountTitle");
      var numberElement = document.getElementById("popupAccountNumber");
      var dateElement = document.getElementById("popupDateOpened");
      var statusElement = document.getElementById("popupAccountStatus");
      var balanceElement = document.getElementById("popupAccountBalance");

      titleElement.textContent = accountType + " Account";
      numberElement.textContent = accountNumber;
      dateElement.textContent = dateOpened;
      statusElement.textContent = accountStatus;
      balanceElement.textContent = balance;

      document.getElementById("popupOverlay").classList.add("active");
      document.getElementById("popupContent").classList.add("active");
    }

    function hidePopup() {
      document.getElementById("popupOverlay").classList.remove("active");
      document.getElementById("popupContent").classList.remove("active");
    }

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
  <?php include '../Include/header.php'; ?>

  <div id="success-popup" class="success-popup" <?php if (isset($_GET['success']) && $_GET['success'] == 1) echo 'style="display: block;"'; ?>>
    <p id="success-popup-message">Account created successfully!</p>
  </div>

  <br>

  <?php
  // Check if there are any accounts
  if (empty($accounts)) {
    echo '<div class="container">';
    echo '<div class="no-accounts">';
    echo '<p>There are no accounts under this user.</p>';
    echo '</div>';
    echo '</div>';
  } else {
    // Iterating over each account
    foreach ($accounts as $account) {
      $accountNumber = $account['account_number'];
      $accountType = $account['account_type'];
      $dateOpened = $account['date_opened'];
      $accountStatus = $account['account_status'];
      $balance = $account['balance'];

      // Define the CSS class and text for the account status
      $statusClass = '';
      $statusText = '';

      switch ($accountStatus) {
        case 'Active':
          $statusClass = 'status-active';
          $statusText = 'Active';
          break;
        case 'Closed':
          $statusClass = 'status-closed';
          $statusText = 'Closed';
          break;
        case 'Suspended':
          $statusClass = 'status-suspended';
          $statusText = 'Suspended';
          break;
        case 'Frozen':
          $statusClass = 'status-frozen';
          $statusText = 'Frozen';
          break;
        case 'Pending Approval':
          $statusClass = 'status-pending';
          $statusText = 'Pending Approval';
          break;
      }
      ?>
      <div class="container-accounts">
        <div class="account-card">
          <div class="status-bar <?php echo $statusClass; ?>"></div>
          <h2><?php echo $accountType; ?> Account</h2>
          <p>Account Number: <span class="account-number"><?php echo $accountNumber; ?></span></p>
          <p class="balance">Balance: <?php echo $balance . " SAR"; ?></p>
          <div class="actions">
            <a href="#" onclick="showPopup('<?php echo $accountType; ?>', '<?php echo $accountNumber; ?>', '<?php echo $dateOpened; ?>', '<?php echo $accountStatus; ?>', '<?php echo $balance; ?>')">View Details</a>
          </div>
        </div>
      </div>
      <?php
    }
  }
  ?>

  <div class="create-account-button">
    <a href="createAccount.php">Create New Account</a>
  </div>

  <div class="popup-overlay" id="popupOverlay">
    <div class="popup-content" id="popupContent">
      <span class="popup-close" onclick="hidePopup()">&times;</span>
      <h2 id="popupAccountTitle"></h2>
      <p>Account Number: <span id="popupAccountNumber"></span></p>
      <p>Date Opened: <span id="popupDateOpened"></span></p>
      <p>Account Status: <span id="popupAccountStatus"></span></p>
      <p>Balance: <span id="popupAccountBalance"></span> SAR</p>
    </div>
  </div>

  <br>

  <div id="account-popup" class="account-popup">
  <p id="popup-message"></p>
  </div>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php'; ?>
</body>

</html>