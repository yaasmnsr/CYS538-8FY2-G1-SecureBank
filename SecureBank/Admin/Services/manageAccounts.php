<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Manage Accounts</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">

  <script>
    function manageAccount(accountId, action) {
      var form = document.createElement("form");
      form.method = "POST";
      form.action = "../../Configurations/manageAccount.php";

      var accountIdInput = document.createElement("input");
      accountIdInput.type = "hidden";
      accountIdInput.name = "accountId";
      accountIdInput.value = accountId;

      var actionInput = document.createElement("input");
      actionInput.type = "hidden";
      actionInput.name = "action";
      actionInput.value = action;

      form.appendChild(accountIdInput);
      form.appendChild(actionInput);

      document.body.appendChild(form);
      form.submit();
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
  <?php include('../Include/header.php'); ?>

  <main>
    <h1 style="margin-bottom: 10px;">Manage Accounts</h1>

    <div id="success-popup" class="success-popup" <?php if (isset($_GET['success']) && $_GET['success'] == 1) echo 'style="display: block;"'; ?>>
    <p id="success-popup-message">Account status updated successfully!</p>
    </div>

    <table id="account-table">
      <thead>
        <tr>
          <th>Account ID</th>
          <th>Account Number</th>
          <th>Account Type</th>
          <th>Date Opened</th>
          <th>Account Status</th>
          <th>Balance</th>
          <th>User ID</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
        include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Configurations/databaseConfig.php');

        // Prepare the query
        $query = "SELECT a.account_id, a.account_number, a.account_type, a.date_opened, a.account_status, a.balance, a.user_id
                  FROM accounts a";
        $statement = mysqli_prepare($conn, $query);

        // Execute the query
        mysqli_stmt_execute($statement);

        // Bind the result variables
        mysqli_stmt_bind_result($statement, $account_id, $account_number, $account_type, $date_opened, $account_status, $balance, $user_id);

        // Fetch the first row
        mysqli_stmt_fetch($statement);

        if (empty($account_id)) {
          echo "<tr><td colspan='7'>No users exist.</td></tr>";
        } else {
          // Loop through the account data and display it in the table
          do {
            echo "<tr>";
            echo "<td>" . $account_id . "</td>";
            echo "<td>" . $account_number . "</td>";
            echo "<td class='account-type'>" . $account_type . "</td>";
            echo "<td class='date-opened'>" . $date_opened . "</td>";
            echo "<td class='account-status'>" . $account_status . "</td>";
            echo "<td>" . $balance . "</td>";
            echo "<td>" . $user_id . "</td>";

            echo "<td>";
            if ($account_status === 'Pending Approval') {
              echo "<button class='action-button' onclick='manageAccount($account_id, \"approve\")'>Approve</button>";
            } elseif ($account_status === 'Active') {
              echo "<button class='action-button' onclick='manageAccount($account_id, \"suspend\")'>Suspend</button>";
              echo "<button class='action-button' onclick='manageAccount($account_id, \"close\")'>Close</button>";
            } elseif ($account_status === "Closed" || $account_status === "Suspended") {
              echo "<button class='action-button' onclick='manageAccount($account_id, \"activate\")'>Activate</button>";
            }
            echo "</td>";
            echo "</tr>";
          } while (mysqli_stmt_fetch($statement));
        }

        mysqli_stmt_close($statement);
        mysqli_close($conn);
        ?>
      </tbody>
    </table>

    <div class="divider"></div>
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

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php');?>

</body>
</html>