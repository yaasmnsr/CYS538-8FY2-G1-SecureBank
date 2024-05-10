<?php
  // Function to retrieve account data from the database
  function retrieveAccountDataFromDatabase($userId) {
  // Replace the following with your database credentials
  require($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Configurations/databaseConfig.php');

  // Prepare the query to retrieve account data based on the user ID
  $sql = "SELECT * FROM accounts WHERE user_id = '$userId'";
  $result = $conn->query($sql);

  // Check if any rows were returned
  if ($result->num_rows > 0) {
    // Initialize an array to store the account data
    $accounts = array();

    // Fetch each row and add it to the accounts array
    while ($row = $result->fetch_assoc()) {
      $accounts[] = $row;
    }

    // Close the database connection
    $conn->close();

    // Return the account data
    return $accounts;
  } else {
    // If no rows were returned, handle the error or return an empty array
    $conn->close();
    return array();
  }
}
  // Start the session
  session_start();

  // Check if the user is not logged in
  if (!isset($_SESSION['user_id'])) {
    // User is not logged in, display warning and redirect
    echo '<script>alert("You must log in to access this page."); window.location.href = "/SecureBank/index.php";</script>';
    exit;
  }

  // User is logged in, access session variables
  $user = $_SESSION['user'];
  $userFirstName = $_SESSION['first_name'];
  $userLastName = $_SESSION['last_name'];
  $userId = $_SESSION['user_id'];
  $email = $_SESSION['email'];
  $phone = $_SESSION['phone'];
  $city = $_SESSION['city'];
  $district = $_SESSION['district'];
  $street = $_SESSION['street'];
  $loginTime = $_SESSION['login_time'];

  // Retrieve the updated account information from the database
  $accounts = retrieveAccountDataFromDatabase($userId);

  // Update the session variable with the new data
  $_SESSION['accounts'] = $accounts;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - User Page</title>
  <link rel="stylesheet" href="/SecureBank/CSS/styles.css">

</head>

<body>
  <!-- Header -->
  <header>
    <nav>
      <div class="container">
        <div class="logo">
          <a class="text-logo" href="/SecureBank/Users/Home/index.php">
            SecureBank
          </a>
          <img src="/SecureBank/Images/logo.png" alt="SecureBank Logo">
        </div>
        <ul class="nav-links">
          <li><a href="/SecureBank/Users/Home/index.php">Home</a></li>
          <li><a href="/SecureBank/Users/Services/userAccounts.php">Accounts</a></li>
          <li><a href="/SecureBank/Users/Services/profileSettings.php">Profile</a></li>
          <li><a href="/SecureBank/Configurations/processLogout.php">Log Out</a></li>
        </ul>
        <ul class="user-identifier">
          <li><?php echo $userFirstName . " " . $userLastName;?></li>
          </br>
          <li><?php echo "ID: " . $userId;?></li>
        </ul>
      </div>
    </nav>
  </header>
</body>
</html>