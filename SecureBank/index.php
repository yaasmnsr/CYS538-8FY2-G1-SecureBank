<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Home</title>
  <link rel="stylesheet" href="CSS/styles.css">
</head>

<body>
  <?php include('Include/header.php'); ?>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <h1>Welcome to SecureBank</h1>
    </br>
      <p><i>Your Financial Future, Our Priority</i>
      </br>
        Experience secure and convenient banking services.</p>
      <a href="login.php" class="btn">Get Started</a>
    </div>
  </section>

<!-- About Section -->
<section class="about">
  <div class="container">
    <h2>About SecureBank</h2>
    <p>SecureBank is a comprehensive web security testing platform focused on addressing vulnerabilities in banking websites. 
      It replicates the functionalities and security flaws found in real-world banking systems, enabling thorough vulnerability assessments and risk mitigation. 
      With a user-friendly interface, it caters to website visitors, database administrators, security testers, and project stakeholders.</p>
    <p>SecureBank aims to protect sensitive financial information, improve the security of web applications, and instill trust in consumers. 
      By actively engaging with the security community and staying up-to-date with emerging threats, 
      SecureBank is committed to maintaining a strong defense against cyber threats and ensuring a secure online banking environment.</p>
    <hr style="border-top: 1px solid #f1f1f1; margin-top: 30px; margin-bottom: 30px;">
    <div class="bullet-list">
      <ul class="image-list">
        <li>
          <span class="bullet-point"></span>
          <p>Secure</p>
          <img src="Images/secure.png" alt="Secure Image">
        </li>
        <li>
          <span class="bullet-point"></span>
          <p>Reliable</p>
          <img src="Images/reliable.png" alt="Reliable Image">
        </li>
        <li>
          <span class="bullet-point"></span>
          <p>Fast Transactions</p>
          <img src="Images/transactions.jpeg" alt="Transactions Image">
        </li>
        <li>
          <span class="bullet-point"></span>
          <p>Easy Payments</p>
          <img src="Images/payment.png" alt="Payments Image">
        </li>
      </ul>
    </div>
  </div>
</section>

<?php include('Include/footer.php'); ?>

</body>
</html>