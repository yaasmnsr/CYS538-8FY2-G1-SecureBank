<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Bill Payments</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">
</head>

<body>
  <?php include('../Include/header.php'); ?>

  <main class="medium-main">
    <h1 style="margin-bottom: 10px;">Pending Bills</h1>
    <?php
    require('../../Configurations/databaseConfig.php');

    $query = "SELECT * FROM billpayments WHERE user_id = $userId AND status = 'Pending'";
    $result = mysqli_query($conn, $query);

    // Check if any bills were found
    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>Bill ID</th><th>Amount</th><th>Issued Date</th><th>Bill Type</th><th>Account Number</th><th>Action</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            // Display the bill information in a table row
            echo "<tr>";
            echo "<td>" . $row['bill_id'] . "</td>";
            echo "<td>" . $row['amount'] . "</td>";
            echo "<td>" . $row['issued_date'] . "</td>";
            echo "<td>" . $row['bill_type'] . "</td>";

            // Get the available accounts for the user
            $userAccountsQuery = "SELECT * FROM accounts WHERE user_id = $userId";
            $userAccountsResult = mysqli_query($conn, $userAccountsQuery);

            echo "<td><form method='post' action='../../Configurations/processBillPayment.php'>";
            echo "<input type='hidden' name='billId' value='" . $row['bill_id'] . "'>";
            echo "<select name='accountId'>";
            while ($accountRow = mysqli_fetch_assoc($userAccountsResult)) {
                echo "<option value='" . $accountRow['account_id'] . "'>" . $accountRow['account_number'] . "</option>";
            }
            echo "</select></td>";

            echo "<td><button class='pay-button' type='submit'>Pay</button></td>";
            echo "</form></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No pending bills found.</p>";
    }

    // Check if there are any transfer errors in the session
    if (isset($_SESSION['payment_errors'])) {
      $paymentErrors = $_SESSION['payment_errors'];
      unset($_SESSION['payment_errors']);
      echo '<div style="color: red;">';
      foreach ($paymentErrors as $error) {
        echo "<p>Error: $error</p>";
      }
      echo '</div>';
    }
    ?>
  </main>

  <main class="medium-main">
    <h1 style="margin-bottom: 10px;">Previous Bills</h1>
    <?php
    $query = "SELECT * FROM billpayments WHERE user_id = $userId AND status = 'Completed'";
    $result = mysqli_query($conn, $query);

    // Check if any bills were found
    if (mysqli_num_rows($result) > 0) {
      echo "<table>";
      echo "<tr><th>Bill ID</th><th>Amount</th><th>Issued Date</th><th>Bill Type</th><th>Payment Time</th><th>Account Number</th></tr>";
      while ($row = mysqli_fetch_assoc($result)) {
        // Display the bill information in a table row
        echo "<tr>";
        echo "<td>" . $row['bill_id'] . "</td>";
        echo "<td>" . $row['amount'] . "</td>";
        echo "<td>" . $row['issued_date'] . "</td>";
        echo "<td>" . $row['bill_type'] . "</td>";
        echo "<td>" . $row['payment_time'] . "</td>";

        // Get the account number using the account_id
        $accountId = $row['account_id'];
        $accountQuery = "SELECT account_number FROM accounts WHERE account_id = $accountId";
        $accountResult = mysqli_query($conn, $accountQuery);
        $accountRow = mysqli_fetch_assoc($accountResult);
        $accountNumber = $accountRow['account_number'];

        echo "<td>" . $accountNumber . "</td>";
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "<p>No previous bills found.</p>";
    }
    ?>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php'); ?>

</body>

</html>