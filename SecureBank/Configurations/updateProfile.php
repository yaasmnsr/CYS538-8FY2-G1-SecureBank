<?php
session_start();

require("databaseConfig.php");

$errors = [];

// Function to sanitize user input
function sanitizeInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return $input;
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_SESSION['user'] === 'Customer') {
        // Get the updated information from the form
        $updatedInfo = [
            'first_name' => sanitizeInput($_POST['first_name']),
            'last_name' => sanitizeInput($_POST['last_name']),
            'password' => sanitizeInput($_POST['password']),
            'email' => sanitizeInput($_POST['email']),
            'phone' => sanitizeInput($_POST['phone']),
            'city' => sanitizeInput($_POST['city']),
            'district' => sanitizeInput($_POST['district']),
            'street' => sanitizeInput($_POST['street'])
        ];

        // Get the user ID from the session
        $userId = $_SESSION['user_id'];

        // Validate and sanitize first name
        if (empty($updatedInfo['first_name'])) {
            $errors[] = "First name is required.";
        } else {
            $updatedInfo['first_name'] = filter_var($updatedInfo['first_name'], FILTER_SANITIZE_STRING);

            // Validate first name format
            $firstNameRegex = "/^[a-zA-Z]+$/"; // Regex pattern for alphabetic characters only

            if (!preg_match($firstNameRegex, $updatedInfo['first_name'])) {
                $errors[] = "Invalid first name format. Only alphabetic characters are allowed.";
            }
        }

        // Validate and sanitize last name
        if (empty($updatedInfo['last_name'])) {
            $errors[] = "Last name is required.";
        } else {
            $updatedInfo['last_name'] = filter_var($updatedInfo['last_name'], FILTER_SANITIZE_STRING);

            // Validate last name format
            $lastNameRegex = "/^[a-zA-Z]+$/"; // Regex pattern for alphabetic characters only

            if (!preg_match($lastNameRegex, $updatedInfo['last_name'])) {
                $errors[] = "Invalid last name format. Only alphabetic characters are allowed.";
            }
        }

        // Check if the entered password matches the current password in the database
        $query = "SELECT password FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();

        if (!(password_verify($updatedInfo['password'], $hashedPassword))) {
            $errors[] = "Incorrect password.";
        }

        // Validate and sanitize email
        if (empty($updatedInfo['email'])) {
            $errors[] = "Email is required.";
        } else {
            $updatedInfo['email'] = filter_var($updatedInfo['email'], FILTER_SANITIZE_EMAIL);

            // Check for empty or blank email address
            if (trim($updatedInfo['email']) === '') {
                $errors[] = "Email is required.";
            } elseif (!filter_var($updatedInfo['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }
        }

        // Validate and sanitize phone
        if (empty($updatedInfo['phone'])) {
            $errors[] = "Phone is required.";
        } else {
            $updatedInfo['phone'] = filter_var($updatedInfo['phone'], FILTER_SANITIZE_STRING);

            // Validate phone number format
            $phoneRegex = "/^05\d{8}$/"; // Regex pattern for 10-digit phone number starting with 05

            if (!preg_match($phoneRegex, $updatedInfo['phone'])) {
                $errors[] = "Invalid phone number format.";
            }
        }

        // Validate and sanitize city
        if (empty($updatedInfo['city'])) {
            $errors[] = "City is required.";
        } else {
            $updatedInfo['city'] = filter_var($updatedInfo['city'], FILTER_SANITIZE_STRING);

            // Validate city format
            $cityRegex = "/^[a-zA-Z\s]+$/"; // Regex pattern for alphabetic characters and spaces

            if (!preg_match($cityRegex, $updatedInfo['city'])) {
                $errors[] = "Invalid city format. Only alphabetic characters and spaces are allowed.";
            }
        }

        // Validate and sanitize district
        if (empty($updatedInfo['district'])) {
            $errors[] = "District is required.";
        } else {
            $updatedInfo['district'] = filter_var($updatedInfo['district'], FILTER_SANITIZE_STRING);

            // Validate district format
            $districtRegex = "/^[a-zA-Z0-9\s]+$/"; // Regex pattern for alphanumeric characters and spaces

            if (!preg_match($districtRegex, $updatedInfo['district'])) {
                $errors[] = "Invalid district format. Only alphanumeric characters and spaces are allowed.";
            }
        }

        // Validate and sanitize street
        if (empty($updatedInfo['street'])) {
            $errors[] = "Street is required.";
        } else {
            $updatedInfo['street'] = filter_var($updatedInfo['street'], FILTER_SANITIZE_STRING);

            // Validate street format
            $streetRegex = "/^[a-zA-Z0-9\s]+$/"; // Regex pattern for alphanumeric characters and spaces

            if (!preg_match($streetRegex, $updatedInfo['street'])) {
                $errors[] = "Invalid street format. Only alphanumeric characters and spaces are allowed.";
            }
        }

        // If there are any validation errors, store them in the session and redirect back to profileSettings.php
        if (!empty($errors)) {
            $_SESSION['update_errors'] = $errors;
            header("Location: ../Users/Services/profileSettings.php");
            exit;
        }

        // Update the user's information in the database
        $query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, city = ?, district = ?, street = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $updatedInfo['first_name'], $updatedInfo['last_name'], $updatedInfo['email'], $updatedInfo['phone'], $updatedInfo['city'], $updatedInfo['district'], $updatedInfo['street'], $userId);
        $stmt->execute();

        // Update the session variables with the updated information
        $_SESSION['first_name'] = $updatedInfo['first_name'];
        $_SESSION['last_name'] = $updatedInfo['last_name'];
        $_SESSION['email'] = $updatedInfo['email'];
        $_SESSION['phone'] = $updatedInfo['phone'];
        $_SESSION['city'] = $updatedInfo['city'];
        $_SESSION['district'] = $updatedInfo['district'];
        $_SESSION['street'] = $updatedInfo['street'];

        // Redirect to the profile settings page
        header("Location: ../Users/Services/profileSettings.php?success=1");
        exit;

    } elseif ($_SESSION['user'] === 'Admin') {
        // Get the updated information from the form
        $updatedInfo = [
            'first_name' => sanitizeInput($_POST['first_name']),
            'last_name' => sanitizeInput($_POST['last_name']),
            'password' => sanitizeInput($_POST['password']),
        ];

        // Get the user ID from the session
        $adminId = $_SESSION['admin_id'];

        // Validate and sanitize first name
        if (empty($updatedInfo['first_name'])) {
            $errors[] = "First name is required.";
        } else {
            $updatedInfo['first_name'] = filter_var($updatedInfo['first_name'], FILTER_SANITIZE_STRING);

            // Validate first name format
            $firstNameRegex = "/^[a-zA-Z]+$/"; // Regex pattern for alphabetic characters only

            if (!preg_match($firstNameRegex, $updatedInfo['first_name'])) {
                $errors[] = "Invalid first name format. Only alphabetic characters are allowed.";
            }
        }

        // Validate and sanitize last name
        if (empty($updatedInfo['last_name'])) {
            $errors[] = "Last name is required.";
        } else {
            $updatedInfo['last_name'] = filter_var($updatedInfo['last_name'], FILTER_SANITIZE_STRING);

            // Validate last name format
            $lastNameRegex = "/^[a-zA-Z]+$/"; // Regex pattern for alphabetic characters only

            if (!preg_match($lastNameRegex, $updatedInfo['last_name'])) {
                $errors[] = "Invalid last name format. Only alphabetic characters are allowed.";
            }
        }

        // Check if the entered password matches the current password in the database
        $query = "SELECT password FROM admins WHERE admin_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();

        if (!(password_verify($updatedInfo['password'], $hashedPassword))) {
            $errors[] = "Incorrect password.";
        }

        // If there are any validation errors, store them in the session and redirect back to profileSettings.php
        if (!empty($errors)) {
            $_SESSION['update_errors'] = $errors;
            header("Location: ../Admin/Services/profileSettings.php");
            exit;
        }

        // Update the user's information in the database
        $query = "UPDATE admins SET first_name = ?, last_name = ? WHERE admin_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $updatedInfo['first_name'], $updatedInfo['last_name'], $adminId);
        $stmt->execute();

        // Update the session variables with the updated information
        $_SESSION['first_name'] = $updatedInfo['first_name'];
        $_SESSION['last_name'] = $updatedInfo['last_name'];

        // Redirect to the profile settings page
        header("Location: ../Admin/Services/profileSettings.php?success=1");
        exit;
    }
}
?>