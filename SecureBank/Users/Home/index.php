<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - User Page</title>
  <link rel="stylesheet" href="../../CSS/styles.css">
  <link rel="stylesheet" href="../CSS/styles.css">
  
  <style>
    .services .service-box {
      display: inline-block;
      width: 200px;
      height: 100px;
      background-color: #f0f0f0;
      border-radius: 10px;
      margin: 10px;
      padding: 20px;
      text-align: center;
      transition: transform 0.3s;
      border: none;
      cursor: pointer;
    }

    .services .service-box:hover {
        transform: translateY(-10px);
        background-color: rgba(0, 0, 0, 0.2);
    }

    .services .service-box h3 {
        margin: 0;
        font-family: 'Times New Roman', Times, serif;
        color: #1d3557;
        font-size: 18px;
    }
  </style>
</head>
 
<body>
<?php include '../Include/header.php'; ?>
   
   <section class="hero">
      <h2>Welcome, <?php echo $user . ' ' . $userFirstName . ' ' . $userLastName; ?>!</h2>
      </br>

      <section class="services">
        <div class="container">
          <p>Explore our range of banking services designed to meet your financial needs:</p>
          <a href="../Services/userAccounts.php"><button type="button" class="service-box">
            <h3>Accounts Overview</h3>
          </button></a>
          <a href="../Services/fundsTransfer.php"><button type="button" class="service-box">
            <h3>Funds Transfer</h3>
          </button></a>
          <a href="../Services/billPayment.php"><button type="button" class="service-box">
            <h3>Bill Payment</h3>
          </button></a>
          <a href="../Services/transactions.php"><button type="button" class="service-box">
            <h3>Transaction History</h3>
          </button></a>
          <a href="../Services/accountStatements.php"><button type="button" class="service-box">
            <h3>Account Statements</h3>
          </button></a>
          <a href="../Services/profileSettings.php"><button type="button" class="service-box">
            <h3>Profile Settings</h3>
          </button></a>
        </div>
    </section>
  </section>


  <?php include($_SERVER['DOCUMENT_ROOT'] . '/SecureBank/Include/footer.php');?>

</body>
</html>