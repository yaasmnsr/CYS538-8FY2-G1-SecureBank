<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecureBank - Privacy Policy</title>
  <link rel="stylesheet" href="../CSS/styles.css">
  <link rel="stylesheet" href="CSS/styles.css">
</head>

<body>
  <?php
  if (isset($_GET['page'])) {
      // Whitelist of allowed pages
      $allowedPages = ['faq.php', 'privacy-policy.php', 'terms-and-conditions.php'];
      $page = trim($_GET['page']); // Trim leading and trailing whitespace

      // Validate the page parameter
      if (!empty($page) && preg_match('/^[a-zA-Z0-9-]+\.php$/', $page) && in_array($page, $allowedPages)) {
          // Construct the file path
          $filePath = __DIR__ . '/' . $page;

          // Verify the file path
          if (is_file($filePath) && strpos($filePath, __DIR__) === 0) {
              require($filePath);
          } else {
              // Invalid file path
              displayErrorAndRedirect('Invalid file path.');
          }
      } else {
          // Invalid page parameter
          displayErrorAndRedirect('Invalid page parameter.');
      }
  } else {
      // No page parameter provided
      displayErrorAndRedirect('No page parameter provided.');
  }

  function displayErrorAndRedirect($errorMessage) {
      echo "<script>alert('$errorMessage'); window.location.href = '/SecureBank/index.php';</script>";
      exit;
  }
  ?>
</body>
</html>