<?php
session_start();

require("databaseConfig.php");

$errors = [];
 
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated information from the form
    $profile = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'password' => $_POST['password'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'city' => $_POST['city'],
        'district' => $_POST['district'],
        'street' => $_POST['street']
    ];

    // Validate and sanitize first name
    if (empty($profile['first_name'])) {
        $errors[] = "First name is required.";
    } else {
        $profile['first_name'] = filter_var($profile['first_name'], FILTER_SANITIZE_STRING);

        // Validate first name format
        $firstNameRegex = "/^[a-zA-Z]+$/"; // Regex pattern for alphabetic characters only

        if (!preg_match($firstNameRegex, $profile['first_name'])) {
            $errors[] = "Invalid first name format. Only alphabetic characters are allowed.";
        }
    }

    // Validate and sanitize last name
    if (empty($profile['last_name'])) {
        $errors[] = "Last name is required.";
    } else {
        $profile['last_name'] = filter_var($profile['last_name'], FILTER_SANITIZE_STRING);

        // Validate last name format
        $lastNameRegex = "/^[a-zA-Z]+$/"; // Regex pattern for alphabetic characters only

        if (!preg_match($lastNameRegex, $profile['last_name'])) {
            $errors[] = "Invalid last name format. Only alphabetic characters are allowed.";
        }
    }

    // Perform input validation on the new password
    if (strlen($profile['password']) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    
    if (!preg_match("/[a-z]/", $profile['password'])) {
        $errors[] = "Password must contain at least one lowercase letter.";
    }
    
    if (!preg_match("/[A-Z]/", $profile['password'])) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }
    
    if (!preg_match("/[0-9]/", $profile['password'])) {
        $errors[] = "Password must contain at least one number.";
    }
    
    if (!preg_match("/[^a-zA-Z0-9]/", $profile['password'])) {
        $errors[] = "Password must contain at least one symbol.";
    }

    // Validate and sanitize email
    if (empty($profile['email'])) {
        $errors[] = "Email is required.";
    } else {
        $profile['email'] = filter_var($profile['email'], FILTER_SANITIZE_EMAIL);

        // Check for empty or blank email address
        if (trim($profile['email']) === '') {
            $errors[] = "Email is required.";
        } elseif (!filter_var($profile['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
    }

    // Validate and sanitize phone
    if (empty($profile['phone'])) {
        $errors[] = "Phone is required.";
    } else {
        $profile['phone'] = filter_var($profile['phone'], FILTER_SANITIZE_STRING);

        // Validate phone number format
        $phoneRegex = "/^05\d{8}$/"; // Regex pattern for 10-digit phone number starting with 05

        if (!preg_match($phoneRegex, $profile['phone'])) {
            $errors[] = "Invalid phone number format.";
        }
    }

    // Validate and sanitize city
    if (empty($profile['city'])) {
        $errors[] = "City is required.";
    } else {
        $profile['city'] = filter_var($profile['city'], FILTER_SANITIZE_STRING);

        // Validate city format
        $cityRegex = "/^[a-zA-Z\s]+$/"; // Regex pattern for alphabetic characters and spaces

        if (!preg_match($cityRegex, $profile['city'])) {
            $errors[] = "Invalid city format. Only alphabetic characters and spaces are allowed.";
        }
    }

    // Validate and sanitize district
    if (empty($profile['district'])) {
        $errors[] = "District is required.";
    } else {
        $profile['district'] = filter_var($profile['district'], FILTER_SANITIZE_STRING);

        // Validate district format
        $districtRegex = "/^[a-zA-Z0-9\s]+$/"; // Regex pattern for alphanumeric characters and spaces

        if (!preg_match($districtRegex, $profile['district'])) {
            $errors[] = "Invalid district format. Only alphanumeric characters and spaces are allowed.";
        }
    }

    // Validate and sanitize street
    if (empty($profile['street'])) {
        $errors[] = "Street is required.";
    } else {
        $profile['street'] = filter_var($profile['street'], FILTER_SANITIZE_STRING);

        // Validate street format
        $streetRegex = "/^[a-zA-Z0-9\s]+$/"; // Regex pattern for alphanumeric characters and spaces

        if (!preg_match($streetRegex, $profile['street'])) {
            $errors[] = "Invalid street format. Only alphanumeric characters and spaces are allowed.";
        }
    }

    // If there are any validation errors, store them in the session and redirect back to sign-up.php
    if (!empty($errors)) {
        $_SESSION['create_errors'] = $errors;
        header("Location: ../sign-up.php");
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($profile['password'], PASSWORD_DEFAULT);

    // Prepare the SQL statement with placeholders for the user's information
    $sql = "INSERT INTO users (first_name, last_name, password, email, phone, city, district, street) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Create a prepared statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "ssssssss", $profile['first_name'], $profile['last_name'], $hashedPassword, 
                           $profile['email'], $profile['phone'], $profile['city'], $profile['district'], 
                           $profile['street']);

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Get the auto-generated user ID
        $userId = mysqli_insert_id($conn);

        // Store the user ID in the session
        $_SESSION['user_id'] = $userId;

        // Redirect to the profile settings page with a success message
        header("Location: ../sign-up.php?success=1");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);

    exit;
}
?>