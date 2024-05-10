<?php
require('databaseConfig.php');

session_start();

$errors = [];

$accounts = $_SESSION['accounts'];

$senderAccountNumber = filter_var($_POST['sender_account'], FILTER_SANITIZE_STRING);
$recipientAccountNumber = filter_var($_POST['recipient_account'], FILTER_SANITIZE_STRING);

$accountNumberRegex = '/^[a-zA-Z0-9]+$/';

if (empty($senderAccountNumber)) {
    $errors[] = "Sender account is required.";
} elseif (!preg_match($accountNumberRegex, $senderAccountNumber)) {
    $errors[] = "Invalid sender account number.";
} else{
    // Get the sender's account
    $senderAccount = null;
    foreach ($accounts as $account) {
        if ($account['account_number'] === $senderAccountNumber) {
            $senderAccount = $account;
            break;
        }
    }
    // Check if the sender's account exists
    if (!$senderAccount) {
        $errors[] = "Sender account does not exist.";
    } elseif ($senderAccount['account_status'] !== 'Active') {
        $errors[] = "Sender account is inactive and cannot conduct the transaction.";
    }
}

$allAccounts = null;
// Retrieve accounts from the database
$stmt = $conn->prepare("SELECT * FROM accounts");
$stmt->execute();
$accountResult = $stmt->get_result();
$allAccounts = $accountResult->fetch_all(MYSQLI_ASSOC);

// Validate recipient account
if (empty($recipientAccountNumber)) {
    $errors[] = "Recipient account is required.";
} elseif (!preg_match($accountNumberRegex, $recipientAccountNumber)) {
    $errors[] = "Invalid recipient account number.";
} else {
    // Get the recipient's account
    $recipientAccount = null;
    foreach ($allAccounts as $account) {
        if ($account['account_number'] === $recipientAccountNumber) {
            $recipientAccount = $account;
            break;
        }
    }
    // Check if the recipient's account exists
    if (!$recipientAccount) {
        $errors[] = "Recipient account does not exist.";
    } elseif ($recipientAccountNumber === $senderAccountNumber) {
        $errors[] = "Cannot transfer funds to the same account.";
    }
}

// Validate amount
$amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
if (empty($amount)) {
    $errors[] = "Amount is required.";
} elseif (!is_numeric($amount)) {
    $errors[] = "Amount must be a valid number.";
} elseif ($amount < 0) {
    $errors[] = "Amount cannot be negative.";
} elseif ($amount > $senderAccount['balance']) {
    $errors[] = "Insufficient balance.";
}

// If there are any validation errors, store them in the session and redirect back to fundsTransfer.php
if (!empty($errors)) {
    $_SESSION['transfer_errors'] = $errors;
    header("Location: ../Users/Services/fundsTransfer.php");
    exit;
}

// Begin the database transaction
mysqli_begin_transaction($conn);

try {
    // Deduct the amount from the sender's balance
    $updateSenderQuery = "UPDATE accounts SET balance = balance - ? WHERE account_number = ?";
    $stmt1 = mysqli_prepare($conn, $updateSenderQuery);
    mysqli_stmt_bind_param($stmt1, "ds", $amount, $senderAccount['account_number']);
    mysqli_stmt_execute($stmt1);

    // Add the amount to the recipient's balance
    $updateReceiverQuery = "UPDATE accounts SET balance = balance + ? WHERE account_number = ?";
    $stmt2 = mysqli_prepare($conn, $updateReceiverQuery);
    mysqli_stmt_bind_param($stmt2, "ds", $amount, $recipientAccount['account_number']);
    mysqli_stmt_execute($stmt2);

    // Insert transaction details into the funds transfer table
    $transferTime = date('Y-m-d H:i:s');
    $insertTransactionQuery = "INSERT INTO fundstransfer (sender_accountId, recipient_accountId, transfer_amount, transfer_time) VALUES (?, ?, ?, ?)";
    $stmt3 = mysqli_prepare($conn, $insertTransactionQuery);
    mysqli_stmt_bind_param($stmt3, "ddds", $senderAccount['account_id'], $recipientAccount['account_id'], $amount, $transferTime);
    mysqli_stmt_execute($stmt3);

    // Get the transfer ID
    $transferId = mysqli_insert_id($conn);

    // Commit the transaction
    mysqli_commit($conn);

    // Generate the receipt message
    $receiptMessage['transfer_id'] = $transferId;
    $receiptMessage['sender_account'] = $senderAccount['account_number'];
    $receiptMessage['recipient_account'] = $recipientAccount['account_number'];
    $receiptMessage['amount'] = $amount;
    $receiptMessage['transfer_time'] = $transferTime;

    // Store the receipt message in the session
    $_SESSION['transfer_receipt'] = $receiptMessage;

    // Redirect to the receipt page
    header("Location: ../Users/Services/receipt.php?success=1");
    exit;
} catch (mysqli_sql_exception $e) {
    // Rollback the transaction in case of an exception
    mysqli_rollback($conn);
    
    die("Transfer failed: " . $e->getMessage());
}