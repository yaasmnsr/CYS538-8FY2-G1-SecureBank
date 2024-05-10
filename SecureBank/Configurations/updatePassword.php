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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $previousPassword = sanitizeInput($_POST['previous_password']);
    $newPassword = sanitizeInput($_POST['new_password']);
    $confirmPassword = sanitizeInput($_POST['confirm_password']);

    if($_SESSION['user'] === 'Customer') {
        // Get the user ID from the session
        $userId = $_SESSION['user_id'];

        // Check if the entered password matches the current password in the database
        $query = "SELECT password FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($password);
        $stmt->fetch();
        $stmt->close();

        if (!password_verify($previousPassword, $password)) {
            $errors[] = "Incorrect password.";
        }

        if (password_verify($newPassword, $password)) {
            $errors[] = "New password must be different from the old password.";
        }

        // Perform input validation on the new password
        if (strlen($newPassword) < 8) {
            $errors[] = "New password must be at least 8 characters long.";
        }

        if (!preg_match("/[a-z]/", $newPassword)) {
            $errors[] = "New password must contain at least one lowercase letter.";
        }

        if (!preg_match("/[A-Z]/", $newPassword)) {
            $errors[] = "New password must contain at least one uppercase letter.";
        }

        if (!preg_match("/[0-9]/", $newPassword)) {
            $errors[] = "New password must contain at least one number.";
        }

        if (!preg_match("/[^a-zA-Z0-9]/", $newPassword)) {
            $errors[] = "New password must contain at least one symbol.";
        }

        // Check if the confirmed password matches the new password
        if ($newPassword !== $confirmPassword) {
            $errors[] = "Confirmed password does not match the new password.";
        }
    
        // If there are any validation errors, store them in the session and redirect back to changePassword.php
        if (!empty($errors)) {
            $_SESSION['updatePass_errors'] = $errors;
            header("Location: ../Users/Services/changePassword.php");
            exit;
        }

        $nweHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $query = "UPDATE users SET password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $nweHashedPassword, $userId);
        $stmt->execute();

        // Redirect to the profile settings page
        header("Location: ../Users/Services/changePassword.php?success=1");
        exit;

    } elseif ($_SESSION['user'] === 'Admin') {
        // Get the admin ID from the session
        $adminId = $_SESSION['admin_id'];

        // Check if the entered password matches the current password in the database
        $query = "SELECT password FROM admins WHERE admin_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $stmt->bind_result($password);
        $stmt->fetch();
        $stmt->close();

        if (!password_verify($previousPassword, $password)) {
            $errors[] = "Incorrect password.";
        }

        if (password_verify($newPassword, $password)) {
            $errors[] = "New password must be different from the old password.";
        }

        // Perform input validation on the new password
        if (strlen($newPassword) < 8) {
            $errors[] = "New password must be at least 8 characters long.";
        }

        if (!preg_match("/[a-z]/", $newPassword)) {
            $errors[] = "New password must contain at least one lowercase letter.";
        }

        if (!preg_match("/[A-Z]/", $newPassword)) {
            $errors[] = "New password must contain at least one uppercase letter.";
        }

        if (!preg_match("/[0-9]/", $newPassword)) {
            $errors[] = "New password must contain at least one number.";
        }

        if (!preg_match("/[^a-zA-Z0-9]/", $newPassword)) {
            $errors[] = "New password must contain at least one symbol.";
        }

        // Check if the confirmed password matches the new password
        if ($newPassword !== $confirmPassword) {
            $errors[] = "Confirmed password does not match the new password.";
        }
    
        // If there are any validation errors, store them in the session and redirect back to changePassword.php
        if (!empty($errors)) {
            $_SESSION['updatePass_errors'] = $errors;
            header("Location: ../Admin/Services/changePassword.php");
            exit;
        }

        $nweHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $query = "UPDATE admins SET password = ? WHERE admin_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $nweHashedPassword, $adminId);
        $stmt->execute();

        // Redirect to the profile settings page
        header("Location: ../Admin/Services/changePassword.php?success=1");
        exit;
    }
}
?>