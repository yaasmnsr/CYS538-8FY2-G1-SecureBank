<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - User Page</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">
</head>

<body>
<?php include('../Include/header.php');?>
  <?php
    if (isset($_GET['service'])) {
        $service = $_GET['service'];
      
        // Include the file
        include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Users/Services/' . $service);
        exit;
    } else {
        // No service parameter provided, redirect to an error page or handle it as desired
        header('Location: error.php');
        exit;
    }
    ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php'); ?>
</body>

</html>