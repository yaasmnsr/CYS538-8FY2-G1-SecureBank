<?php
session_start();

require('databaseConfig.php');

// A variable to hold the error message
$errorMsg = [];

// Function to sanitize user input
function sanitizeInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the submitted ID and password
    $id = sanitizeInput($_POST['id']);
    $password = sanitizeInput($_POST['password']);

    // Prepare the statement with a parameter - for USERS
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $id);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the user records
    $user = $result->fetch_assoc();

    // Check if a matching user is found in the users table
    if ($user && password_verify($password, $user['password'])) {
        // Password verification succeeded

        // Set session variables
        $_SESSION['user'] = 'Customer';
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['city'] = $user['city'];
        $_SESSION['district'] = $user['district'];
        $_SESSION['street'] = $user['street'];
        $_SESSION['login_time'] = date('Y-m-d H:i:s'); // Set the login time to the current timestamp

        // Retrieve account details from the database
        $stmt = $conn->prepare("SELECT * FROM accounts WHERE user_id = ?");
        $stmt->bind_param("s", $user['user_id']);
        $stmt->execute();
        $accountResult = $stmt->get_result();
        $accounts = $accountResult->fetch_all(MYSQLI_ASSOC);

        // Store the account details in the session
        $_SESSION['accounts'] = $accounts;

        // Redirect the user to the users' page
        header('Location: ../Users/Home/index.php');
        exit;
    } else {
        // Prepare the statement with a parameter - for ADMINS
        $stmt = $conn->prepare("SELECT * FROM admins WHERE admin_id = ?");
        $stmt->bind_param("s", $id);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the admin records
        $admin = $result->fetch_assoc();

        // Check if a matching admin is found in the admins table
        if ($admin && password_verify($password, $admin['password'])) {
            // Password verification succeeded

            // Set session variables
            $_SESSION['user'] = 'Admin';
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['first_name'] = $admin['first_name'];
            $_SESSION['last_name'] = $admin['last_name'];
            $_SESSION['login_time'] = time(); // Set the login time to the current timestamp

            // Redirect the admin to the admin page
            header('Location: ../Admin/Home/index.php');
            exit;
        } else {
            // Password verification failed or user not found
            $errorMsg[] = "Invalid ID or Password!";
            $_SESSION['errorMsg'] = $errorMsg;
            header('Location: ../login.php');
            exit;
        }
    }
}

// Close the statement
$stmt->close();

// Close the connection
$conn->close();
?>