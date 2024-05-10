<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Receipt</title>
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

  <?php
  // Check if the receipt exists in the session
  if (isset($_SESSION['transfer_receipt'])) { ?>
    <div id="success-popup" class="success-popup" <?php if (isset($_GET['success']) && $_GET['success'] == 1) echo 'style="display: block;"'; ?>>
    <p id="success-popup-message">Successful transaction!</p>
    </div>

    <?php
    $receipt = $_SESSION['transfer_receipt'];
    unset($_SESSION['transfer_receipt']);
    ?>

    <main class="receipt">
      <h1>Transfer Receipt</h1>
      <p><b>Transfer ID:</b> <?php echo $receipt['transfer_id']; ?></p>
      <p><b>Transfer Time:</b> <?php echo $receipt['transfer_time']; ?></p>
      <p><b>Sender Account:</b> <?php echo $receipt['sender_account']; ?></p>
      <p><b>Recipient Account:</b> <?php echo $receipt['recipient_account']; ?></p>
      <p><b>Amount:</b> <?php echo $receipt['amount']; ?></p>
      <p><b>Transfer Time:</b> <?php echo $receipt['transfer_time']; ?></p>

      <div class="button-container">
        <button onclick="window.location.href = '/SecureBank/Users/Services/fundsTransfer.php';">Make Another Transaction</button>
        <button onclick="window.location.href = '/SecureBank/Users/Services/transactions.php';">View Transactions History</button>
        <button onclick="window.location.href = '/SecureBank/Users/Home/index.php';">Go to Home</button>
      </div>
    </main>

  <?php } else if (isset($_SESSION['bill_receipt'])) { ?>
    <div id="success-popup" class="success-popup" <?php if (isset($_GET['success']) && $_GET['success'] == 1) echo 'style="display: block;"'; ?>>
    <p id="success-popup-message">Successful transaction!</p>
    </div>

    <?php
    $receipt = $_SESSION['bill_receipt'];
    unset($_SESSION['bill_receipt']);
    ?>
    <main class="receipt">
      <h1>Bill Receipt</h1>
      <p><b>Bill ID:</b> <?php echo $receipt['bill_id']; ?></p>
      <p><b>User ID:</b> <?php echo $receipt['user_id']; ?></p>
      <p><b>Bill Type:</b> <?php echo $receipt['bill_type']; ?></p>
      <p><b>Issued Date:</b> <?php echo $receipt['issued_date']; ?></p>
      <p><b>Account Number:</b> <?php echo $receipt['account_number']; ?></p>
      <p><b>Payment Time:</b> <?php echo $receipt['payment_time']; ?></p>
      <p><b>Amount:</b> <?php echo $receipt['amount']; ?></p>

      <div class="button-container">
      <button onclick="window.location.href = '/SecureBank/Users/Services/billPayment.php';">Go To Bill Payments</button>
        <button onclick="window.location.href = '/SecureBank/Users/Services/transactions.php';">View Transactions History</button>
        <button onclick="window.location.href = '/SecureBank/Users/Home/index.php';">Go to Home</button>
      </div>
    </main>

    <?php } else { ?>
    <main>
      <h1>Receipt</h1>
      <p>Receipt not found.</p>
      <div class="button-container">
        <button onclick="window.location.href = '/SecureBank/Users/index.php';">Go to Home</button>
      </div>
    </main>
  <?php } ?>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php'); ?>

</body>

</html>