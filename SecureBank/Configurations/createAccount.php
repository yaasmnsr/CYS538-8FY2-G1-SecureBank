<?php
require('databaseConfig.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate the account details from the form
    $userId = sanitizeInput($_POST['userId']);
    $accountNumber = sanitizeInput($_POST['accountNumber']);
    $accountType = sanitizeInput($_POST['accountType']);
    $dateOpened = sanitizeInput($_POST['dateOpened']);

    // Validate the input
    if (empty($userId) || empty($accountNumber) || empty($accountType) || empty($dateOpened)) {
        $errors[] = 'All fields are required.';
    }

    // If there are no validation errors, store the account details in the database
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO accounts (user_id, account_number, account_type, date_opened) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $userId, $accountNumber, $accountType, $dateOpened);
        $stmt->execute();

        // Check if the insertion was successful
        if ($stmt->affected_rows < 0) {
            $errors[] = 'Failed to create the account.';
        }

        $stmt->close();
    }

    // If there are any validation errors, store them in the session and redirect back to createAccount.php
    if (!empty($errors)) {
        session_start();
        $_SESSION['accountError'] = $errors;
        header("Location: ../Users/Services/createAccount.php");
        exit;
    }
}

// Redirect the user back to the user page when the process is successful
header('Location: /SecureBank/Users/Services/userAccounts.php?success=1');
exit();

// Function to sanitize user input
function sanitizeInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}
?>