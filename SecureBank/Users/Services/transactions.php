<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - User Page</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">
</head>

<body>
  <?php include('../Include/header.php'); ?>

  <main class="big-main">
    <h1 style="margin-bottom: 10px;">Past Transfers</h1>
    <table>
      <tr>
        <th>ID</th>
        <th>Sender Account</th>
        <th>Recipient Account</th>
        <th>Date</th>
        <th>Amount</th>
      </tr>
      <?php
      require('../../Configurations/databaseConfig.php');

      // Prepare the statement for retrieving the user's account IDs
      $accountsQuery = "SELECT account_id, account_number FROM accounts WHERE user_id = ?";
      $accountsStmt = $conn->prepare($accountsQuery);
      $accountsStmt->bind_param("i", $userId);
      $accountsStmt->execute();
      $accountsResult = $accountsStmt->get_result();

      $hasTransfers = false; // Flag to track if the user has any transfers

      // Iterate through the user's account IDs
      while ($accountRow = $accountsResult->fetch_assoc()) {
        $accountId = $accountRow["account_id"];
        $accountNumber = $accountRow["account_number"];

        // Prepare the statement for retrieving past transfers sent by the current account
        $transfersQuery = "SELECT fundstransfer.transfer_id, fundstransfer.transfer_time, fundstransfer.transfer_amount, sender.account_number AS sender_account_number, recipient.account_number AS recipient_account_number
                          FROM fundstransfer
                          INNER JOIN accounts AS sender ON fundstransfer.sender_accountId = sender.account_id
                          INNER JOIN accounts AS recipient ON fundstransfer.recipient_accountId = recipient.account_id
                          WHERE fundstransfer.sender_accountId = ?";
        $transfersStmt = $conn->prepare($transfersQuery);
        $transfersStmt->bind_param("i", $accountId);
        $transfersStmt->execute();
        $transfersResult = $transfersStmt->get_result();

        if ($transfersResult->num_rows > 0) {
          $hasTransfers = true; // User has transfers
          while ($row = $transfersResult->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["transfer_id"] . "</td>
                    <td>" . $row["sender_account_number"] . "</td>
                    <td>" . $row["recipient_account_number"] . "</td>
                    <td>" . $row["transfer_time"] . "</td>
                    <td>" . $row["transfer_amount"] . "</td>
                  </tr>";
          }
        }

        $transfersResult->close();

        $transfersStmt->close();
      }

      $accountsResult->close();

      $accountsStmt->close();

      // Check if the user has no transfers at all
      if (!$hasTransfers) {
        echo "<tr><td colspan='5'>No transfers found for the user.</td></tr>";
      }
      ?>
    </table>
  </main>

  <main class="big-main">
    <h1 style="margin-bottom: 10px;">Past Bill Payments</h1>
    <table>
      <tr>
        <th>Bill ID</th>
        <th>Bill Type</th>
        <th>Amount</th>
        <th>Date</th>
        <th>Account Number</th>
        <th>Time</th>
        <th>Status</th>
      </tr>
      <?php
      // Prepare the statement for retrieving bills for the current user
      $paymentsQuery = "SELECT billpayments.bill_id, billpayments.bill_type, billpayments.amount, billpayments.issued_date, accounts.account_number, billpayments.payment_time, billpayments.status
                        FROM billpayments
                        INNER JOIN accounts ON billpayments.account_id = accounts.account_id
                        WHERE billpayments.user_id = ?";
      $paymentsStmt = $conn->prepare($paymentsQuery);
      $paymentsStmt->bind_param("i", $userId);
      $paymentsStmt->execute();
      $paymentsResult = $paymentsStmt->get_result();

      if ($paymentsResult->num_rows > 0) {
        while ($row = $paymentsResult->fetch_assoc()) {
          echo "<tr>
                  <td>" . $row["bill_id"] . "</td>
                  <td>" . $row["bill_type"] . "</td>
                  <td>" . $row["amount"] . "</td>
                  <td>" . $row["issued_date"] . "</td>
                  <td>" . $row["account_number"] . "</td>
                  <td>" . $row["payment_time"] . "</td>
                  <td>" . $row["status"] . "</td>
                </tr>";
        }
      } else {
        echo "<tr><td colspan='7'>No past bill payments found for this user.</td></tr>";
      }

      $paymentsResult->close();

      $paymentsStmt->close();

      $conn->close();
      ?>
    </table>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php'); ?>

</body>

</html>