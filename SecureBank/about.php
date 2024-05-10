<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - About</title>
  <link rel="stylesheet" href="CSS/styles.css">

  <script>
    document.addEventListener('DOMContentLoaded', function () {
    var toggles = document.querySelectorAll('.toggle');
    toggles.forEach(function (toggle) {
      toggle.addEventListener('click', function () {
        var paragraph = this.nextElementSibling;
        var arrow = this.querySelector('.arrow');
        var isExpanded = paragraph.classList.contains('visible');
        
        paragraph.classList.toggle('hidden');
        paragraph.classList.toggle('visible');
        paragraph.classList.toggle('slide-down');
        paragraph.classList.toggle('slide-up');
        
        if (isExpanded) {
          toggle.classList.remove('expanded');
          arrow.classList.remove('expanded');
        } else {
          toggle.classList.add('expanded');
          arrow.classList.add('expanded');
        }
      });
    });

    // Toggle "What is SecureBank?" on by default
    var defaultToggle = document.querySelector('.toggle');
    var defaultParagraph = defaultToggle.nextElementSibling;
    var defaultArrow = defaultToggle.querySelector('.arrow');
    defaultParagraph.classList.remove('hidden');
    defaultParagraph.classList.add('visible');
    defaultParagraph.classList.add('slide-down');
    defaultToggle.classList.add('expanded');
    defaultArrow.classList.add('expanded');
    });
  </script>
</head>

<body>
  <?php include('Include/header.php'); ?>
  
  <section class="about">
    <div class="container">
      <h2>About SecureBank</h2>
      <div class="about-page">
        <h3 class="toggle">
          <span class="arrow"></span> What is SecureBank?
        </h3>
        <p class="hidden slide-down">
          SecureBank is a comprehensive web security testing platform designed to address the vulnerabilities commonly found in banking websites. 
          Our mission is to provide a secure and reliable online banking experience for individuals and businesses alike. 
          With a strong focus on combating Remote File Inclusion (RFI) and Local File Inclusion (LFI) attacks, 
          we aim to protect sensitive financial information and ensure the utmost security of our users' data.
        </p>
        
        <h3 class="toggle">
          <span class="arrow"></span> Our Mission
        </h3>
        <p class="hidden slide-down">
          At SecureBank, we understand the critical importance of web application security. 
          Our team works tirelessly to create a robust platform that accurately simulates real-world banking systems. 
          By replicating the functionalities and security flaws typically found in banking websites, we enable thorough vulnerability assessments and risk mitigation.
        </p>
        
        <h3 class="toggle">
          <span class="arrow"></span> What Do We Offer?
        </h3>
        <p class="hidden slide-down">
          Through our website, users can access a range of features and content while enjoying peace of mind knowing that their interactions are protected by state-of-the-art security measures.
          Our platform caters to various end users, including website visitors, database administrators, security testers, and project stakeholders.
          With a user-friendly interface and comprehensive security testing capabilities, we empower organizations to identify and address potential vulnerabilities,
          bolster the resilience of their web applications, and instill trust in their consumers.
        </p>
        
        <h3 class="toggle">
          <span class="arrow"></span> Our Team
        </h3>
        <p class="hidden slide-down">
          SecureBank is committed to transparency and collaboration. We actively engage with the security community,
          constantly enhancing our platform's capabilities and staying up-to-date with emerging threats and best practices. 
          Our goal is to be at the forefront of web security, helping organizations proactively safeguard their digital assets and maintain a strong defense against evolving cyber threats.
        </p>

        <h3 class="toggle">
          <span class="arrow"></span> Team Members
        </h3>
        <p class="hidden slide-down">
          - Hessa Almegren<br>
          - Norah Alqahtani<br>
          - Nouf Alsagour<br>
          - Noor Albarrak<br>
          - Yasmeen Almansour<br>
        </p>
      </div>
      <hr style="border-top: 1px solid #f1f1f1; margin-top: 20px; margin-bottom: 50px;">
      <h2> Our Features</h2>
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
        <hr style="border-top: 1px solid #f1f1f1; margin-top: 20px; margin-bottom: 50px;">

      <p style="border: 2px solid #1d3557; background: #f1f1f1;; padding: 10px; margin-top: 20px;"><i>Join us on this journey towards a more secure online banking environment.
        Experience the peace of mind that comes with knowing your financial information is protected with SecureBank.</i></p>
    </div>
  </section>

  <?php include('Include/footer.php'); ?>

</html>