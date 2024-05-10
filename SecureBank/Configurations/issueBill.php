<?php
session_start();

require("databaseConfig.php");

$errors = [];

// Function to sanitize user input
function sanitizeInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the input values from the form and sanitize them
    $user_id = sanitizeInput(filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT));
    $bill_type = sanitizeInput(filter_input(INPUT_POST, 'bill_type', FILTER_SANITIZE_STRING));
    $amount = sanitizeInput(filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
    $issued_date = sanitizeInput(filter_input(INPUT_POST, 'issued_date', FILTER_SANITIZE_STRING));

    // Whitelisted bill types
    $allowed_bill_types = [
        "Utility Bill",
        "Subscription Bill",
        "Financial Bill",
        "Education Bill",
        "Healthcare Bill",
        "Fine Bill"
    ];

    // Validate and sanitize input
    if (empty($bill_type)) {
        $errors[] = "Bill type is required.";
    } else {
        // Check if the bill type is in the allowed list
        if (!in_array($bill_type, $allowed_bill_types)) {
            $errors[] = "Invalid bill type.";
        }
    }

    if (empty($issued_date)) {
        $errors[] = "Issued date is required.";
    } else {
        // Validate the date format
        $date_regex = "/^\d{4}-\d{2}-\d{2}$/";
        if (!preg_match($date_regex, $issued_date)) {
            $errors[] = "Invalid issued date format. Please use YYYY-MM-DD.";
        }
    }

    if (empty($amount)) {
        $errors[] = "Amount is required.";
    } else {
        // Validate the amount format
        if (!is_numeric($amount) || $amount < 0) {
            $errors[] = "Invalid amount. Please enter a valid non-negative number.";
        }
    }

    // Check if the user_id exists in the users table
    $sql = "SELECT user_id FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 0) {
        $errors[] = "Invalid user ID.";
    }

    mysqli_stmt_close($stmt);

    // If there are any validation errors, store them in the session and redirect
    if (!empty($errors)) {
        $_SESSION['issueBill_errors'] = $errors;
        header("Location: ../Admin/Services/issueBills.php");
        exit;
    }

    // Save the bill to the database
    $status = "Pending";

    // Prepare the SQL statement with placeholders for the bill information
    $sql = "INSERT INTO billpayments (user_id, bill_type, issued_date, status, amount) 
            VALUES (?, ?, ?, ?, ?)";

    // Create a prepared statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "isssd", $user_id, $bill_type, $issued_date, $status, $amount);

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../Admin/Services/issueBills.php?success=1");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);

    exit;
}
?>