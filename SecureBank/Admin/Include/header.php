<?php
  session_start();

  // Check if the user is not logged in
  if (!isset($_SESSION['admin_id'])) {
    // Admin is not logged in, display warning and redirect
    echo '<script>alert("You must log in to access this page."); window.location.href = "/SecureBank/index.php";</script>';
    exit;
  }

  // User is logged in, access session variables
  $user = $_SESSION['user'];
  $adminFirstName = $_SESSION['first_name'];
  $adminLastName = $_SESSION['last_name'];
  $adminId = $_SESSION['admin_id'];
  $loginTime = $_SESSION['login_time'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Admin</title>
  <link rel="stylesheet" href="/SecureBank/CSS/styles.css">
</head>

<body>
  <!-- Header -->
  <header>
    <nav>
      <div class="container">
        <div class="logo">
          <a class="text-logo" href="/SecureBank/Admin/Home/index.php">
            SecureBank
          </a>
          <img src="/SecureBank/Images/logo.png" alt="SecureBank Logo">
        </div>
        <ul class="nav-links">
          <li><a href="/SecureBank/Admin/Home/index.php">Home</a></li>
          <li><a href="/SecureBank/Admin/Services/profileSettings.php">Profile</a></li>
          <li><a href="/SecureBank/Configurations/processLogout.php">Log Out</a></li>
        </ul>
        <ul class="user-identifier">
          <li><?php echo $adminFirstName . " " . $adminLastName;?></li>
          </br>
          <li><?php echo "ID: " . $adminId;?></li>
        </ul>
      </div>
    </nav>
  </header>
</body>
</html>