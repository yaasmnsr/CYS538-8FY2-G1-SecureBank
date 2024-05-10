<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Account Statements</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">

  <script>
    function exportToPdf() {
      const sectionElement = document.querySelector('.account-statements');

      // Create a new window to open the print dialog
      const printWindow = window.open('', '_blank');

      // Write the section HTML to the print window
      printWindow.document.open();
      printWindow.document.write(`
        <html>
          <head>
            <style>
              @page {
                padding: 60px 0;
                background-color: #fff;
                max-width: 1000px;
                margin: 50px auto;
                box-shadow: 10px 10px 5px rgba(0, 0, 0, 0.1);
                border-radius: 5px;
                margin-bottom: 15px;
              }

              h2 {
                color: #1d3557;
                font-size: 36px;
                margin-bottom: 20px;
                text-align: center;
              }

              h3 {
                color: #1d3557;
                font-size: 24px;
                margin-top: 30px;
                margin-bottom: 10px;
              }

              p {
                margin-bottom: 15px;
                line-height: 1.6;
              }

              ul {
                margin-bottom: 15px;
                padding-left: 20px;
              }

              li {
                margin-bottom: 5px;
              }
            </style>
          </head>
          <body>
            ${sectionElement.outerHTML}
          </body>
        </html>
      `);
      printWindow.document.close();

      printWindow.addEventListener('load', () => {
        // Print the window
        printWindow.print();
      });
    }
  </script>
</head>

<body>
  <?php include('../Include/header.php'); ?>
  <?php
    require('../../Configurations/databaseConfig.php');

    $accountIds = array_column($accounts, 'account_id');
    $placeholders = '';
    if (!empty($accountIds)) {
        $placeholders = str_repeat('?,', count($accountIds) - 1) . '?';
    }

    $query = "SELECT * FROM fundstransfer WHERE sender_accountId IN ($placeholders) AND transfer_time > ?";
    if (!empty($placeholders)) {
        $stmt = $conn->prepare($query);
        $params = array_merge($accountIds, [$loginTime]);
        $stmt->bind_param(str_repeat('s', count($accountIds)) . 's', ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $transfers = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        $transfers = [];
    }

    $query = "SELECT * FROM billpayments WHERE user_id = ? AND payment_time > ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $userId, $loginTime);
    $stmt->execute();
    $result = $stmt->get_result();
    $bills = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
  ?>

<section class="account-statements">
  <div class="container">
    <h2>Account Statements: <br> <i> <?php echo date('Y-m-d H:i:s');?></i></h2>

    <hr style="border-top: 1px solid #f1f1f1; margin-top: 30px; margin-bottom: 30px;">
    
    <h3>Customer Information:</h3>
      <p><b>Customer ID: </b><i><?php echo $userId; ?></i></p>
      <ul>
        <li><b>Customer's Full Name: </b><?php echo $userFirstName . ' ' . $userLastName ?></li>
        <li><b>Email: </b><?php echo $email; ?></li>
        <li><b>Phone Number: </b><?php echo '+966 0' . $phone; ?></li>
      </ul>

    <hr style="border-top: 1px solid #f1f1f1; margin-top: 30px; margin-bottom: 30px;">
    
    <h3>Accounts:</h3>
    <?php if (count($accounts) > 0) : ?>
      <?php foreach ($accounts as $account) : ?>
        <p><b>Account Number: </b><i><?php echo $account['account_number']; ?></i></p>
        <ul>
          <li><b>Account Type: </b><?php echo $account['account_type']; ?></li>
          <li><b>Account Status: </b><?php echo $account['account_status']; ?></li>
          <li><b>Balance: </b><?php echo $account['balance']; ?></li>
        </ul>
        <?php endforeach; ?>
    <?php else : ?>
      <p>No accounts found.</p>
    <?php endif; ?>

    <hr style="border-top: 1px solid #f1f1f1; margin-top: 30px; margin-bottom: 30px;">

    <h3>Transfers:</h3>
    <?php if (count($transfers) > 0) : ?>
      <?php foreach ($transfers as $transfer) : ?>
        <p><b>Transfer ID: </b><i><?php echo $transfer['transfer_id']; ?></i></p>
        <ul>
          <?php
          $query = "SELECT account_number FROM accounts WHERE account_id = ?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("i", $transfer['recipient_accountId']);
          $stmt->execute();
          $result = $stmt->get_result();
          $recipientAccountNo = $result->fetch_assoc();
          $stmt->close();
          ?>
          <li><b>Recipient Account Number: </b><?php echo $recipientAccountNo['account_number']; ?></li>
          <li><b>Transfer Amount: </b><?php echo $transfer['transfer_amount']; ?></li>
          <li><b>Transfer Time: </b><?php echo $transfer['transfer_time']; ?></li>
        </ul>
      <?php endforeach; ?>
    <?php else : ?>
      <p>No transfers found.</p>
    <?php endif; ?>
    
    <hr style="border-top: 1px solid #f1f1f1; margin-top: 30px; margin-bottom: 30px;">

    <h3>Bills:</h3>
    <?php if (count($bills) > 0) : ?>
      <?php foreach ($bills as $bill) : ?>
        <p><b>Bill ID: </b><i><?php echo $bill['bill_id']; ?></i></p>
        <ul>
          <li><b>Bill Type: </b><?php echo $bill['bill_type']; ?></li>
          <li><b>Bill Amount: </b><?php echo $bill['amount']; ?></li>
          <li><b>Payment Time: </b><?php echo $bill['payment_time']; ?></li>
        </ul>
        <?php endforeach; ?>
    <?php else : ?>
      <p>No bills found.</p>
    <?php endif; ?>

    <hr style="border-top: 1px solid #f1f1f1; margin-top: 30px; margin-bottom: 30px;">
  </div>
  </section>

  <div class="export-button-container">
    <button class="button" onclick="exportToPdf()">Export to PDF</button>
  </div>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php'); ?>

</body>
</html>