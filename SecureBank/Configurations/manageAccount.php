<?php
include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Configurations/databaseConfig.php');

session_start();

$errors = [];

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the account ID and action from the form data
  $accountId = $_POST["accountId"];
  $action = $_POST["action"];

  // Update the account status based on the action
  switch ($action) {
    case "approve":
      $status = "Active";
      break;
    case "suspend":
      $status = "Suspended";
      break;
    case "close":
      $status = "Closed";
      break;
    case "activate":
      $status = "Active";
      break;
    default:
      $errors[] = "Invalid account status.";
  }

  $query = "UPDATE accounts SET account_status = ? WHERE account_id = ?";
  $statement = mysqli_prepare($conn, $query);

  mysqli_stmt_bind_param($statement, "si", $status, $accountId);
  mysqli_stmt_execute($statement);

  if (mysqli_stmt_affected_rows($statement) > 0) {
    header("Location: ../Admin/Services/manageAccounts.php?success=1");
    exit();
  } else {
    $errors[] = "Failed to update.";
  }

  // If there are any validation errors, store them in the session and redirect back to profileSettings.php
  if (!empty($errors)) {
    $_SESSION['update_errors'] = $errors;
    header("Location: ../Admin/Services/manageAccounts.php");
    exit;
  }

  // Close the statement and connection
  mysqli_stmt_close($statement);
  mysqli_close($conn);

} else {
  // If the form data is not submitted, redirect back to the account management page
  header("Location: ../Admin/Services/manageAccounts.php");
  exit();
}
?>