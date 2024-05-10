<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Admin Page</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">
</head>

<body>
  <?php include('../Include/header.php'); ?>

  <section class="hero">
      <h2>Welcome, <?php echo $user . ' ' . $adminFirstName . ' ' . $adminLastName; ?>!</h2>
      </br>

      <section class="services">
      <div class="container">
        <p>Explore our range of services designed to meet your admin privileges:</p>
        <a href="../Services/viewUsers.php"><button type="button" class="service-box">
          <h3>View Users</h3>
        </button></a>
        <a href="../Services/viewAccounts.php"><button type="button" class="service-box">
          <h3>View Account</h3>
        </button></a>
        <a href="../Services/manageAccounts.php"><button type="button" class="service-box">
          <h3>Manage Account</h3>
        </button></a>
        <a href="../Services/issueBills.php"><button type="button" class="service-box">
          <h3>Issue Bills</h3>
        </button></a>
        <a href="../Services/transferLogs.php"><button type="button" class="service-box">
          <h3>Transfer Logs</h3>
        </button></a>
        <a href="../Services/billLogs.php"><button type="button" class="service-box">
          <h3>Bill Payment Logs</h3>
        </button></a>
      </div>
    </section>
  </section>


  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php');?>

</body>
</html>