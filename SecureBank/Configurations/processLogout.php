<?php
  session_start();

  // Terminate the session
  session_destroy();

  // Redirect to the login page or any other desired location
  header("Location: ../index.php");
  exit;
?>