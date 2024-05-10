<?php
require('databaseConfig.php');

session_start();

$errors = [];

$billId = filter_var($_POST['billId'], FILTER_SANITIZE_NUMBER_INT);
$accountId = filter_var($_POST['accountId'], FILTER_SANITIZE_NUMBER_INT);

// Retrieve the account information
$accountQuery = "SELECT * FROM accounts WHERE account_id = ? LIMIT 1";
$stmt1 = mysqli_prepare($conn, $accountQuery);
mysqli_stmt_bind_param($stmt1, "i", $accountId);
mysqli_stmt_execute($stmt1);
$accountResult = mysqli_stmt_get_result($stmt1);
$account = mysqli_fetch_assoc($accountResult);

// Check if the account has sufficient funds
$billQuery = "SELECT * FROM billpayments WHERE bill_id = ? LIMIT 1";
$stmt2 = mysqli_prepare($conn, $billQuery);
mysqli_stmt_bind_param($stmt2, "i", $billId);
mysqli_stmt_execute($stmt2);
$billResult = mysqli_stmt_get_result($stmt2);
$bill = mysqli_fetch_assoc($billResult);

if ($bill['amount'] > $account['balance']) {
    $errors[] = "Insufficient funds in the selected account.";
}

// Check if the account is active
if ($account['account_status'] !== 'Active') {
    $errors[] = "The selected account is inactive and cannot make payments.";
}

// If there are any validation errors, store them in the session and redirect back to billPayment.php
if (!empty($errors)) {
    $_SESSION['payment_errors'] = $errors;
    header("Location: ../Users/Services/billPayment.php");
    exit;
}

// Begin the database transaction
mysqli_begin_transaction($conn);

try {
    // Update the bill status to "In Process" and record the account ID and current time
    $updateBillQuery = "UPDATE billpayments SET status = 'In Process', account_id = ?, payment_time = NOW() WHERE bill_id = ?";
    $stmt3 = mysqli_prepare($conn, $updateBillQuery);
    mysqli_stmt_bind_param($stmt3, "ii", $accountId, $billId);
    mysqli_stmt_execute($stmt3);

    // Deduct the bill amount from the account balance
    $updateAccountQuery = "UPDATE accounts SET balance = balance - ? WHERE account_id = ?";
    $stmt4 = mysqli_prepare($conn, $updateAccountQuery);
    mysqli_stmt_bind_param($stmt4, "di", $bill['amount'], $accountId);
    mysqli_stmt_execute($stmt4);

    // Update the bill status to "Completed" and record the account ID and current time
    $updateBillQuery = "UPDATE billpayments SET status = 'Completed', account_id = ?, payment_time = ? WHERE bill_id = ?";
    $stmt5 = mysqli_prepare($conn, $updateBillQuery);

    // Get the current date and time
    $payment_time = date('Y-m-d H:i:s');

    mysqli_stmt_bind_param($stmt5, "isi", $accountId, $payment_time, $billId);
    mysqli_stmt_execute($stmt5);

    // Commit the transaction
    mysqli_commit($conn);

    // Generate the receipt message
    $receiptMessage['bill_id'] = $billId;
    $receiptMessage['user_id'] = $_SESSION['user_id'];
    $receiptMessage['bill_type'] = $bill['bill_type'];
    $receiptMessage['amount'] = $bill['amount'];
    $receiptMessage['issued_date'] = $bill['issued_date'];
    $receiptMessage['account_number'] = $account['account_number'];
    $receiptMessage['payment_time'] = $payment_time;

    // Store the receipt message in the session
    $_SESSION['bill_receipt'] = $receiptMessage;

    // Redirect to the receipt page
    header("Location: ../Users/Services/receipt.php?success=1");
    exit;
} catch (Exception $e) {
    // Rollback the transaction if an error occurred
    mysqli_rollback($conn);

    // Save the error message in the session
    $_SESSION['payment_errors'] = "An error occurred while processing the bill payment. Please try again later.";
    header("Location: ../Users/Services/billPayment.php");
    exit;
}
?>