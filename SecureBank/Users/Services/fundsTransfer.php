<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Funds Transfer</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">

</head>
<body>
  <?php include('../Include/header.php'); ?>

  <main class="small-main">
    <h1>Funds Transfer</h1>
    <form action="../../Configurations/processTransfer.php" method="POST">
      <label for="sender_account">Sender Account:</label>
      <select id="sender_account" name="sender_account">
        <?php
        // Generate the option elements for each account
        foreach ($accounts as $account) {
            $accountNumber = $account['account_number'];
            $selected = isset($_POST['sender_account']) && $_POST['sender_account'] === $accountNumber ? 'selected' : '';
            echo "<option value='{$accountNumber}' {$selected}>{$accountNumber}</option>";
        }
        ?>
      </select>

      <label for="recipient_account">Recipient Account:</label>
      <input type="text" id="recipient_account" name="recipient_account">

      <label for="amount">Amount:</label>
      <input type="text" id="amount" name="amount">

      <?php
        // Check if there are any transfer errors in the session
        if (isset($_SESSION['transfer_errors'])) {
            $transferErrors = $_SESSION['transfer_errors'];
            unset($_SESSION['transfer_errors']);
            echo '<div style="color: red;">';
            foreach ($transferErrors as $error) {
                echo "<p>Error: $error</p>";
            }
            echo '</div>';
        }
        ?>

      <button type="submit">Transfer</button>
    </form>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php'); ?>

</body>
</html>